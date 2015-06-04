<?php
/**
 * Unit tests for Edeco_Model_Property class
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
    define('PHPUnit_MAIN_METHOD', 'Edeco_Model_PropertyTest::main');
}

require_once realpath(dirname(__FILE__) . '/../bootstrap.php');

/**
 * Unit tests for Edeco_Model_Address class
 *
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @version    SVN: $Id$
 * @copyright  Mandrágora Web-Based Systems 2010
 * @category   Tests
 * @package    Edeco
 * @subpackage Test
 */

class Edeco_Model_PropertyTest
    extends ControllerTestCase
{
    /**
     * @var Edeco_Model_Property
     */
    protected $property;

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
        $suite = new PHPUnit_Framework_TestSuite('Edeco_Model_PropertyTest');
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
        $this->property = new Edeco_Model_Property();
    }

    public function testCanCreateAddressModelFromString()
    {
        $address = '13 sur 10932|Eclipse||Puebla|Puebla|México';
        $this->property->address = $address;
        $this->assertTrue(
            $this->property->address instanceof Edeco_Model_Address,
            '$this->address should be an instance of Edeco_Model_Address'
        );
        $this->assertEquals(
            '13 sur 10932', $this->property->address->streetAndNumber
        );
        $this->assertEquals('Eclipse', $this->property->address->neighborhood);
        $this->assertEquals('', $this->property->address->zipCode);
        $this->assertEquals('Puebla', $this->property->address->state);
        $this->assertEquals('Puebla', $this->property->address->city);
        $this->assertEquals('México', $this->property->address->country);
    }

    public function testCanAccessNewProperties()
    {
        $this->property = $this->createProperty();
        $this->assertEquals(
            $this->property->totalSurface,
            $this->propertyInformation['totalSurface']
        );
        $this->assertEquals(
            $this->property->metersOffered,
            $this->propertyInformation['metersOffered']
        );
        $this->assertEquals(
            $this->property->metersFront,
            $this->propertyInformation['metersFront']
        );
        $this->assertEquals(
            $this->property->landUse,
            $this->propertyInformation['landUse']
        );
        $this->assertEquals(
            $this->property->creationDate,
            $this->propertyInformation['creationDate']
        );
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
            'address' => '13 sur 10932|Eclipse||Puebla|Puebla|México',
            'addressReference' => 'Junto a Suburbia',
            'latitude' => 100.1, 'longitude' => 120.2, 'category' => 'premises',
            'totalSurface' => 12.5, 'metersOffered' => 16.5,
            'metersFront' => 20.5, 'landUse' => 'commercial',
            'creationDate' => '2010-07-07', 'active' => 1,
            'Picture' => array()
        );
        return new Edeco_Model_Property($this->propertyInformation);
    }

}
if (PHPUnit_MAIN_METHOD == 'Edeco_Model_PropertyTest::main') {
    Edeco_Model_PropertyTest::main();
}