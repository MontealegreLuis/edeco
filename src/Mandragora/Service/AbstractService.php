<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Service;

use Mandragora\Model;
use Mandragora\Model\AbstractModel;
use Mandragora\FormFactory;
use Zend_Cache_Manager;

/**
 * Abstract class for service objects
 */
abstract class AbstractService
{
    /** @var string */
    protected $modelName;

    /** @var AbstractModel */
    private $model;

    /**
     * @var Mandragora_Form_Abstract
     */
    private $form;

    /**
     * @var Zend_Cache_Manager
     */
    protected $cacheManager;

    /**
     * @param string $modelName
     */
    public function __construct($modelName)
    {
        $this->modelName = (string) $modelName;
    }

    /**
     * Method to be overriden by developer to customize the service object
     *
     * @return void
     */
    protected function init() {}

    /**
     * @param array $values = null
     * @return AbstractModel
     */
    public function getModel(array $values = null)
    {
        if (!$this->model) {
            $this->model = Model::factory($this->modelName, $values);
        }
        return $this->model;
    }

    /**
     * @param AbstractModel $model
     * @return void
     */
    public function setModel(AbstractModel $model)
    {
        $this->model = $model;
    }

    /**
     * @param string $formName = 'Detail'
     * @param boolean $fromConfig = true
     * @param boolean $disableCache = false
     * @return Mandragora_Form_Abstract
     */
    public function getForm(
        $formName = 'Detail', $fromConfig = true, $disableCache = false
    )
    {
        if (!$this->form) {
            $formFactory = new FormFactory($disableCache, $fromConfig);
            if (!$disableCache) {
                 $formFactory->setCache($this->getCache('form'));
            }
            $this->form = $formFactory->create($formName, $this->modelName);
        }
        return $this->form;
    }

    /**
     * @param Zend_Cache_Manager $cacheManager
     * @return void
     */
    public function setCacheManager(Zend_Cache_Manager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    /**
     * @param string $cacheName
     * @return Zend_Cache_Core
     */
    protected function getCache($cacheName)
    {
        return $this->cacheManager->getCache($cacheName);
    }
}
