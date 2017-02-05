<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Service\Crud\Doctrine;

use Mandragora\Service\Crud\AbstractCrud;
use Mandragora\Paginator\Adapter\DoctrineQuery;
use Zend_Paginator;
use Doctrine_Query;
use Mandragora\Application\Doctrine\Manager;

/**
 * Base class for services which perform CRUD operations using Doctrine ORM
 */
abstract class DoctrineCrud extends AbstractCrud
{
    /**
     * @var array
     */
    protected $defaults = ['query' => null, 'page' => 1];

    /**
     * @var Zend_Paginator
     */
    protected $paginator;

    /**
     * @var array
     */
    protected $paginatorOptions;

    /**
     * @var Doctrine_Query
     */
    protected $query;

    /**
     * @var Mandragora_Application_Doctrine_Manager
     */
    protected $doctrineManager;

    /**
     * @return void
     */
    public function openConnection()
    {
        if (!$this->doctrineManager->isConnectionOpen()) {
            $this->doctrineManager->setup();
        }
    }

    /**
     * @param int $page
     * @return Zend_Paginator
     */
    public function getPaginator($page)
    {
        if (!$this->paginator) {
            $this->createPaginator();
        }
        $this->paginator->setCurrentPageNumber((int)$page);
        return $this->paginator;
    }

    /**
     * @param array $options
     * @return void
     */
    protected function createPaginator()
    {
        $adapter = new DoctrineQuery($this->query);
        $this->paginator = new Zend_Paginator($adapter);
        $itemsPerPage = (int)$this->paginatorOptions['itemCountPerPage'];
        $this->paginator->setItemCountPerPage($itemsPerPage);
        $pageRange = (int)$this->paginatorOptions['pageRange'];
        $this->paginator->setPageRange($pageRange);
        Zend_Paginator::setCache($this->getCache('paginator'));
    }

    /**
     * @param Doctrine_Query $query
     * @return void
     */
    public function setPaginatorQuery(Doctrine_Query $query)
    {
        $this->query = $query;
    }

    /**
     * @param array $options
     * @return void
     */
    public function setPaginatorOptions(array $options)
    {
        if (is_array($this->paginatorOptions)) {
            $options = array_merge($this->paginatorOptions, $options);
        } else {
            $options = array_merge($this->defaults, $options);
        }
        $this->paginatorOptions = $options;
    }

    /**
     * @param Mandragora_Application_Doctrine_Manager $doctrineManager
     */
    public function setDoctrineManager(
        Manager $doctrineManager
    )
    {
        $this->doctrineManager = $doctrineManager;
    }
}
