<?php
/**
 * Unit tests for Edeco_Geocoder_Adapter class
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
    define('PHPUnit_MAIN_METHOD', 'Mandragora_Geocoder_AdapterTest::main');
}

require_once realpath(dirname(__FILE__) . '/../../bootstrap.php');

/**
 * Unit tests for Edeco_Geocoder_Adapter class
 *
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @version    SVN: $Id$
 * @copyright  Mandrágora Web-Based Systems 2010
 * @category   Tests
 * @package    Edeco
 * @subpackage Test
 */
class Mandragora_Geocoder_AdapterTest extends ControllerTestCase
{
    /*
     * @var Edeco_Geocoder_Adapter
     */
    protected $adapter;

    /**
     * Executes all the available tests cases
     *
     * @return void
     */
    public static function main()
    {
        $suite = new PHPUnit_Framework_TestSuite('Mandragora_Geocoder_AdapterTest');
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
        $this->adapter = new Mandragora_Geocoder_Adapter('');
    }

    public function testCanLookupAddresses()
    {
        $address =
       'PASEO TABASCO 1407, TABASCO 2000, 86030, TABASCO, VILLAHERMOSA, MEXICO';
        $placeMarkers = $this->adapter->lookup($address);
        $this->assertTrue(is_array($placeMarkers));
        $this->assertTrue(count($placeMarkers) > 0);
        $this->assertTrue(
            $placeMarkers[0] instanceof Mandragora_Geocoder_PlaceMark
        );
        //Zend_Debug::dump($placeMarkers);
    }

}

if (PHPUnit_MAIN_METHOD == 'Mandragora_Geocoder_AdapterTest::main') {
    Mandragora_Geocoder_AdapterTest::main();
}