<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\DataFixtures\Countries;
class RoutsController extends AbstractController
{

    private function status_code_400(): Response
    {
        $response = new Response();
        return $response->setStatusCode(400);
    }


    private function get_node (array $from, string $node): string {
        $nodeFound = "";
        foreach ($from as $obj) {
            $key = array_search($node, $obj);
            if ($key) {
                $nodeFound=$key;
            }
        }
        return $nodeFound;
    }

    private function find_shortest_route(Countries $countries):  array
    {
        $destinationCountry = $countries->get_destination_country();
        $currentCountry = $countries->get_source_country();
        $queue[0] =$currentCountry;
        $previous = [];
        $currentCountryName = $queue[0]->cca3;
        $visitedCountry[$currentCountryName] = true;
        $visitedBorders[$currentCountryName] = true;
        $nextNode = [];
        $routeFound=false;

        while (!empty($queue)) {
            $currentCountry = array_shift($queue);
            if ($currentCountry->cca3 === $destinationCountry->cca3) {
                break;
            } else {
                foreach ($currentCountry->borders as $neighbour) {
                    $neighbourCountry = $countries->get_country($neighbour);
                    $neighbourCountryObj = reset($neighbourCountry);
                    if (!array_key_exists($neighbour,  $visitedBorders)) {
                        $visitedBorders[$neighbour] = true;
                        $previous[] = [$currentCountry->cca3 => $neighbour];
                        if (strtoupper($neighbour) == strtoupper($destinationCountry->cca3)) {
                            $currentCountry = $neighbourCountryObj;
                            $routeFound = true;
                            break;
                        }
                        if (!array_key_exists($neighbour,  $visitedCountry)) {
                            $nextNode[$neighbour]=$neighbourCountry;
                        }
                    }
                }
                if (!$routeFound && !empty($nextNode)) {
                    $next =  array_shift($nextNode);
                    $queue = $next;
                }
            }
        }
        if (!$currentCountry->cca3 === $destinationCountry->cca3) {
            return [];
        }
        $path = [];
        for ($node = $destinationCountry->cca3; $node != null; $node = $this->get_node($previous, $node)) {
            $path[] = $node;
        }

        return $path;
    }

    #[Route('/routing/{source}/{destination}', name: 'get_optimal_route', methods:['GET'] )]
    public function get_optimal_route($source, $destination):Response {
        $countries = new Countries($source, $destination);
        if (!$countries->countries_exist_and_have_borders()) {
            return $this->status_code_400();
        }
        return $this->json(["route"=>array_reverse($this->find_shortest_route($countries))]);
    }
}