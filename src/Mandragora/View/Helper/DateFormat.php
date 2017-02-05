<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\View\Helper;

use Zend_Controller_Front;
use Zend_Locale;
use Zend_Registry;
use Zend_Date;

/**
 * Helper for displaying the current date
 */
class DateFormat
{
    /**
     * @var string
     */
    protected $locale;

    public function __construct()
    {
        $fc = Zend_Controller_Front::getInstance();
        $cache = $fc->getParam('bootstrap')
                    ->getResource('cachemanager')
                    ->getCache('default');
        Zend_Locale::setCache($cache);
        $this->locale = Zend_Registry::get('Zend_Locale');
    }

    /**
     * @return Mandragora_View_Helper_DateFormat
     */
    public function dateFormat()
    {
        return $this;
    }

    /**
     * @return string
     */
    public function year()
    {
        $currentDate = new Zend_Date(null, null, $this->locale);
        return $currentDate->toString(Zend_Date::YEAR);
    }

    /**
     * @return string
     */
    public function full()
    {
        $currentDate = new Zend_Date(null, null, $this->locale);
        return $currentDate->toString(Zend_Date::DATE_FULL);
    }
}
