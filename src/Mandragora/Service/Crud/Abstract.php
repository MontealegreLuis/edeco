<?php
/**
 * Base class for services which perform CRUD operations
 *
 * PHP version 5
 *
 * LICENSE: Redistribution and use of this file in source and binary forms,
 * with or without modification, is not permitted under any circumstance
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   Library
 * @package    Mandragora
 * @subpackage Service_Crud
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Base class for services which perform CRUD operations
 *
 * @category   Library
 * @package    Mandragora
 * @subpackage Service_Crud
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
abstract class Mandragora_Service_Crud_Abstract
extends        Mandragora_Service_Abstract
{
    /**
     * @var Mandragora_Gateway_Interface
     */
    private $gateway;

    /**
     * @var Mandragora_Paginator
     */
    protected $paginator;

    /**
     * @param string $modelName
     */
    public function __construct($modelName)
    {
        parent::__construct($modelName);
    }

    /**
     * @return Mandragora_Gateway_Interface
     */
    public function getGateway()
    {
        if (!$this->gateway) {
            $this->gateway = Mandragora_Gateway::factory($this->modelName);
        }
        return $this->gateway;
    }

    /**
     * @param   Mandragora_Gateway_Interface
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
        if (!($gateway instanceof Mandragora_Gateway_Decorator_CacheAbstract)) {
            if ($decoratorName) {
                $decoratorName = 'App_Model_Gateway_Cache_' . $decoratorName;
            } else {
                $decoratorName = 'App_Model_Gateway_Cache_' . $this->modelName;
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
     * This method let the developer customize the form details before the
     * action of creating a model
     *
     * @param string $action
     * @return Zend_Form
     */
    abstract public function getFormForCreating($action);

    /**
     * This method let the developer customize the form details before the
     * action of editing a model
     *
     * @param string $action
     * @return Zend_Form
     */
    abstract public function getFormForEditing($action);

}