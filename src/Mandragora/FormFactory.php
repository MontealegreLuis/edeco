<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora;

use Mandragora\Form\SecureForm;
use stdClass;
use Zend_Cache_Core as Cache;
use Zend_Filter_Word_CamelCaseToDash as CamelCaseToDash;
use Zend_Config_Ini as IniConfig;
use Zend_Form as Form;

/**
 * Factory for forms
 */
class FormFactory
{
    /** @var Cache */
    protected static $cache;

    private function __construct() {}

    public static function useConfiguration(Cache $cache = null): FormFactory
    {
        self::$cache = $cache;
        return new FormFactory();
    }

    public function configure(Form $form): void
    {
        $form->setConfig(
            $this->getConfiguration($this->getIniFileConfiguration(get_class($this)))
        );
    }

    public function create(string $name, string $model): SecureForm
    {
        $className = $this->getClassName($name, $model);
        $configuration = $this->getIniFileConfiguration($className);

        if (self::$cache === null) {
            return $this->fromIniFile($configuration, $className);
        }

        return $this->fromCache($configuration, $className);
    }

    public function setCache(Cache $cache): void
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

    private function fromCache(stdClass $configuration, string $className): SecureForm
    {
        $cacheTag = str_replace('\\', '_', $className);
        $form = $this->getCache()->load($cacheTag);
        if (!$form) {
            $form = $this->fromIniFile($configuration, $className);
            $this->getCache()->save($form, $cacheTag);
            return $form;
        }
        return $form;
    }

    /**
     * @throws \Zend_Config_Exception
     */
    private function fromIniFile(stdClass $information, string $className): SecureForm
    {
        return new $className($this->getConfiguration($information));
    }

    /**
     * @throws \Zend_Config_Exception
     */
    private function getConfiguration(stdClass $configuration): IniConfig
    {
        return new IniConfig($configuration->path, $configuration->section);
    }

    private function getIniFileConfiguration($className): stdClass
    {
        $pathToIniFile = APPLICATION_PATH . '/configs/forms/%s/%s.ini';
        $filter = new CamelCaseToDash();
        $classParts = explode('\\', $className);

        $file = strtolower($filter->filter(array_pop($classParts)));
        $folder = strtolower($filter->filter(array_pop($classParts)));

        $configuration = new stdClass();
        $configuration->path = sprintf($pathToIniFile, $folder, $file);
        $configuration->section = $folder;

        return $configuration;
    }
}
