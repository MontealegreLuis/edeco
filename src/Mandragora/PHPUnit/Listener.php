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
     * It truncates the database tables before each test
     *
     * @return void
     * @throws \Doctrine_Manager_Exception
     */
    public function startTest(PHPUnit_Framework_Test $test)
    {
        if ($test instanceof DoctrineTestInterface) {
            $connection = Manager::connection();
            $handler = $connection->getDbh();
            $handler->query(sprintf('SET FOREIGN_KEY_CHECKS = 0;'));
            /** @var string[] $tables */
            $tables = $connection->import->listTables();
            foreach ($tables as $table) {
                $sql = sprintf('TRUNCATE TABLE %s', $table);
                $handler->query($sql);
            }
            $handler->query(sprintf('SET FOREIGN_KEY_CHECKS = 1;'));
        }
    }

    /**
     * It bootstraps Doctrine, recreates both the database and the tables. It
     * will only run for the `database` suite.
     *
     * @throws \Zend_Application_Exception
     * @throws \Doctrine_Manager_Exception
     * @throws \Zend_Application_Bootstrap_Exception
     */
    public function startTestSuite(PHPUnit_Framework_TestSuite $suite)
    {
        if ('integration' === $suite->getName()) {
            $configFilePath = APPLICATION_PATH . '/configs/application.ini';
            $application = new Application(APPLICATION_ENV, $configFilePath);
            $bootstrap = $application->getBootstrap()->bootstrap('doctrine');
            $bootstrap->getResource('doctrine')->setup();
            Manager::connection();
            Core::dropDatabases();
            Core::createDatabases();
            Core::createTablesFromModels();
        }
    }

    /**
     * Do nothing here
     */
    public function endTest(PHPUnit_Framework_Test $test, $time) { }

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
