<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use App\Model\Dao\CityDao;
use App\Model\Dao\StateDao;
use App\Model\Gateway\City;
use App\Model\Gateway\State;
use Mandragora\PHPUnit\DoctrineTest\DoctrineTestInterface;

/**
 * Unit tests for Edeco_Model_Gateway_City class
 */
class Edeco_Model_Gateway_CityTest extends ControllerTestCase implements DoctrineTestInterface
{
    /**
     * @var Edeco_Model_State
     */
    protected $state;

	/** @var State */
	protected $gatewayState;

    /**
     * Setup application to run test cases
     *
     * @see tests/application/ControllerTestCase#setUp()
     */
    public function setUp()
    {
        parent::setUp();
        $this->gatewayState = new State(new StateDao());
    }

    /**
     * @return void
     */
    public function testFindAllByStateNameRetrieveAllActiveCitiesOfAGivenState()
    {
        $insertedCities = [];
        $this->insertState();
        for ($i = 0; $i < 5; $i++) {
            $insertedCities[$i] = $this->insertCity();
        }
        $gatewayCity = new City(new CityDao());
        $allCities = $gatewayCity->findAllByStateId($this->state->id);
        $this->assertCount(5, $allCities);
        for ($i = 0; $i < 5; $i++) {
            $this->assertEquals(
                $insertedCities[$i]->name, $allCities[$i]['name']
            );
        }
    }

    /**
     * Verifies if findAllByStateName method retrieves 0
     *
     * @return void
     */
    public function testFindAllByStateNameShouldRetrieveZeroRecordsWithNonExistingState()
    {
        $gatewayCity = new City(new CityDao());
        $this->insertState();
        $allStates = $gatewayCity->findAllByStateId($this->state->id);
        $this->assertCount(0, $allStates);
    }

    /**
     * @return void
     */
    protected function insertState()
    {
        $this->state = new \App\Model\State();
        $this->state->name = 'MÉXICO';
        $this->state->url = 'mexico';
        $this->gatewayState->insert($this->state);
    }

    /**
     * @return \App\Model\City
     */
    protected function insertCity()
    {
        $gatewayCity = new City(new CityDao());
        $city = new \App\Model\City();
        $city->name = 'Cholula';
        $city->url = 'cholula';
        $city->stateId = $this->state->id; //the value assigned on insertState
        $gatewayCity->insert($city);
        return $city;
    }
}
