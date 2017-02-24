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
    /** @var Cache */
    protected static $cache;

    private function __construct() {}

    public static function useConfiguration(Cache $cache = null)
    {
        self::$cache = $cache;
        return new self();
    }

    public function create(string $name, string $model): SecureForm
    {
        $className = $this->getClassName($name, $model);
        $filter = new CamelCaseToDash();
        $folder = strtolower($filter->filter($model));
        $file = strtolower($filter->filter($name));

        if (self::$cache === null) {
            return $this->fromIniFile($folder, $file, $className);
        }

        return $this->fromCache($className, $folder, $file);
    }

    /**
     * @return void
     */
    public function setCache(Cache $cache)
    {
        self::$cache = $cache;
    }

    protected function getCache(): Cache
    {
        return self::$cache;
    }

    protected function getClassName(string $formName, string $model): string
    {
        return sprintf('App\Form\%s\%s', $model, $formName);
    }

    private function fromCache(string $className, string $folder, string $file): SecureForm
    {
        $cacheTag = str_replace('\\', '_', $className);
        $form = $this->getCache()->load($cacheTag);
        if (!$form) {
            $form = $this->fromIniFile($folder, $file, $className);
            $this->getCache()->save($form, $cacheTag);
            return $form;
        }
        return $form;
    }

    /**
     * @throws \Zend_Config_Exception
     */
    private function fromIniFile(
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
