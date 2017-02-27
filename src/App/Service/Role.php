<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use Mandragora\Model\AbstractModel;
use Mandragora\Service\Crud\Doctrine\DoctrineCrud;

class Role extends DoctrineCrud
{
    /**
     * @return void
     */
    public function init()
    {
        $this->openConnection();
        $this->decorateGateway();
    }

    /**
     * @return array
     */
    public function retrieveAllRoles()
    {
        $this->init();
        return $this->getGateway()->findAll();
    }

    public function getModel(array $values = null): ?AbstractModel
    {
        return null;
    }
}
