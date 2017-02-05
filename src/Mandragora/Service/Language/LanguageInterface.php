<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Service\Language;

use Zend_Controller_Request_Abstract;
use Zend_Locale;
use Zend_Cache_Core;

interface LanguageInterface
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