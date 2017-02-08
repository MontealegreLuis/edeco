<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Application\Resource;

use Zend_Application_Resource_ResourceAbstract as Resource;
use Mandragora\Application\Doctrine\Manager;

/**
 * Doctrine's settings resource
 */
class Doctrine extends Resource
{
    /** @var Manager */
    protected $doctrineManager;

    /**
     * @return Manager
     */
    public function init()
    {
        return $this->getDoctrineManager();
    }

    /**
     * @return Manager
     */
    public function getDoctrineManager()
    {
        if (!$this->doctrineManager) {
            $this->doctrineManager = new Manager($this->getOptions());
        }
        return $this->doctrineManager;
    }
}
