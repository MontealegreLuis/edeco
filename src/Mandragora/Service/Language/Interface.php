<?php
interface Mandragora_Service_Language_Interface
{
    /**
     * @param Zend_Controller_Request_Abstract $request
     * @param Zend_Locale $locale
     * @param Zend_Cache_Core $cache
     * @return void
     */
    public function setLanguage(
        Zend_Controller_Request_Abstract $request,
        Zend_Locale $locale,
        Zend_Cache_Core $cache
    );
}