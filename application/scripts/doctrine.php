<?php
/**
 * PHP version 5.5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright  Mandrágora Web-Based Systems 2015 (http://www.mandragora-web-systems.com)
 */

/**
 * Bootstrap file for Doctrine's CLI application
 *
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 */
require '../../vendor/autoload.php';

defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../'));
defined('APPLICATION_ENV') || define('APPLICATION_ENV', 'doctrineCLI');

$configFilePath = APPLICATION_PATH . '/configs/application.ini';
$application = new Zend_Application(APPLICATION_ENV, $configFilePath);
$doctrineManager = $application
    ->getBootstrap()
    ->getPluginResource('doctrine')
    ->getDoctrineManager()
;
$doctrineManager->setup();
$cli = new Doctrine_Cli($doctrineManager->getConfiguration());
$cli->run($_SERVER['argv']);
