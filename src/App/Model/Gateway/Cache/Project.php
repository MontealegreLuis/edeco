<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Gateway\Cache;

use Mandragora\Gateway\Decorator\CacheAbstract;
use Mandragora\Model\AbstractModel;

class Project extends CacheAbstract
{
    /**
     * @return array
     */
    public function findAll()
    {
        $project = $this->getCache()->load('project');
        if (!$project) {
            $project = $this->gateway->findAll();
            $this->getCache()->save($project, 'project');
        }
        return $project;
    }

    /**
     * @return array
     * @throws Mandragora_Doctrine_Gateway_NoResultsFoundException
     */
    public function findOneById($id)
    {
        $cacheId = 'project' . (int)$id;
        $project = $this->getCache()->load($cacheId);
        if (!$project) {
            $project = $this->gateway->findOneById((int)$id);
            $this->getCache()->save($project, $cacheId);
        }
        return $project;
    }

    /**
     * @return array
     * @throws Mandragora_Doctrine_Gateway_NoResultsFoundException
     */
    public function findOneByName($projectName)
    {
        $cacheId = 'project' . (int)$projectName;
        $project = $this->getCache()->load($cacheId);
        if (!$project) {
            $project = $this->gateway->findOneByName((int)$projectName);
            $this->getCache()->save($project, $cacheId);
        }
        return $project;
    }

    /**
     * @param Mandragora_Model_Abstract $project
     * @return void
     */
    public function delete(AbstractModel $project)
    {
        $this->gateway->delete($project);
    }


    /**
     * @param Mandragora_Model_Abstract $project
     * @return void
     */
    public function insert(AbstractModel $project)
    {
        $this->gateway->insert($project);
        $cacheId = 'project' . $project->id;
        $this->getCache()->save($project->toArray(true), $cacheId);
    }


    /**
     * @param Mandragora_Model_Abstract $project
     * @return void
     */
    public function update(AbstractModel $project)
    {
        $this->gateway->update($project);
        $cacheId = 'project' . $project->id;
        $this->getCache()->save($project->toArray(true), $cacheId);
    }
}
