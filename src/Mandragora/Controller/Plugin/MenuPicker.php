<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Controller\Plugin;

use Zend_Controller_Plugin_Abstract;
use Zend_Controller_Request_Abstract;
use Zend_Layout;
use Zend_Auth;
use Zend_Config_Xml;
use Zend_Navigation;
use Mandragora\Acl\Wrapper;
use RecursiveIteratorIterator;

/**
 * Select the menu according to the module
 */
class MenuPicker extends Zend_Controller_Plugin_Abstract
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
                     ->setAcl(Wrapper::getInstance())
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
