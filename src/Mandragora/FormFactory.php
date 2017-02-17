<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora;

use Mandragora\Form\SecureForm;
use Zend_Cache_Core as Cache;
use Zend_Filter_Word_CamelCaseToDash as CamelCaseToDash;
use Zend_Config_Ini as IniConfig;

/**
 * Factory for forms
 */
class FormFactory
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

    /** @var Cache */
    protected static $cache;

    public function __construct(bool $disableCache, bool $fromConfig)
    {
        $this->disableCache = $disableCache;
        $this->fromConfig = $fromConfig;
    }

    /**
     * @return void
     */
    public function setCache(Cache $cache)
    {
        self::$cache = $cache;
    }

    public function create(string $className, string $model): SecureForm
    {
        if ($this->fromConfig) {
            return $this->fromConfig($className, $model);
        } else {
            return $this->fromClass($className, $model);
        }
    }

    protected function fromClass(string $name, string $model): SecureForm
    {
        $className = $this->getClassName($name, $model);
        return new $className();
    }

    protected function fromConfig(string $name, string $model): SecureForm
    {
        return $this->createFormFromConfig($name, $model);
    }

    protected function createFormFromConfig(
        string $name,
        string $model
    ): SecureForm
    {
        $className = $this->getClassName($name, $model);
        $filter = new CamelCaseToDash();
        $folder = strtolower($filter->filter($model));
        $file = strtolower($filter->filter($name));
        if ($this->disableCache) {
            $form = $this->_loadFormFromIniFile($folder, $file, $className);
        } else {
            $cacheTag = str_replace('\\', '_', $className);
            $form = $this->getCache()->load($cacheTag);
            if (!$form) {
                $form = $this->_loadFormFromIniFile($folder, $file, $className);
                $this->getCache()->save($form, $cacheTag);
            }
        }
        return $form;
    }

    protected function getCache(): Cache
    {
        return self::$cache;
    }

    protected function getClassName(string $formName, string $model): string
    {
        return sprintf('App\Form\%s\%s', $model, $formName);
    }

    /**
     * @throws \Zend_Config_Exception
     */
    private function _loadFormFromIniFile(
        string $folder,
        string $fileName,
        string $className
    ): SecureForm
    {
        $pathToIniFile = APPLICATION_PATH . '/configs/forms/%s/%s.ini';
        $configPath = sprintf($pathToIniFile, $folder, $fileName);
        return new $className(new IniConfig($configPath, sprintf('%s', $folder)));
    }
}
