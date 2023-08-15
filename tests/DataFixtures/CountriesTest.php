<?php
namespace App\Tests\DataFixtures;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\DataFixtures\Countries;

class CountriesTest extends WebTestCase {
    public function test_get_country() {
        $country = new Countries("HRV", "SLO");
        $testCountry = $country->get_country("HRV");
        $testCountry = array_shift($testCountry);
      $this->assertEquals("HRV",  $testCountry->cca3);
   }

    public function test_countries_exist_and_have_borders() {
        $country = new Countries("HRV", "SLO");
        $countryExist = $country->countries_exist_and_have_borders();
        $this->assertFalse($countryExist);
        $country = new Countries("HRV", "SVN");
        $countryExist = $country->countries_exist_and_have_borders();
        $this->assertTrue($countryExist);
    }
}