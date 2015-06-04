<?php
/**
 * Contains all tests written for the services of this application
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
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Edeco_Service_AllTests::main');
}

require_once realpath(dirname(__FILE__) . '/../bootstrap.php');
require_once realpath(dirname(__FILE__) . '/AddressTest.php');
require_once realpath(dirname(__FILE__) . '/PropertyTest.php');

/**
 * Contains all tests written for the services of this application
 *
 * @category   Tests
 * @package    Edeco
 * @subpackage Test
 * @author     LNJ <lemuel.nonoal@mandragora-web-systems.com>
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class Edeco_Service_AllTests
{
    /**
     * Runs all the available tests for the current application
     *
     * @return void
     */
    public static function main()
    {
        $listener = new Mandragora_PHPUnit_Listener();
        PHPUnit_TextUI_TestRunner::run(
            self::suite(), array('listeners' => array($listener))
        );
    }

    /**
     * Retrieves all the tests written for this application
     *
     * @return void
     */
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Edeco Services');
        $suite->addTestSuite('Edeco_Service_AddressTest');
        $suite->addTestSuite('Edeco_Service_PropertyTest');
        return $suite;
    }

}

if (PHPUnit_MAIN_METHOD == 'Edeco_Service_AllTests::main') {
    Edeco_Service_AllTests::main();
}