<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Gateway;

use App\Model\Dao\CityDao;
use App\Model\Gateway\City as CityGateway;
use ControllerTestCase;
use Edeco\Fixtures\CitiesFixture;
use Mandragora\PHPUnit\DoctrineTest\DoctrineTestInterface;

class CityTest extends ControllerTestCase implements DoctrineTestInterface
{
    /** @test */
    function it_retrieves_all_cities_of_a_given_state()
    {
        $existingCities = $this->fixture->cities();

        $allCities = $this->cityGateway->findAllByStateId($this->fixture->stateId());

        $this->assertCount(5, $allCities);
        $i = 0;
        foreach ($existingCities as $existingCity) {
            $this->assertEquals($existingCity['name'], $allCities[$i]['name']);
            $i++;
        }
    }

    /** @test */
    function it_retrieves_zero_results_with_a_non_existing_state()
    {
        $this->assertCount(0, $this->cityGateway->findAllByStateId(-1));
    }

    /** @before */
    function createStateGateway(): void
    {
        /** @var \Mandragora\Application\Doctrine\Manager $manager */
        $manager = $this->_frontController->getParam('bootstrap')->getResource('doctrine');
        $this->fixture = CitiesFixture::fromDSN($manager->getConfiguration()['dsn']);
        $this->cityGateway = new CityGateway(new CityDao());
    }

    /** @var CityGateway */
    private $cityGateway;

    /** @var CitiesFixture */
    private $fixture;
}
