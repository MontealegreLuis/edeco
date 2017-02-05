<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Mandragora\Controller\Plugin;

use Zend_Controller_Plugin_Abstract;
use Zend_Controller_Request_Abstract;
use Zend_Controller_Front;
use Mandragora\Service\Language;

/**
 * Plugin to select appropriate language
 */
class LanguageSelector extends Zend_Controller_Plugin_Abstract
{
    /**
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $fc = Zend_Controller_Front::getInstance();
        $locale = $fc->getParam('bootstrap')
                     ->getResource('locale');
        $namespace = $fc->getParam('bootstrap')
                        ->getResource('settings')
                        ->getSetting('namespace');
        $cache = $fc->getParam('bootstrap')
                    ->getResource('cachemanager')
                    ->getCache('default');
        $languageSelector = Language::factory('DefaultLanguage');
        $languageSelector->setLanguage($request, $locale, $cache);
    }
}
