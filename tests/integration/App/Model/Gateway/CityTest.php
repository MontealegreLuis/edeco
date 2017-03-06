<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Gateway;

use App\Model\City;
use App\Model\Dao\CityDao;
use App\Model\Dao\StateDao;
use App\Model\Gateway\City as CityGateway;
use App\Model\Gateway\State as StateGateway;
use App\Model\State;
use ControllerTestCase;
use Mandragora\PHPUnit\DoctrineTest\DoctrineTestInterface;

class CityTest extends ControllerTestCase implements DoctrineTestInterface
{
    /** @test */
    public function it_retrieves_all_cities_of_a_given_state()
    {
        $insertedCities = [];
        $this->insertState();
        for ($i = 0; $i < 5; $i++) {
            $insertedCities[$i] = $this->insertCity();
        }
        $gatewayCity = new CityGateway(new CityDao());
        $allCities = $gatewayCity->findAllByStateId($this->state->id);
        $this->assertCount(5, $allCities);
        for ($i = 0; $i < 5; $i++) {
            $this->assertEquals(
                $insertedCities[$i]->name, $allCities[$i]['name']
            );
        }
    }

    /** @test */
    public function it_retrieves_zero_results_with_a_non_existing_state()
    {
        $gatewayCity = new CityGateway(new CityDao());
        $this->insertState();
        $allStates = $gatewayCity->findAllByStateId($this->state->id);
        $this->assertCount(0, $allStates);
    }

    /** @before */
    public function createStateGateway(): void
    {
        $this->stateGateway = new StateGateway(new StateDao());
    }

    private function insertState(): void
    {
        $this->state = new State();
        $this->state->name = 'MÉXICO';
        $this->state->url = 'mexico';
        $this->stateGateway->insert($this->state);
    }

    private function insertCity(): City
    {
        $gatewayCity = new CityGateway(new CityDao());
        $city = new City();
        $city->name = 'Cholula';
        $city->url = 'cholula';
        $city->stateId = $this->state->id; //the value assigned on insertState
        $gatewayCity->insert($city);
        return $city;
    }

    /** @var State*/
    protected $state;

    /** @var StateGateway */
    protected $stateGateway;
}
