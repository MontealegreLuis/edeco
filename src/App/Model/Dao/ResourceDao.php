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
Doctrine_Manager::getInstance()->bindComponent(ResourceDao::class, 'doctrine');

/**
 * @property string $name
 */
class ResourceDao extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('resource');
        $this->hasColumn('name', 'string', 25, array(
             'type' => 'string',
             'length' => 25,
             'fixed' => false,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => false,
             ));
    }
}
