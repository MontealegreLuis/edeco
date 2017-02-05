<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\PHPUnit;

use PHPUnit_Framework_TestListener;
use PHPUnit_Framework_Test;
use Mandragora\PHPUnit\DoctrineTest\DoctrineTestInterface;
use Zend_Application;
use Doctrine_Manager;
use Doctrine_Core;
use PHPUnit_Framework_TestSuite;
use Exception;
use PHPUnit_Framework_AssertionFailedError;

/**
 * PHPUnit Listener for Doctrine's tests
 */
class Listener implements PHPUnit_Framework_TestListener
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
        if($test instanceof DoctrineTestInterface) {

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
        if($test instanceof DoctrineTestInterface) {

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
