<?php
class App_Service_State extends Mandragora_Service_Crud_Doctrine_Abstract
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