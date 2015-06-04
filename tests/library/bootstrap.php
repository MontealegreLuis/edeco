<?php
/**
 * Bootstrap file for the unit tests of the current application's library(ies)
 *
 * @category   Application
 * @package    Edeco
 * @subpackage Tests
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/*
 * Load PHPUnit classes
 */
require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/Framework/IncompleteTestError.php';
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/Runner/Version.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'PHPUnit/Util/Filter.php';

/*
 * Initializes the application for testing
 */
$rootDir = realpath(dirname(__FILE__) . '/../../');
defined('ROOT_PATH') || define('ROOT_PATH', $rootDir);
$applicationDir = $rootDir . '/application';
$libraryDir = $rootDir . '/library';

defined('APPLICATION_ENV') || define('APPLICATION_ENV', 'testing');
defined('APPLICATION_PATH') || define('APPLICATION_PATH', $applicationDir);

$paths = array($libraryDir, $applicationDir, get_include_path());

set_include_path(implode(PATH_SEPARATOR, $paths));

require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->setFallbackAutoloader(true);

require_once 'ControllerTestCase.php';