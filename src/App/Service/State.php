<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use Mandragora\Service\Crud\Doctrine\DoctrineCrud;

class State extends DoctrineCrud
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
    public function retrieveAllStates()
    {
        $this->init();
        $states = $this->getGateway()->findAll();
        $options = array();
        foreach ($states as $state) {
            $options[$state['id']] = $state['name'];
        }
        return $options;
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
