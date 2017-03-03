<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Application\Doctrine;

use Zend_Loader_Autoloader as Autoloader;
use Doctrine_Manager as DoctrineManager;
use Doctrine_Core as Core;

/**
 * Handles Doctrine connections and it's configurations options
 */
class Manager
{
    /** @var array */
    protected $options;

    /** @var \Doctrine_Connection */
    protected static $connection;

    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * @return void
     * @throws \Doctrine_Exception
     */
    public function setup()
    {
        if (!$this->isConnectionOpen()) {
            $this->openConnection();
        }
    }

    /**
     * @return boolean
     */
    public function isConnectionOpen()
    {
        return null !== self::$connection;
    }

    /**
     * @return array
     */
    public function getConfiguration()
    {
        return $this->options;
    }

    private function openConnection(): void
    {
        $loader = Autoloader::getInstance();
        $loader->pushAutoloader([Core::class, 'autoload'])
            ->pushAutoloader([Core::class, 'modelsAutoload']);
        $manager = DoctrineManager::getInstance();
        $manager->setAttribute(Core::ATTR_AUTO_ACCESSOR_OVERRIDE, true);
        $manager->setAttribute(Core::ATTR_MODEL_LOADING,
            $this->options['model_autoloading']);
        Core::loadModels($this->options['models_path']);
        self::$connection = DoctrineManager::connection($this->options['dsn'],
            'doctrine');
        self::$connection->setAttribute(Core::ATTR_USE_NATIVE_ENUM, true);
        self::$connection->setCharset('UTF8');
    }
}
