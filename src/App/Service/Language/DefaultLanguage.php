<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service\Language;

use Mandragora\Service\Language\LanguageInterface;
use Zend_Controller_Request_Abstract;
use Zend_Locale;
use Zend_Cache_Core;
use Zend_Translate;
use Zend_Registry;
use Zend_Form;
use Zend_Validate_Abstract;


class DefaultLanguage implements LanguageInterface
{
    /**
     * @param Zend_Controller_Request_Abstract $request
     * @param Zend_Locale $locale
     * @param Zend_Cache_Core $cache
     */
    public function setLanguage(
        Zend_Controller_Request_Abstract $request,
        Zend_Locale $locale,
        Zend_Cache_Core $cache
    )
    {
        $language = $locale->getLanguage();
        $region = $locale->getRegion();
        $languagePath = sprintf('%s/%s.csv', $language, $region);
        $translate = new Zend_Translate(
            'csv',
            APPLICATION_PATH . '/configs/languages/'. $languagePath,
            $language
        );
        Zend_Translate::setCache($cache);
        Zend_Registry::set('Zend_Translate', $translate);
        Zend_Form::setDefaultTranslator($translate);
        Zend_Validate_Abstract::setDefaultTranslator($translate);
    }
}
