<?php
/**
 * Unit tests for Edeco_Service_Address class
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
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Edeco_Service_AddressTest::main');
}

require_once realpath(dirname(__FILE__) . '/../bootstrap.php');

/**
 * Unit tests for Edeco_Service_Address class
 *
 * @author     LNJ <lemuel.nonoal@mandragora-web-systems.com>
 * @version    SVN: $Id$
 * @copyright  Mandrágora Web-Based Systems 2010
 * @category   Tests
 * @package    Edeco
 * @subpackage Test
 */

class Edeco_Service_AddressTest
    extends ControllerTestCase
    implements Mandragora_PHPUnit_DoctrineTest_Interface
{
    /**
     * @var Edeco_Service_Address
     */
    private $address;

    /**
     * @var Dica_Model_Gateway_City
     */
    protected $cityGateway;

    /**
     * @var Dica_Model_Dao_City
     */
    protected $cityDao;

    /**
     * @var Dica_Model_Gateway_State
     */
    protected $stateGateway;

    /**
     * @var Dica_Model_Dao_State
     */
    protected $stateDao;

    /**
     * Executes all the available tests cases
     *
     * @return void
     */
    public static function main()
    {
        $suite = new PHPUnit_Framework_TestSuite('Edeco_Service_AddressTest');
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

        $this->cityDao = new Edeco_Model_Dao_City();
        $this->cityGateway = new Edeco_Model_Gateway_City($this->cityDao);

        $this->stateDao = new Edeco_Model_Dao_State();
        $this->stateGateway = new Edeco_Model_Gateway_State($this->stateDao);
    }

    public function testGetAddressForm()
    {
        $data = array(
            'streetAndNumber' => 'priv tabacos',
            'neighborhood' => 'Ignacio Romero V', 'state' => 1,
            'city' => 1, 'zipCode' => 72120, 'country' => 'México'
        );
        $address = new Edeco_Service_Address(
            new Edeco_Model_Address(),
            new Edeco_Model_Gateway_Property(new Edeco_Model_Dao_Property())
        );
        $dataForm = $address->getAddressFormForAdding('')->getErrors();
        $this->assertTrue(is_array($dataForm));
    }

    public function testIsAddressFormValidMustReturnTrue()
    {
        $state = new Edeco_Model_State(array('id' => 1, 'name' => 'Puebla'));
        $this->stateGateway->insert($state);
        $city = new Edeco_Model_City(
            array('id' => 1, 'name' => 'tepexi', 'stateId' => 1)
        );
        $this->cityGateway->insert($city);
        $data = array(
            'propertyId' => 1, 'streetAndNumber' => 'priv tabacos',
            'neighborhood' => 'Ignacio Romero V ', 'state' => 'Puebla',
            'city' => 'tepexi', 'zipCode' => 72120, 'country' => 'México'
        );
        $address = new Edeco_Service_Address(
            new Edeco_Model_Address(),
            new Edeco_Model_Gateway_Property(new Edeco_Model_Dao_Property())
        );
        $address->getAddressFormForAdding('');
        $this->assertTrue($address->isAddressFormValid($data));
    }

    public function testFindAddressByPropertyId()
    {
    	$property = $this->createProperty();
    	$address = new Edeco_Service_Address(
            new Edeco_Model_Address(),
            new Edeco_Model_Gateway_Property(new Edeco_Model_Dao_Property())
        );
        $propertyGateway = new Edeco_Model_Gateway_Property(
            new Edeco_Model_Dao_Property()
        );
        $propertyGateway->insert($property);
        $address->findAddressByPropertyId(1);
        $addressDB = $propertyGateway->findAddressByPropertyId(1);
        $this->assertEquals(
            'Priv tabacos,Ignacio Romero V,72120,Puebla,Tepexi,México',
            $addressDB
        );
    }

    public function testDeletePropertyAddress()
    {
    	$property = $this->createProperty();
    	$address = new Edeco_Model_Address();
        $serviceAddress = new Edeco_Service_Address(
            $address,
            new Edeco_Model_Gateway_Property(new Edeco_Model_Dao_Property())
        );
        $propertyGateway = new Edeco_Model_Gateway_Property(
            new Edeco_Model_Dao_Property()
        );
        $propertyGateway->insert($property);
        $addressChange = "109 b poniente 1542,pluss, 1,1,72000,in";
        $address->fromString($addressChange);
        $serviceAddress->updatePropertyAddress($address->toArray(), 1);
        $addressDB = $propertyGateway->findAddressByPropertyId(1);
        $this->assertEquals($addressChange,$addressDB);
    }

    /**
     * @return Edeco_Model_Property
     */
    protected function createProperty()
    {
        $propertyInformation = array(
            'name' => 'Local Comercial X', 'url' => 'www.ejemplo.com',
            'description' => 'Buena ubicación', 'price' => 'Casi regalado',
            'address' =>
                'Priv tabacos,Ignacio Romero V,72120,Puebla,Tepexi,México',
            'addressReference' => 'ejemplo de referencias de direccon',
            'latitude' => 120.5, 'longitude' => 457.8, 'category' => 'lands',
            'totalSurface' => 120, 'metersOffered' => 13, 'metersFront' => 10,
            'landUse' => Edeco_Enum_PropertyLandUse::Commercial,
            'creationDate' => '2010-01-01', 'showOnWeb' => 1,
        );
        $property = new Edeco_Model_Property($propertyInformation);
        return $property;
    }

}
if (PHPUnit_MAIN_METHOD == 'Edeco_Service_AddressTest::main') {
    Edeco_Service_AddressTest::main();
}