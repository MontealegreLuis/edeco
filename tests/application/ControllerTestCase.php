<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

/**
 * Base class used to perform unit testing on Zend applications
 */
abstract class ControllerTestCase extends Zend_Test_PHPUnit_ControllerTestCase
{
    /**
     * @var Zend_Application The application to be tested
     */
    protected $application;

    /**
     * Bootstraps the application before running the tests
     */
    public function setUp()
    {
        $this->bootstrap = [$this, 'applicationBootstrap'];
        parent::setUp();
    }

    /**
     * Initializes the application using the testing environment
     *
     * @throws \Zend_Application_Exception
     */
    public function applicationBootstrap()
    {
        $this->application = new Zend_Application(
            APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini'
        );
        $this->application->bootstrap();
        //Add this to boostrap correctly the app
        $bootstrap = $this->application->getBootstrap();
        $front = $bootstrap->getResource('FrontController');
        $front->setParam('bootstrap', $bootstrap);
    }
}
