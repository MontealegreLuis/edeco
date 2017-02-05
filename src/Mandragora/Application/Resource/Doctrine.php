<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Application\Resource;

use Zend_Application_Resource_ResourceAbstract;
use Mandragora\Application\Doctrine\Manager;

/**
 * Doctrine's settings resource
 */
class Doctrine extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * @var Mandragora_Application_Doctrine_Manager
     */
    protected $doctrineManager;

    /**
     * @return void
     */
    public function init()
    {
        return $this->getDoctrineManager();
    }

    /**
     * @return Mandragora_Application_Doctrine_Manager
     */
    public function getDoctrineManager()
    {
        if (!$this->doctrineManager) {
            $this->doctrineManager =
               new Manager($this->getOptions());
        }
        return $this->doctrineManager;
    }
}
