<?php
namespace App\Tests\Controller;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Controller\RoutsController;

class RoutsControllerTest extends WebTestCase {
    public function test_class() {
        $testClass = new RoutsController();
        $this->assertNotEmpty($testClass);
    }
    public function test_source_country_not_found() {
        $client = static::createClient();
        $client->request('GET', '/routing/slo/hrv');
        $this->assertResponseStatusCodeSame("400");
    }

    public function test_destination_country_not_found() {
    $client = static::createClient();
    $client->request('GET', '/routing/hrv/slo');
    $this->assertResponseStatusCodeSame("400");
    }

    public function test_single_country_route() {
        $client = static::createClient();
        $client->request('GET', '/routing/hrv/hrv');
        $response = $client->getResponse();
        $expectedData = $response->getContent();
        $json = json_encode(["route"=>["HRV"]]);
        $this->assertEquals($json, $expectedData);
    }

    public function test_multiple_country_route() {
        $client = static::createClient();
        $client->request('GET', '/routing/LSO/MYS');
        $response = $client->getResponse();
        $expectedData = $response->getContent();
        $json = json_encode(["route"=>["LSO","ZAF","BWA","ZMB","COD","CAF","SDN","EGY","ISR","JOR","IRQ","IRN","AFG","CHN","MMR","THA","MYS"]]);
        $this->assertEquals($json, $expectedData);
    }
}