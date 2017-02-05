<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Gateway;

use Mandragora\Gateway\Doctrine\AbstractDoctrine;

/**
 * Gateway for authorization role model objects
 */
class Role extends AbstractDoctrine
{
    /**
     * @return array
     */
    public function findAll()
    {
        $query = $this->dao->getTable()->createQuery();
        $query->from($this->alias())->orderBy('parentRole');
        $roles = $query->fetchArray();
        return $roles;
    }

}