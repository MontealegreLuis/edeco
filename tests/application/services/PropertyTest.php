<?php
/**
 * Unit tests for Edeco_Service_Property class
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
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Edeco_Service_PropertyTest::main');
}

require_once realpath(dirname(__FILE__) . '/../bootstrap.php');

/**
 * Unit tests for Edeco_Service_Property class
 *
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @version    SVN: $Id$
 * @copyright  Mandrágora Web-Based Systems 2010
 * @category   Tests
 * @package    Edeco
 * @subpackage Test
 */

class Edeco_Service_PropertyTest
    extends ControllerTestCase
    implements Mandragora_PHPUnit_DoctrineTest_Interface
{
    /**
     * @var Edeco_Service_Property
     */
    protected $property;

    /**
     * @var Dica_Model_Gateway_Property
     */
    protected $propertyGateway;

    /**
     * @var array
     */
    protected $propertyInformation;

    /**
     * Executes all the available tests cases
     *
     * @return void
     */
    public static function main()
    {
        $suite = new PHPUnit_Framework_TestSuite('Edeco_Service_PropertyTest');
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
        $this->propertyGateway = new Edeco_Model_Gateway_Property(
            new Edeco_Model_Dao_Property()
        );
        $this->property = new Edeco_Service_Property(
            new Edeco_Model_Property(),
            $this->propertyGateway
        );
    }

    public function testCanRetrievePropertyByUrl()
    {
        $property = $this->createProperty();
        $this->propertyGateway->insert($property);
        // Setters are used in service, call'em to test equality
        $property->address = $this->propertyInformation['address'];
        $propertyFound = $this->property->retrievePropertyByUrl(
            $property->url
        );
        $propertyFound->latitude =
            (float)$this->propertyInformation['latitude'];
        $propertyFound->longitude =
            (float)$this->propertyInformation['longitude'];
        //Remove fields that aren't in query's projection
        $propertyInformation = $property->toArray();
        $propertyInformation['url'] = null;
        $propertyInformation['category'] = null;
        $propertyInformation['totalSurface'] = null;
        $propertyInformation['metersOffered'] = null;
        $propertyInformation['metersFront'] = null;
        $propertyInformation['landUse'] = null;
        $propertyInformation['creationDate'] = null;
        $propertyInformation['showOnWeb'] = null;
        $propertyInformation['active'] = null;
        $this->assertEquals($propertyInformation, $propertyFound->toArray());
    }

    /**
     * @return Edeco_Model_Property
     */
    protected function createProperty()
    {
        $this->propertyInformation = array(
            'id' => null, 'name' => 'Local Plaza Dorada',
            'url' => 'local-plaza-dorada', 'description' => 'Local amplio',
            'price' => '5000 al mes',
            'address' =>
                'Priv tabacos,Ignacio Romero V,72120,Puebla,Tepexi,México',
            'addressReference' => 'Junto a Suburbia',
            'latitude' => 100.1, 'longitude' => 120.2, 'category' => 'premises',
            'totalSurface' => 12.5, 'metersOffered' => 16.5,
            'metersFront' => 20.5, 'landUse' => 'commercial',
            'creationDate' => '2010-01-01', 'active' => 1,
        );
        $property = new Edeco_Model_Property($this->propertyInformation);
        return $property;
    }

}

if (PHPUnit_MAIN_METHOD == 'Edeco_Service_PropertyTest::main') {
    Edeco_Service_PropertyTest::main();
}