<?php
/**
 * Contains all tests written for Edeco application
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
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @version    SVN: $Id$
 * @copyright  Mandrágora Web-Based Systems 2010
 * @category   Tests
 * @package    Edeco
 * @subpackage Test
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Edeco_AllTests::main');
}

require_once realpath(dirname(__FILE__) . '/bootstrap.php');
require_once realpath(dirname(__FILE__) . '/models/AllTests.php');
require_once realpath(dirname(__FILE__) . '/services/AllTests.php');

/**
 * Contains all tests written for Edeco application
 *
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @version    SVN: $Id$
 * @copyright  Mandrágora Web-Based Systems 2010
 * @category   Tests
 * @package    Edeco
 * @subpackage Test
 */
class Edeco_AllTests
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
        $suite = new PHPUnit_Framework_TestSuite('Edeco');
        $suite->addTestSuite(Edeco_Model_AllTests::suite());
        $suite->addTestSuite(Edeco_Service_AllTests::suite());
        return $suite;
    }

}

if (PHPUnit_MAIN_METHOD == 'Edeco_AllTests::main') {
    Edeco_AllTests::main();
}