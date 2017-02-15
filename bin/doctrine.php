<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Doctrine_Cli as Cli;
use Zend_Application as Application;

require __DIR__ . '/../vendor/autoload.php';

defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(__DIR__ . '/../application'));
defined('APPLICATION_ENV') || define('APPLICATION_ENV', 'doctrineCLI');

$configFilePath = APPLICATION_PATH . '/configs/application.ini';
$application = new Application(APPLICATION_ENV, $configFilePath);
$doctrineManager = $application
    ->getBootstrap()
    ->getPluginResource('doctrine')
    ->getDoctrineManager()
;
$doctrineManager->setup();
$cli = new Cli($doctrineManager->getConfiguration());
$cli->run($_SERVER['argv']);
