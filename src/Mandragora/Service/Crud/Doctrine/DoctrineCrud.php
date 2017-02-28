<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Service\Crud\Doctrine;

use Doctrine_Query as Query;
use Mandragora\Application\Doctrine\Manager;
use Mandragora\Paginator\Adapter\DoctrineQuery;
use Mandragora\Service\Crud\CrudService;
use Zend_Paginator as Paginator;

/**
 * Base class for services which perform CRUD operations using Doctrine ORM
 */
abstract class DoctrineCrud extends CrudService
{
    /** @var array */
    protected $defaults = ['query' => null, 'page' => 1];

    /** @var Paginator */
    protected $paginator;

    /** @var array */
    protected $paginatorOptions;

    /** @var Doctrine_Query */
    protected $query;

    /** @var Manager */
    protected $doctrineManager;

    /**
     * @return void
     * @throws \Doctrine_Exception
     */
    public function openConnection()
    {
        if (!$this->doctrineManager->isConnectionOpen()) {
            $this->doctrineManager->setup();
        }
    }

    /**
     * @param int $page
     * @return Paginator
     */
    public function getPaginator($page)
    {
        if (!$this->paginator) {
            $this->createPaginator();
        }
        $this->paginator->setCurrentPageNumber((int) $page);
        return $this->paginator;
    }

    /**
     * @param array $options
     * @return void
     */
    protected function createPaginator()
    {
        $adapter = new DoctrineQuery($this->query);
        $this->paginator = new Paginator($adapter);
        $itemsPerPage = (int) $this->paginatorOptions['itemCountPerPage'];
        $this->paginator->setItemCountPerPage($itemsPerPage);
        $pageRange = (int) $this->paginatorOptions['pageRange'];
        $this->paginator->setPageRange($pageRange);
        Paginator::setCache($this->getCache('paginator'));
    }

    /**
     * @param Query $query
     * @return void
     */
    public function setPaginatorQuery(Query $query)
    {
        $this->query = $query;
    }

    /**
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

    public function setDoctrineManager(Manager $doctrineManager)
    {
        $this->doctrineManager = $doctrineManager;
    }
}
