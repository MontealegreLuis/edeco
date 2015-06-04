<?php
/**
 * Unit tests for Edeco_Model_Gateway_City class
 *
 * PHP version 5
 *
 * LICENSE: Redistribution and use of this file in source and binary forms,
 * with or without modification, is not permitted under any circumstance
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   Tests
 * @package    Edeco
 * @subpackage Test
 * @author     LNJ <lemuel.nonoal@mandragora-web-systems.com>
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Edeco_Model_Gateway_CityTest::main');
}

require_once realpath(dirname(__FILE__) . '/../../bootstrap.php');

/**
 * Unit tests for Edeco_Model_Gateway_City class
 *
 * @category   Tests
 * @package    Edeco
 * @subpackage Test
 * @author     LNJ <lemuel.nonoal@mandragora-web-systems.com>
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class Edeco_Model_Gateway_CityTest
    extends ControllerTestCase
    implements Mandragora_PHPUnit_DoctrineTest_Interface
{
    /**
     * @var Edeco_Model_State
     */
    protected $state;

	/*
	 * @var Edeco_Model_Gateway_State
	 */
	protected $gatewayState;

    /**
     * Executes all the available tests cases
     *
     * @return void
     */
    public static function main()
    {
        $suite = new PHPUnit_Framework_TestSuite(
            'Edeco_Model_Gateway_CityTest'
        );
        $listener = new Mandragora_PHPUnit_Listener();
        $result = PHPUnit_TextUI_TestRunner::run(
            $suite, array('listeners' => array($listener))
        );
    }

    /**
     * Setup application to run test cases
     *
     * @see tests/application/ControllerTestCase#setUp()
     */
    public function setUp()
    {
        parent::setUp();
        $this->gatewayState = new Edeco_Model_Gateway_State(
            new Edeco_Model_Dao_State()
        );
    }

    /**
     * @return void
     */
    public function testFindAllByStateNameRetrieveAllActiveCitiesOfAGivenState()
    {
        $insertedCities = array();
        $this->insertState();
        for ($i = 0; $i < 5; $i++) {
            $insertedCities[$i] = $this->insertCity();
        }
        $gatewayCity = new Edeco_Model_Gateway_City(
            new Edeco_Model_Dao_City()
        );
        $allCities = $gatewayCity->findAllByStateName('MÉXICO');
        $this->assertEquals(5, count($allCities));
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
    public function
        testFindAllByStateNameShouldRetrieveZeroRecordsWithNonExistingState()
    {
        $gatewayCity = new Edeco_Model_Gateway_City(
            new Edeco_Model_Dao_City()
        );
        $allStates = $gatewayCity->findAllByStateName('Mexico');
        $this->assertEquals(0, count($allStates));
    }

    /**
     * @return void
     */
    protected function insertState()
    {
        $this->state = new Edeco_Model_State();
        $this->state->name = 'MÉXICO';
        $this->gatewayState->insert($this->state);
    }

    /**
     * @return Edeco_Model_City
     */
    protected function  insertCity()
    {
        $gatewayCity = new Edeco_Model_Gateway_City(
            new Edeco_Model_Dao_City()
        );
        $city = new Edeco_Model_City();
        $city->name = 'cholula';
        $city->stateId = $this->state->id; //the value assigned on insertState
        $gatewayCity->insert($city);
        return $city;
    }

}

if (PHPUnit_MAIN_METHOD == 'Edeco_Model_Gateway_CityTest::main') {
    Edeco_Model_Gateway_CityTest::main();
}