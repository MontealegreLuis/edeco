<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Application\Doctrine;

use Zend_Loader_Autoloader;
use Doctrine_Manager;
use Doctrine_Core;

/**
 * Handles Doctrine connections and it's configurations options
 */
class Manager
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var Doctrine_Connection
     */
    protected static $connection;

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * @return void
     */
    public function setup()
    {
        if (!$this->isConnectionOpen()) {
            $loader = Zend_Loader_Autoloader::getInstance();
            $loader->pushAutoloader(array('Doctrine_Core', 'autoload'))
                   ->pushAutoloader(array('Doctrine_Core', 'modelsAutoload'));
            $manager = Doctrine_Manager::getInstance();
            $manager->setAttribute(
                Doctrine_Core::ATTR_AUTO_ACCESSOR_OVERRIDE, true
            );
            $manager->setAttribute(
                Doctrine_Core::ATTR_MODEL_LOADING,
                $this->options['model_autoloading']
            );
            Doctrine_Core::loadModels($this->options['models_path']);
            self::$connection = Doctrine_Manager::connection(
                $this->options['dsn'], 'doctrine'
            );
            self::$connection->setAttribute(
                Doctrine_Core::ATTR_USE_NATIVE_ENUM, true
            );
            self::$connection->setCharset('UTF8');
        }
    }

    /**
     * @return boolean
     */
    public function isConnectionOpen()
    {
        return !is_null(self::$connection);
    }

    /**
     * @return array
     */
    public function getConfiguration()
    {
        return $this->options;
    }
}
