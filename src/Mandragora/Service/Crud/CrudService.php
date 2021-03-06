<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Service\Crud;

use Mandragora\Service\AbstractService;
use Mandragora\Gateway;
use Mandragora\Gateway\Decorator\CacheAbstract;

/**
 * Base class for services which perform CRUD operations
 */
abstract class CrudService extends AbstractService
{
    /** @var \Mandragora\Gateway\GatewayInterface */
    private $gateway;

    /** @return \Mandragora\Gateway\GatewayInterface */
    public function getGateway()
    {
        if (!$this->gateway) {
            $this->gateway = Gateway::factory($this->modelName);
        }
        return $this->gateway;
    }

    /**
     * @param   \Mandragora\Gateway\GatewayInterface
     *        | Mandragora_Gateway_Decorator_CacheAbstract $gateway
     * @return void
     */
    public function setGateway($gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * @param string $decoratorName = null
     * 		Use this value if you need to decorate a different gateway for this
     * 		service
     * @return void
     */
    public function decorateGateway($decoratorName = null)
    {
        $gateway = $this->getGateway();
        if (!($gateway instanceof CacheAbstract)) {
            if ($decoratorName) {
                $decoratorName = 'App\Model\Gateway\Cache\\' . $decoratorName;
            } else {
                $decoratorName = 'App\Model\Gateway\Cache\\' . $this->modelName;
            }
            $cacheGateway = new $decoratorName($gateway);
            $cacheGateway->setCache($this->getCache('gateway'));
            $this->setGateway($cacheGateway);
        }
    }

    /**
     * @param int $pageNumber
     * @return Zend_Paginator
     */
    abstract public function getPaginator($pageNumber);

    /**
     * @return void
     */
    abstract protected function createPaginator();

    /**
     * @array $options
     */
    abstract public function setPaginatorOptions(array $options);

    /**
     * Customize the form details before the action of creating a model
     *
     * @param string $action
     * @return Zend_Form
     */
    public function getFormForCreating($action) {}

    /**
     * Customize the form details before the action of editing a model
     *
     * @param string $action
     * @return Zend_Form
     */
    public function getFormForEditing($action) {}
}
