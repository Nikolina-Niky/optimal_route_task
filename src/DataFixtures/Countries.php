<?php
namespace App\DataFixtures;
class Countries
{
    private array $countryList = [];
    private $sourceCountry;
    private $destinationCountry;

    public function set_country_list($countryList): void {
        $this->countryList = $countryList;
    }

    public function set_source_country($sourceCountry): void {
        $this->sourceCountry = $sourceCountry;
    }
    public function get_source_country() {
        return $this->sourceCountry;
    }
    public function set_destination_country($destinationCountry): void {
        $this->destinationCountry = $destinationCountry;
    }

    public function get_destination_country() {
        return $this->destinationCountry;
    }

    public function __construct(string $source, string $destination) {
        $json = file_get_contents("https://raw.githubusercontent.com/mledoze/countries/master/countries.json");
        //set country list
        $this->set_country_list(json_decode($json));
        //set source country
        $sourceCountry = $this->get_country($source);
        $objSourceCountry = array_shift($sourceCountry);
        $this->set_source_country($objSourceCountry);
        //set destination country
        $destinationCountry = $this->get_country($destination);
        $objDestinationCountry = array_shift($destinationCountry);
        $this->set_destination_country($objDestinationCountry);
    }
    public function get_country(string $country): array
    {
        if (!$country || empty($this->countryList)) {
            return [];
        }
        return array_filter($this->countryList, function ($obj) use ($country) {
            if ($obj->cca3 == strtoupper($country)) {
                return $obj;
            }
            return []; //it will cover situations when source country is not in the list
        });
    }

    public function countries_exist_and_have_borders(): bool
    {
        if (
            empty($this->sourceCountry) ||
            empty($this->destinationCountry) ||
            empty($this->sourceCountry->borders) ||
            empty($this->destinationCountry->borders)
        ) {
            return false;
        }
        return true;
    }
}