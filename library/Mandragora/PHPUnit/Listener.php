<?php
/**
 * PHPUnit Listener for Doctrine's tests
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
 * @category   Library Tests
 * @package    Mandragora
 * @subpackage PHPUnit
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * PHPUnit Listener for Doctrine's tests
 *
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @version    SVN: $Id$
 * @copyright  Mandrágora Web-Based Systems 2010
 * @category   Library Tests
 * @package    Mandragora
 * @subpackage PHPUnit
 * @history    may 11, 2010
 *             LMV
 *             - Interface creation
 */
class Mandragora_PHPUnit_Listener implements PHPUnit_Framework_TestListener
{
    /**
     * This method runs before each test.
     *
     * It bootsraps Doctrine, recreates both the database and the tables. It
     * first checks if the current test is Doctrine test, i. e. checks if the
     * tests implements the marker interface
     * Mandragora_PHPUnit_DoctrineTest_Interface
     *
     * @return void
     */
    public function startTest(PHPUnit_Framework_Test $test)
    {
        if($test instanceof Mandragora_PHPUnit_DoctrineTest_Interface) {

            $configFilePath = APPLICATION_PATH . '/configs/application.ini';
            $application = new Zend_Application(
                APPLICATION_ENV, $configFilePath
            );
            $application->getBootstrap()->bootstrap('doctrine');
            Doctrine_Manager::connection();
            Doctrine_Core::dropDatabases();
            Doctrine_Core::createDatabases();
            Doctrine_Core::createTablesFromModels();
        }
    }

    /**
     * This method runs after each tests
     *
     * It checks if the test is a doctrine test, if true it closes the doctrine
     * connection
     *
     * @return void
     */
    public function endTest(PHPUnit_Framework_Test $test, $time)
    {
        if($test instanceof Mandragora_PHPUnit_DoctrineTest_Interface) {

            Doctrine_Manager::getInstance()->closeConnection(
                Doctrine_Manager::connection()
            );
        }
    }

    /**
     * Do nothing here
     */
    public function startTestSuite(PHPUnit_Framework_TestSuite $suite) { }

    /**
     * Do nothing here
     */
    public function endTestSuite(PHPUnit_Framework_TestSuite $suite) { }

    /**
     * Do nothing here
     */
    public function addError(
        PHPUnit_Framework_Test $test, Exception $e, $time
    ) { }

    /**
     * Do nothing here
     */
    public function addFailure(
        PHPUnit_Framework_Test $test,
        PHPUnit_Framework_AssertionFailedError $e, $time
    ) { }

    /**
     * Do nothing here
     */
    public function addIncompleteTest(
        PHPUnit_Framework_Test $test, Exception $e, $time
    ) { }

    /**
     * Do nothing here
     */
    public function addSkippedTest(
        PHPUnit_Framework_Test $test, Exception $e, $time
    ) { }

}