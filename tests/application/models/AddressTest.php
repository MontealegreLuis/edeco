<?php
/**
 * Unit tests for Edeco_Model_Address class
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
    define('PHPUnit_MAIN_METHOD', 'Edeco_Model_AddressTest::main');
}

require_once realpath(dirname(__FILE__) . '/../bootstrap.php');

/**
 * Unit tests for Edeco_Model_Address class
 *
 * @author     LNJ <lemuel.nonoal@mandragora-web-systems.com>
 * @version    SVN: $Id$
 * @copyright  Mandrágora Web-Based Systems 2010
 * @category   Tests
 * @package    Edeco
 * @subpackage Test
 */
class Edeco_Model_AddressTest extends ControllerTestCase
{
    /**
     * @var Edeco_Model_Address
     */
    private $address;

    /**
     * Executes all the available tests cases
     *
     * @return void
     */
    public static function main()
    {
        $suite = new PHPUnit_Framework_TestSuite('Edeco_Model_AddressTest');
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
    }

    public function testCanConvertToString()
    {
        $values = array(
	        'streetAndNumber' => 'priv tabacos',
	        'neighborhood' => null, 'zipCode' => 72120,
	        'city' => 'puebla', 'state' => 'puebla', 'country' => 'México'
        );
        $address = new Edeco_Model_Address($values);
        $this->assertEquals(implode('|', $values), (string)$address);
    }

    public function testCanConvertToHtml()
    {
        $data = array(
	        'streetAndNumber' => 'priv tabacos',
	        'neighborhood' => null, 'zipCode' => 72120,
	        'city' => 'puebla', 'state' => 'puebla', 'country' => 'México'
        );
        $address = new Edeco_Model_Address($data);
        $this->assertRegExp(
            '/.*[<br \/>].*[<br \/>].*[,]\s.*[<br \/>].*/', $address->toHtml()
        );
    }

    public function testCanGeocode()
    {
    	$data = array(
	        'streetAndNumber' => 'priv tabacos',
	        'neighborhood' => null, 'zipCode' => 72120,
	        'city' => 'puebla', 'state' => 'puebla', 'country' => 'México'
        );
        //Inject action helper dependency
        $helper = new Edeco_Controller_Action_Helper_GoogleMaps();
        $helper->direct($this->getRequest());
        $address = new Edeco_Model_Address($data);
        $dataAddress = $address->geocode();
        $this->assertTrue(is_array($dataAddress));
        if(count($dataAddress) > 0){
        	$this->assertTrue(
        		$dataAddress[0] instanceof  Mandragora_Geocoder_PlaceMark
        	);
        }

    }

}

if (PHPUnit_MAIN_METHOD == 'Edeco_Model_AddressTest::main') {
    Edeco_Model_AddressTest::main();
}