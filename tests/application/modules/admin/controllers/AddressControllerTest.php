<?php
/**
 * Unit tests for Admin_AddressController class
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
    define('PHPUnit_MAIN_METHOD', 'AddressControllerTest::main');
}

require_once realpath(dirname(__FILE__) . '/../../../bootstrap.php');

/**
 * Unit tests for AddressController class
 *
 * @author     LNJ <lemue.nonoal@mandragora-web-systems.com>
 * @version    SVN: $Id$
 * @copyright  Mandrágora Web-Based Systems 2010
 * @category   Tests
 * @package    Dica
 * @subpackage Test
 */
class AddressControllerTest extends ControllerTestCase
{
    /**
     * @var Zend_View_Helper_Url
     */
    protected $urlHelper;

    /**
     * Executes all the available tests cases
     *
     * @return void
     */
    public static function main()
    {
        $suite = new PHPUnit_Framework_TestSuite('AddressControllerTest');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    /**
     * Setup application to run test cases
     *
     * @see tests/application/ControllerTestCase#setUp()
     */
    public function setUp()
    {
        parent::setUp();
        $this->urlHelper = new Zend_View_Helper_Url();
        
        $this->cityDao = new Edeco_Model_Dao_City();
        $this->cityGateway = new Edeco_Model_Gateway_City($this->cityDao);

        $this->stateDao = new Edeco_Model_Dao_State();
        $this->stateGateway = new Edeco_Model_Gateway_State($this->stateDao);
    }
    
    /**
     * Reset request
     */
    public function tearDown() {
        $this->resetRequest();
        $this->resetResponse();
        parent::tearDown();
    }
    
    public function testUpdateActionChangeAddress()
    {
    	$property = $this->createProperty();
        $propertyGateway = new Edeco_Model_Gateway_Property(
            new Edeco_Model_Dao_Property());
        $propertyGateway->insert($property);

        $this->getRequest()->setMethod('GET');
    	$this->getRequest()->setParams( array('propertyId' => $property->id));
    	$this->doLogin();
        $this->dispatch($this->urlHelper->url(array(
            'propertyId' => $property->id), 'updateAddress')
        );
        $this->assertController('address');
        $this->assertAction('update');
        $this->assertModule('admin');
        $this->assertNotRedirect();
        $this->assertQuery('#frmDetail');
        
    }
        
    public function testAddActionInsertsANewAddressForProperty()
    {
        $property = $this->createProperty();
        $propertyGateway = new Edeco_Model_Gateway_Property(
            new Edeco_Model_Dao_Property());
        $propertyGateway->insert($property);
        
        $data = array(
            'streetAndAddres' => 'juan de palafox',
            'neighborhood' => 'centro', 'zipCode' => 72000,
            'city' => 'puebla', 'state' => 'puebla', 'country' => 'México'
        );
        
        $modelAddress = new Edeco_Model_Address($data);
        $this->getRequest()->setMethod('POST')                           ->setPost($modelAddress->toArray());
        $this->getRequest()->setParams( array('propertyId' => $property->id));
        $this->doLogin();
        $this->dispatch($this->urlHelper->url(array(
            'propertyId' => $property->id), 'addAddress')
        );
        $address = $propertyGateway->findAddressById($property->id);
        //die(var_dump($address));
        $this->assertEquals($address, strlen($modelAddress));
        $this->assertController('address');
        $this->assertAction('add');
        $this->assertModule('admin');
        $this->assertRedirect('/admin/inmuebles.html');
        $this->assertQuery('#frmDetail');
        
    }
    
    protected function  doLogin( $identity  = null )
    {
        if ( $identity === null ) {
            $identity = $this->createUser();
        }

        Zend_Auth::getInstance()->getStorage()->write($identity );
    }
    

    public function createUser()
    {
        $user = array(
            'username' => 'x0_nonoal_x0@gmail.com', 
            'password' => 'lemuel', 'state' => 'active'
        );
        
        $user = new Edeco_Model_User($user);
        
        return $user;
    }
    
    /**
     * @return Edeco_Model_Property
     */
    protected function createProperty()
    {
        $data = array(
	        'name' => 'Bodega', 'url' => 'www.bodega.com', 
	        'description' => 'aplia bodega', 'price' => 'regular', 
	        'addressReference' => 'megacable', 'latitude' => 133.5, 
	        'longitude' => 543.3, 'category' => 'warehouses', 
	        'totalSurface' => 200, 'metersOffered' => 100, 'metersFront' => 15,
	        'landUse' => 'housing', 'creationDate' => '2010-01-01', 'active' => 1
        );
        
        $property = new Edeco_Model_Property($data);
        
        return $property;
    }
    
}

if (PHPUnit_MAIN_METHOD == 'AddressControllerTest::main') {
    AddressControllerTest::main();
}