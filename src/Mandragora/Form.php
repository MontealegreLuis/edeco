<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora;

use Zend_Cache_Core;
use Zend_Filter_Word_CamelCaseToDash;
use Zend_Config_Ini;

/**
 * Factory for forms
 */
class Form
{
    /**
     * Flag to determine if cache should be disabled
     *
     * @var boolean
     */
    protected $disableCache = false;

    /**
     * Flag to determine if form should be created from a config file
     *
     * @var boolean
     */
    protected $fromConfig;

    /**
     * @var Zend_Cache_Core
     */
    protected static $cache;

    /**
     * @param boolean $disableCache
     * @param boolean $fromConfig
     */
    public function __construct($disableCache, $fromConfig)
    {
        $this->disableCache = $disableCache;
        $this->fromConfig = $fromConfig;
    }

    /**
     * @param Zend_Cache_Core $cache
     * @return void
     */
    public function setCache(Zend_Cache_Core $cache)
    {
        self::$cache = $cache;
    }

    /**
     * @param string $name
     * @param string $model
     * @return Mandragora_Form_Abstract
     */
    public function factory($name, $model)
    {
        if ($this->fromConfig) {
            return $this->fromConfig($name, $model);
        } else {
            return $this->fromClass($name, $model);
        }
    }

    /**
     * @param string $name
     * @param string $model
     * @return Mandragora_Form_Abstract
     */
    protected function fromClass($name, $model)
    {
        $className = $this->getClassName($name, $model);
        return new $className();
    }

    /**
     * @param string $name
     * @param string $model
     * @return Mandragora_Form_Abstract
     */
    protected function fromConfig($name, $model)
    {
        return $this->createFormFromConfig($name, $model);
    }

    /**
     * @param string $name
     * @param string $model
     */
    protected function createFormFromConfig($name, $model)
    {
        $className = $this->getClassName($name, $model);
        $filter = new Zend_Filter_Word_CamelCaseToDash();
        $folder = strtolower($filter->filter($model));
        $file = strtolower($filter->filter($name));
        if ($this->disableCache) {
            $form = $this->_loadFormFromIniFile($folder, $file, $className);
        } else  {
            $cacheTag = str_replace('\\', '_', $className);
            $form = $this->getCache()->load($cacheTag);
            if (!$form) {
                $form = $this->_loadFormFromIniFile($folder, $file, $className);
                $this->getCache()->save($form, $cacheTag);
            }
        }
        return $form;
    }

    /**
     * @return Zend_Cache_Core
     */
    protected function getCache()
    {
        return self::$cache;
    }

    /**
     * @param string $formName
     * @param string $model
     * @return string
     */
    protected function getClassName($formName, $model)
    {
        return sprintf('App\Form\%s\%s', $model, $formName);
    }

    /**
     * @param string $folder
     * @param string $fileName
     * @param string $className
     * @return Mandragora_Form_Abstract
     */
    private function _loadFormFromIniFile($folder, $fileName, $className)
    {
        $pathToIniFile = APPLICATION_PATH . '/configs/forms/%s/%s.ini';
        $configPath = sprintf($pathToIniFile, $folder, $fileName);
        $config = new Zend_Config_Ini($configPath, sprintf('%s', $folder));
        return new $className($config);
    }
}
