<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
require __DIR__ . '/../vendor/autoload.php';

Mandragora\ErrorToException::register();
define('ROUTES', 'default');

defined('PUBLIC_PATH') || define('PUBLIC_PATH', __DIR__);
$applicationPath = realpath(__DIR__ . '/../application');
defined('APPLICATION_PATH') || define('APPLICATION_PATH', $applicationPath);
$applicationEnvironment = getenv('APPLICATION_ENV')
    ? getenv('APPLICATION_ENV')
    : 'development';
defined('APPLICATION_ENV') || define('APPLICATION_ENV', $applicationEnvironment);

$configFilePath = APPLICATION_PATH . '/configs/application.ini';
$edeco = new Zend_Application(APPLICATION_ENV, $configFilePath);
$edeco->bootstrap()->run();
