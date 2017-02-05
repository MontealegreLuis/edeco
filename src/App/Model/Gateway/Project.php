<?php
/**
 * Gateway for project model objects
 *
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace App\Model\Gateway;

use Mandragora\Gateway\Doctrine\AbstractDoctrine;
use Doctrine_Core;
use Mandragora\Gateway\NoResultsFoundException;

/**
 * Gateway for project model objects
 */
class Project extends AbstractDoctrine
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
     * @param int $id
     * @return array
     * @throws Mandragora_Doctrine_Gateway_NoResultsFoundException
     */
    public function findOneById($id)
    {
        $query = $this->dao->getTable()->createQuery();
        $query->from($this->alias())
              ->where('p.id = :id');
        $project = $query->fetchOne(
            array(':id' => (int)$id), Doctrine_Core::HYDRATE_ARRAY
        );
        if (!$project) {
            throw new NoResultsFoundException(
                "The project with $id cannot be found"
            );
        }
        return $project;
    }

    /**
     * @param string $projectName
     * @return array
     */
    public function findOneByName($projectName)
    {
        $query = $this->dao->getTable()->createQuery();
        $query->from($this->alias())
              ->where('p.name = :name');
        $project = $query->fetchOne(
            array(':name' => (string)$projectName,),
            Doctrine_Core::HYDRATE_ARRAY
        );
        if (!$project) {
            throw new NoResultsFoundException(
                "Project $projectName not found"
            );
        }
        return $project;
    }
}