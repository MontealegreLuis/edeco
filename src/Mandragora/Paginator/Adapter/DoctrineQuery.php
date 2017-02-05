<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Paginator\Adapter;

use Zend_Paginator_Adapter_Interface;
use Doctrine_Query;

/**
 * Implements a Zend paginator interface using a Doctrine query object
 */
class DoctrineQuery implements Zend_Paginator_Adapter_Interface
{
    /** @var Doctrine_Query */
    protected $query;

    /** @var int */
    protected $rowCount;

    public function __construct(Doctrine_Query $query)
    {
        $this->query = $query;
    }

    /**
     * @param int $offset
     * @param int $itemsPerPage
     * @return array
     */
    public function getItems($offset, $itemsPerPage)
    {
        return $this
            ->query
            ->limit((int) $itemsPerPage)
            ->offset((int) $offset)
            ->fetchArray()
        ;
    }

    /**
     * @return int
     */
    public function count()
    {
        if ($this->rowCount === null) {
            $this->rowCount = $this->query->count();
        }
        return $this->rowCount;
    }
}
