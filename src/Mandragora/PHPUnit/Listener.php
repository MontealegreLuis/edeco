<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\PHPUnit;

use PHPUnit_Framework_TestListener;
use PHPUnit_Framework_Test;
use Mandragora\PHPUnit\DoctrineTest\DoctrineTestInterface;
use Zend_Application as Application;
use Doctrine_Manager as Manager;
use Doctrine_Core as Core;
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
     * It bootstraps Doctrine, recreates both the database and the tables. It
     * first checks if the current test is Doctrine test, i. e. checks if the
     * tests implements the marker interface
     * \Mandragora\PHPUnit\DoctrineTest\DoctrineTestInterface
     *
     * @return void
     * @throws \Zend_Application_Bootstrap_Exception
     * @throws \Doctrine_Manager_Exception
     * @throws \Zend_Application_Exception
     */
    public function startTest(PHPUnit_Framework_Test $test)
    {
        if ($test instanceof DoctrineTestInterface) {

            $configFilePath = APPLICATION_PATH . '/configs/application.ini';
            $application = new Application(
                APPLICATION_ENV, $configFilePath
            );
            $bootstrap = $application->getBootstrap()->bootstrap('doctrine');
            $doctrine = $bootstrap->getResource('doctrine');
            $doctrine->setup();
            Manager::connection();
            Core::dropDatabases();
            Core::createDatabases();
            Core::createTablesFromModels();
        }
    }

    /**
     * It checks if the test is a doctrine test, if true it closes the doctrine
     * connection
     *
     * @return void
     * @throws \Doctrine_Manager_Exception
     */
    public function endTest(PHPUnit_Framework_Test $test, $time)
    {
        /*if ($test instanceof DoctrineTestInterface) {
            Manager::getInstance()->closeConnection(Manager::connection());
        }*/
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

    /**
     * Do nothing here
     */
    public function addRiskyTest(
        PHPUnit_Framework_Test $test,
        Exception $e,
        $time
    ) { }
}
