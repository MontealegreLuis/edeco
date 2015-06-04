<?php
/**
 * Select the menu according to the module
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
 * @category   Library
 * @package    Mandragora
 * @subpackage Controller
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Select the menu according to the module
 *
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @version    SVN: $Id$
 * @copyright  Mandrágora Web-Based Systems 2010
 * @category   Library
 * @package    Mandragora
 * @subpackage Controller
 */
class Mandragora_Controller_Plugin_MenuPicker
    extends Zend_Controller_Plugin_Abstract
{
    /**
     * Select the menu according to the module
     *
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $layout = Zend_Layout::getMvcInstance();
        $view = $layout->getView();
        $isUserAthenticated = Zend_Auth::getInstance()->hasIdentity();
        $moduleName = $request->getModuleName();
        if ($isUserAthenticated
            && ($moduleName === 'admin' || $moduleName === 'help')) {
            $configFile = 'admin.xml';
        } else if ($moduleName === 'default') {
            $configFile = 'default.xml';
        } else {
            $configFile = false;
        }
        if ($configFile) {
            $config = new Zend_Config_Xml(
                APPLICATION_PATH . '/configs/navigation/' . $configFile, 'nav'
            );
            $container = new Zend_Navigation($config);
            if ($moduleName == 'default') {
                $this->setAccessKeyValues($container);
            }
            $view->navigation($container);
            if ($isUserAthenticated) {
                $role = Zend_Auth::getInstance()->getIdentity()->roleName;
                $view->navigation()
                     ->setAcl(Mandragora_Acl_Wrapper::getInstance())
                     ->setRole($role);
            }
        }
    }

    /**
     * @param Zend_Navigation $container
     * @return void
     */
    public function setAccessKeyValues(Zend_Navigation $container)
    {
        $it = new RecursiveIteratorIterator(
            $container, RecursiveIteratorIterator::SELF_FIRST
        );
        $view = Zend_Layout::getMvcInstance()->getView();
        $i = 1;
        foreach ($it as $page) {
            $page->accesskey = $i;
            //$params = $page->getParams();
            //$url = $view->translate($params['page']);
            //$params['page'] = $url;
            //$page->setParams($params);
            $i++;
        }
    }

}