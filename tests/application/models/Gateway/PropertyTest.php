<?php
/**
 * Unit tests for Edeco_Model_Gateway_Property class
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
    define('PHPUnit_MAIN_METHOD', 'Edeco_Model_Gateway_PropertyTest::main');
}

require_once realpath(dirname(__FILE__) . '/../../bootstrap.php');

/**
 * Unit tests for Edeco_Model_Gateway_Property class
 *
 * @category   Tests
 * @package    Edeco
 * @subpackage Test
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class Edeco_Model_Gateway_PropertyTest
    extends ControllerTestCase
    implements Mandragora_PHPUnit_DoctrineTest_Interface
{
    /**
     * Executes all the available tests cases
     *
     * @return void
     */
    public static function main()
    {
        $suite = new PHPUnit_Framework_TestSuite(
            'Edeco_Model_Gateway_PropertyTest'
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
    }

    public function testCanCreateProperty()
    {
        $property = $this->createProperty();
        $gatewayProperty = new Edeco_Model_Gateway_Property(
            new Edeco_Model_Dao_Property()
        );
        $gatewayProperty->insert($property);
        $propertyTable = Doctrine_Core::getTable('Edeco_Model_Dao_Property');
        $this->assertEquals(
            $property->toArray(),
            $propertyTable->findOneById($property->id)->toArray()
        );
    }

    public function testCanFindPropertyById()
    {
        $property = $this->createProperty();
        $gatewayProperty = new Edeco_Model_Gateway_Property(
            new Edeco_Model_Dao_Property()
        );
        $gatewayProperty->insert($property);
        $foundProperty = $gatewayProperty->findOneById($property->id);
        // Correct some stuff, Doctrine always return strings
        $foundProperty['latitude'] = (float)$foundProperty['latitude'];
        $foundProperty['longitude'] = (float)$foundProperty['longitude'];
        $foundProperty['Picture'] = array();
        $this->assertEquals($property->toArray(), $foundProperty);
    }

    /**
     * @expectedException Mandragora_Doctrine_Gateway_NoResultsFoundException
     */
    public function testFindNonExistentPropertyByIdThrowsException()
    {
        $gatewayProperty = new Edeco_Model_Gateway_Property(
            new Edeco_Model_Dao_Property()
        );
        $foundProperty = $gatewayProperty->findOneById(-1);
    }

    public function testCanFindAllProperties()
    {
        // Insert some properties
        $createdProperties = array();
        for ($i = 0; $i < 3; $i++) {

            $gatewayProperty = new Edeco_Model_Gateway_Property(
                new Edeco_Model_Dao_Property()
            );
            $createdProperties[$i] = $this->createProperty();
            $gatewayProperty->insert($createdProperties[$i]);
        }

        // Find the properties recently inserted
        $gatewayProperty = new Edeco_Model_Gateway_Property(
            new Edeco_Model_Dao_Property()
        );
        $allProperties = $gatewayProperty->findAll();
        $this->assertEquals(3, count($allProperties));

        // Check that the properties found were the same that were inserted
        for ($i = 0; $i < 3; $i++) {

            $this->assertEquals(
                $createdProperties[$i]->toArray(), $allProperties[$i]
            );
        }
    }

    public function testFindAllPropertiesRetrievesZeroElementsWhenTableIsEmpty()
    {
        $gatewayProperty = new Edeco_Model_Gateway_Property(
            new Edeco_Model_Dao_Property()
        );
        $allProperties = $gatewayProperty->findAll();
        $this->assertEquals(0, count($allProperties));
    }

    public function testFindAddressByIdMustRetunString()
    {
    	$property = $this->createProperty();
    	$gatewayProperty = new Edeco_Model_Gateway_Property(
            new Edeco_Model_Dao_Property()
        );
        $gatewayProperty->insert($property);
        $address =  $gatewayProperty->findAddressByPropertyId(1);
        $this->assertEquals(
            'Priv tabacos,Ignacio Romero V,72120,Puebla,Tepexi,México', $address
        );
    }

    public function testUpdateAddressById()
    {
    	$property = $this->createProperty();
        $gatewayProperty = new Edeco_Model_Gateway_Property(
            new Edeco_Model_Dao_Property()
        );
        $gatewayProperty->insert($property);
        $address = "violetas 59,la vista, 1,1,72100,lv";
        $gatewayProperty = new Edeco_Model_Gateway_Property(
            new Edeco_Model_Dao_Property()
        );
        $gatewayProperty->updateAddressByPropertyId($address, 1);
    }

    /**
     * @return Edeco_Model_Property
     */
    protected function createProperty()
    {
        $propertyInformation = array(
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
            'Picture' => null
        );
        $property = new Edeco_Model_Property($propertyInformation);
        return $property;
    }

}

if (PHPUnit_MAIN_METHOD == 'Edeco_Model_Gateway_PropertyTest::main') {
    Edeco_Model_Gateway_PropertyTest::main();
}