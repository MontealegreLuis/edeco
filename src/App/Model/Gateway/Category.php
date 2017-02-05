<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Gateway;

use Mandragora\Gateway\Doctrine\AbstractDoctrine;
use Doctrine_Core;
use Mandragora\Gateway\NoResultsFoundException;

/**
 * Gateway for Category model objects
 */
class Category extends AbstractDoctrine
{
    /**
     * @return Doctrine_Query
     */
    public function getQueryFindAll()
    {
        $query = $this->dao->getTable()->createQuery();
        $query->from($this->alias());
        return $query;
    }

    /**
     * @return array
     * @throws \Mandragora\Gateway\NoResultsFoundException
     * @throws Mandragora_Doctrine_Gateway_NoResultsFoundException
     */
    public function findOneById($id)
    {
        $query = $this->dao->getTable()->createQuery();
        $query->from($this->alias())
              ->where('c.id = :id');
        $category = $query->fetchOne(
            [':id' => (int)$id], Doctrine_Core::HYDRATE_ARRAY
        );
        if (!$category) {
            throw new NoResultsFoundException(
                "Category with id '$id' cannot be found"
            );
        }
        return $category;
    }

    /**
     * @return array
     * @throws \Mandragora\Gateway\NoResultsFoundException
     * @throws Mandragora_Doctrine_Gateway_NoResultsFoundException
     */
    public function findOneByUrl($url)
    {
        $query = $this->dao->getTable()->createQuery();
        $query->from($this->alias())->where('c.url = :url');

        $category = $query->fetchOne([':url' => (string)$url], Doctrine_Core::HYDRATE_ARRAY);
        if (!$category) {
            throw new NoResultsFoundException("Category with url '$url' cannot be found");
        }

        return $category;
    }
}
