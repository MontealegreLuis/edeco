<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use App\Model\City as CityModel;
use Mandragora\Model\AbstractModel;
use Mandragora\Service\Crud\Doctrine\DoctrineCrud;

/**
 * Service class for City model
 */
class City extends DoctrineCrud
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
     * @param string $stateName
     * @return array
     */
    public function retrieveAllByStateId($stateId)
    {
        $this->init();
        return $this->getGateway()->findAllByStateId((int)$stateId);
    }

    public function getModel(array $values = null): AbstractModel
    {
        if (!$this->model) {
            $this->model = new CityModel($values);
        }

        return $this->model;
    }
}
