<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright  Mandrágora Web-Based Systems 2010-2015 (http://www.mandragora-web-systems.com)
 */

/**
 * Bootstrap file for Edeco's web solution
 *
 * @author     LMV <luis.montealegre@mandragora-web.systems.com>
 */
require_once '../vendor/autoload.php';
Mandragora_ErrorToException::register();
define('ROUTES', 'admin');
defined('PUBLIC_PATH') || define('PUBLIC_PATH', dirname(__FILE__));
defined('ROOT_PATH') || define('ROOT_PATH', realpath(PUBLIC_PATH . '/../'));
$applicationPath = realpath(dirname(__FILE__) . '/../application');
defined('APPLICATION_PATH') || define('APPLICATION_PATH', $applicationPath);
$applicationEnvironment = getenv('APPLICATION_ENV')
    ? getenv('APPLICATION_ENV')
    : 'development';
defined('APPLICATION_ENV') || define('APPLICATION_ENV', $applicationEnvironment);

$configFilePath = APPLICATION_PATH . '/configs/application.ini';
$edeco = new Zend_Application(APPLICATION_ENV, $configFilePath);
Zend_Controller_Front::getInstance()->registerPlugin(
    new Zend_Controller_Plugin_ErrorHandler(
        array('module' => 'admin', 'controller' => 'error', 'action' => 'error')
    )
);
$edeco->bootstrap()->run();
