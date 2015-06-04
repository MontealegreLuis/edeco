<?php
/**
 * Bootstrap file for Edeco's web solution
 *
 * PHP version 5
 *
 * LICENSE: Redistribution and use of this file in source and binary forms,
 * with or without modification, is not permitted under any circumstance
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   Application
 * @package    Edeco
 * @subpackage Bootstrap
 * @author     LMV <luis.montealegre@mandragora-web.systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN $Id$
 */

/**
 * Bootstrap file for Edeco's web solution
 *
 * @author     LMV <luis.montealegre@mandragora-web.systems.com>
 * @version    SVN $Id$
 * @copyright  Mandrágora Web-Based Systems 2010
 * @category   Application
 * @package    Edeco
 * @subpackage Bootstrap
 */
//require_once '../library/Mandragora/ErrorToException.php';
//Mandragora_ErrorToException::register();
define('ROUTES', 'admin');
$publicPath = dirname(__FILE__);
defined('PUBLIC_PATH') || define('PUBLIC_PATH', $publicPath);
defined('ROOT_PATH') || define('ROOT_PATH', realpath(PUBLIC_PATH . '/../'));
$applicationPath = realpath(dirname(__FILE__) . '/../application');
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', $applicationPath);
$applicationEnvironment = getenv('APPLICATION_ENV')
    ? getenv('APPLICATION_ENV')
    : 'production';
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', $applicationEnvironment);
set_include_path(
    implode(
        PATH_SEPARATOR,
        array(
            realpath(APPLICATION_PATH . '/../library'),
            get_include_path(),
        )
    )
);

require_once 'Zend/Application.php';
$configFilePath = APPLICATION_PATH . '/configs/application.ini';
$edeco = new Zend_Application(APPLICATION_ENV, $configFilePath);
Zend_Controller_Front::getInstance()->registerPlugin(
    new Zend_Controller_Plugin_ErrorHandler(
        array('module' => 'admin', 'controller' => 'error', 'action' => 'error')
    )
);
$edeco->bootstrap()->run();