<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Service;

use Mandragora\Form\SecureForm;
use Mandragora\Model;
use Mandragora\Model\AbstractModel;
use Mandragora\FormFactory;
use Zend_Cache_Manager as CacheManager;

/**
 * Abstract class for service objects
 */
abstract class AbstractService
{
    /** @var string */
    protected $modelName;

    /** @var AbstractModel */
    protected $model;

    /** @var \Mandragora\Form\SecureForm */
    private $form;

    /** @var \Zend_Cache_Manager */
    protected $cacheManager;

    public function __construct(string $modelName)
    {
        $this->modelName = $modelName;
    }

    /**
     * Method to be overridden by developer to customize the service object
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

    public function getForm(string $formName = 'Detail', bool $disableCache = false): SecureForm
    {
        if (!$this->form) {
            if (!$disableCache) {
                $formFactory = FormFactory::useConfiguration($this->getCache('form'));
            } else {
                $formFactory = FormFactory::useConfiguration();
            }
            $this->form = $formFactory->create($formName, $this->modelName);
        }
        return $this->form;
    }

    /**
     * @return void
     */
    public function setCacheManager(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    /**
     * @return \Zend_Cache_Core
     */
    protected function getCache(string $cacheName)
    {
        return $this->cacheManager->getCache($cacheName);
    }
}
