<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

/* Initialize the application for testing */
define('ROUTES', 'admin');
$rootDir = realpath(__DIR__ . '/../');
defined('ROOT_PATH') || define('ROOT_PATH', $rootDir);
$applicationDir = $rootDir . '/application';
$libraryDir = $rootDir . '/library';

defined('APPLICATION_ENV') || define('APPLICATION_ENV', 'testing');
defined('APPLICATION_PATH') || define('APPLICATION_PATH', $applicationDir);

$paths = [$libraryDir, $applicationDir, get_include_path()];

set_include_path(implode(PATH_SEPARATOR, $paths));

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/ControllerTestCase.php';