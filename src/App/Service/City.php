<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use Mandragora\Service\Crud\Doctrine\AbstractDoctrine;

/**
 * Service class for City model
 */
class City extends AbstractDoctrine
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

    /**
     * @see Mandragora_Service_Crud_Abstract::getFormForCreating()
     */
    public function getFormForCreating($action) {}

    /**
     * @see Mandragora_Service_Crud_Abstract::getFormForEditing()
     */
    public function getFormForEditing($action) {}
}
