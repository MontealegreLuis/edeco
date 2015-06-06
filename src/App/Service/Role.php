<?php
class App_Service_Role extends Mandragora_Service_Crud_Doctrine_Abstract
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

    /**
     * @see Mandragora_Service_Crud_Abstract::getFormForCreating()
     */
    public function getFormForCreating($action) {}
    
    /**
     * @see Mandragora_Service_Crud_Abstract::getFormForEditing()
     */
    public function getFormForEditing($action) {}
    
}