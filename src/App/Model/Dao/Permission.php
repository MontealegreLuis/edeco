<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Dao;

use Doctrine_Manager;
use Doctrine_Record;

// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent(Permission::class, 'doctrine');

/**
 * @property string $name
 * @property string $roleName
 * @property string $resourceName
 */
class Permission extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('permission');
        $this->hasColumn('name', 'string', 45, array(
             'type' => 'string',
             'length' => 45,
             'fixed' => false,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('roleName', 'string', 15, array(
             'type' => 'string',
             'length' => 15,
             'fixed' => false,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => false,
             ));
        $this->hasColumn('resourceName', 'string', 25, array(
             'type' => 'string',
             'length' => 25,
             'fixed' => false,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => false,
             ));
    }
}
