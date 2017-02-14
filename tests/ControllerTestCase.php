<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Mandragora\Controller\Plugin\Router;
use Zend_Application as Application;

/**
 * Base class used to perform unit testing on Zend applications
 */
abstract class ControllerTestCase extends Zend_Test_PHPUnit_ControllerTestCase
{
    /** @var Application */
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
        $this->application = new Application(
            APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini'
        );
        $this->application->bootstrap();
        $bootstrap = $this->application->getBootstrap();

        /** @var \Zend_Controller_Front $front */
        $this->_frontController = $bootstrap->getResource('FrontController');
        $this->_frontController->setParam('bootstrap', $bootstrap);

        $this
            ->_frontController
            ->getPlugin(Router::class)
            ->routeStartup($this->getRequest())
        ;
    }
}
