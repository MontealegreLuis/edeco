<?php
/**
 * Unit tests for Edeco_Model_PremiseInformation class
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
    define('PHPUnit_MAIN_METHOD', 'Edeco_Model_PremiseInformationTest::main');
}

require_once realpath(dirname(__FILE__) . '/../bootstrap.php');

/**
 * Unit tests for Edeco_Model_PremiseInformation class
 *
 * @category   Tests
 * @package    Edeco
 * @subpackage Test
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class Edeco_Model_PremiseInformationTest extends ControllerTestCase
{
    /**
     * Executes all the available tests cases
     *
     * @return void
     */
    public static function main()
    {
        $suite = new PHPUnit_Framework_TestSuite(
            'Edeco_Model_PremiseInformationTest'
        );
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
            'name' => 'Premise test', 'telephone' => '(111) 1-11-11-11',
            'emailAddress' => 'test@test.com', 'zone' => 'Test zone',
            'minPrice' => 3000, 'maxPrice' => 10000,
            'surface' => 20, 'characteristics' => 'Some nice ones'
        );
        $premiseInformation = new Edeco_Model_PremiseInformation($values);
        $this->assertEquals('Premise test', (string)$premiseInformation);
    }

    public function testCanAccesProperties()
    {
        $values = array(
            'name' => 'Premise test', 'telephone' => '(111) 1-11-11-11',
            'emailAddress' => 'test@test.com', 'zone' => 'Test zone',
            'minPrice' => '3000', 'maxPrice' => '10000',
            'surface' => 20, 'characteristics' => 'Some nice ones'
        );
        $premiseInformation = new Edeco_Model_PremiseInformation($values);
        $this->assertEquals('Premise test', $premiseInformation->name);
        $this->assertEquals('(111) 1-11-11-11', $premiseInformation->telephone);
        $this->assertEquals('test@test.com', $premiseInformation->emailAddress);
        $this->assertEquals('Test zone', $premiseInformation->zone);
        $this->assertEquals('3000', (string)$premiseInformation->minPrice);
        $this->assertEquals('10000', (string)$premiseInformation->maxPrice);
        $this->assertEquals(20, $premiseInformation->surface);
        $this->assertEquals(
            'Some nice ones', $premiseInformation->characteristics
        );
    }

}

if (PHPUnit_MAIN_METHOD == 'Edeco_Model_PremiseInformationTest::main') {
    Edeco_Model_PremiseInformationTest::main();
}