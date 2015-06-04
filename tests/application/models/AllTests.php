<?php
/**
 * Contains all tests written for the models of Edeco application
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
    define('PHPUnit_MAIN_METHOD', 'Edeco_Model_AllTests::main');
}

require_once realpath(dirname(__FILE__) . '/../bootstrap.php');
require_once realpath(dirname(__FILE__) . '/AddressTest.php');
require_once realpath(dirname(__FILE__) . '/CityTest.php');
require_once realpath(dirname(__FILE__) . '/ContactTest.php');
require_once realpath(dirname(__FILE__) . '/PictureTest.php');
require_once realpath(dirname(__FILE__) . '/PremiseInformationTest.php');
require_once realpath(dirname(__FILE__) . '/ProjectTest.php');
require_once realpath(dirname(__FILE__) . '/PropertyTest.php');
require_once realpath(dirname(__FILE__) . '/StateTest.php');
require_once realpath(dirname(__FILE__) . '/UserTest.php');
require_once realpath(dirname(__FILE__) . '/Gateway/AllTests.php');

/**
 * Contains all tests written for the models of Edeco application
 *
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @version    SVN: $Id$
 * @copyright  Mandrágora Web-Based Systems 2010
 * @category   Tests
 * @package    Edeco
 * @subpackage Test
 */
class Edeco_Model_AllTests
{
    /**
     * Runs all the available tests for the current application
     *
     * @return void
     */
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(
            self::suite(),
            array('listeners' => array(new Mandragora_PHPUnit_Listener()))
        );
    }

    /**
     * Retrieves all the tests written for this application
     *
     * @return void
     */
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Edeco Models');
        //$suite->addTestSuite(Edeco_Model_Gateway_AllTests::suite());
        $suite->addTestSuite('Edeco_Model_AddressTest');
        $suite->addTestSuite('Edeco_Model_CityTest');
        $suite->addTestSuite('Edeco_Model_ContactTest');
        $suite->addTestSuite('Edeco_Model_PictureTest');
        $suite->addTestSuite('Edeco_Model_PremiseInformationTest');
        $suite->addTestSuite('Edeco_Model_ProjectTest');
        $suite->addTestSuite('Edeco_Model_PropertyTest');
        $suite->addTestSuite('Edeco_Model_StateTest');
        $suite->addTestSuite('Edeco_Model_UserTest');
        return $suite;
    }

}

if (PHPUnit_MAIN_METHOD == 'Edeco_Model_AllTests::main') {
    Edeco_Model_AllTests::main();
}