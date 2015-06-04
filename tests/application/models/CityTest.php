<?php
/**
 * Unit tests for Edeco_Model_City class
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
    define('PHPUnit_MAIN_METHOD', 'Edeco_Model_CityTest::main');
}

require_once realpath(dirname(__FILE__) . '/../bootstrap.php');

/**
 * Unit tests for Edeco_Model_Address class
 *
 * @category   Tests
 * @package    Edeco
 * @subpackage Test
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class Edeco_Model_CityTest extends ControllerTestCase
{
    /**
     * Executes all the available tests cases
     *
     * @return void
     */
    public static function main()
    {
        $suite = new PHPUnit_Framework_TestSuite('Edeco_Model_CityTest');
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
            'id' => 1, 'name' => 'Puebla', 'stateId' => 1
        );
        $city = new Edeco_Model_City($values);
        $this->assertEquals('Puebla', (string)$city);
    }

    public function testCanAccesProperties()
    {
        $values = array(
            'id' => 1, 'name' => 'Puebla', 'stateId' => 1
        );
        $city = new Edeco_Model_City($values);
        $this->assertEquals(1, $city->id);
        $this->assertEquals('Puebla', $city->name);
        $this->assertEquals(1, $city->stateId);
    }

}

if (PHPUnit_MAIN_METHOD == 'Edeco_Model_CityTest::main') {
    Edeco_Model_CityTest::main();
}