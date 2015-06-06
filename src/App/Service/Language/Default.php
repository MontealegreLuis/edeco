<?php
class      App_Service_Language_Default
implements Mandragora_Service_Language_Interface
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