<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Mandragora\ErrorToException;
use Zend_Application as Application;
use Zend_Controller_Front as FrontController;
use Zend_Controller_Plugin_ErrorHandler as ErrorHandler;

require_once __DIR__ . '/../vendor/autoload.php';

ErrorToException::register();
define('ROUTES', 'admin');
defined('PUBLIC_PATH') || define('PUBLIC_PATH', __DIR__);
defined('ROOT_PATH') || define('ROOT_PATH', realpath(PUBLIC_PATH . '/../'));
$applicationPath = realpath(__DIR__ . '/../application');
defined('APPLICATION_PATH') || define('APPLICATION_PATH', $applicationPath);
$applicationEnvironment = getenv('APPLICATION_ENV') ?: 'development';
defined('APPLICATION_ENV') || define('APPLICATION_ENV', $applicationEnvironment);

$configFilePath = APPLICATION_PATH . '/configs/application.ini';
$edeco = new Application(APPLICATION_ENV, $configFilePath);
FrontController::getInstance()->registerPlugin(new ErrorHandler([
    'module' => 'admin', 'controller' => 'error', 'action' => 'error'
]));
$edeco->bootstrap()->run();
