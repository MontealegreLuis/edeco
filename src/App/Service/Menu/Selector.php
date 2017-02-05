<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service\Menu;

use Mandragora\Service\Controller\Plugin\AbstractPlugin;
use Mandragora\Service\Menu\MenuInterface;
use Zend_Controller_Request_Abstract;
use Zend_Layout;
use Zend_Auth;
use Zend_Config_Xml;
use Zend_Navigation;
use Mandragora\Acl\Wrapper;
use RecursiveIteratorIterator;

/**
 * Service that will setup the main navigation menu
 */
class Selector extends AbstractPlugin implements MenuInterface
{
    /**
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function selectMenu(Zend_Controller_Request_Abstract $request)
    {
        if ($request->getParam('error_handler')){
            return; // No menu is needed when an exception has been thrown
        }
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
    protected function setAccessKeyValues(Zend_Navigation $container)
    {
        $it = new RecursiveIteratorIterator($container, RecursiveIteratorIterator::SELF_FIRST);
        $i = 1;
        foreach ($it as $page) {
            $page->accesskey = (string) $i;
            $i++;
        }
    }
}
