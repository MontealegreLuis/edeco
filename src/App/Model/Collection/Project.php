<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Collection;

use App\Model\Project as ProjectModel;
use Mandragora\Collection\AbstractCollection;

/**
 * Collection class for Project model
 */
class Project extends AbstractCollection
{
    /**
     * @return ProjectModel
     */
    protected function createModel(array $data)
    {
        return new ProjectModel($data);
    }
}
