<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Mandragora\Application\Resource;

use Zend_Application_Resource_ResourceAbstract;
use Mandragora\Application\Manager;

/**
 * Application's settings resource
 */
class Settings extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * @var Mandragora_Application_Manager
     */
    protected $applicationManager;

    /**
     * @return void
     */
    public function init()
    {
        return $this->getApplicationManager();
    }

    /**
     * @return Mandragora_Application_Manager
     */
    public function getApplicationManager()
    {
        if (!$this->applicationManager) {
            $this->applicationManager = new Manager(
                $this->getOptions()
            );
        }
        return $this->applicationManager;
    }
}
