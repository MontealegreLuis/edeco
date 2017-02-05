<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Gateway;

use Mandragora\Gateway\Doctrine\AbstractDoctrine;

/**
 * Gateway for authorization resource model objects
 */
class Resource extends AbstractDoctrine
{
    /**
     * @return array
     */
    public function findAll()
    {
        $query = $this->dao->getTable()->createQuery();
        $query->from($this->alias());
        $resources = $query->fetchArray();
        return $resources;
    }
}
