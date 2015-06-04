<?php
interface Mandragora_Service_Acl_Interface
{
    /**
     * @return void
     */
    public function createAcl();

    /**
     * @param array $options
     * @return void
     */
    public function setOptions(array $options);

    /**
     * @param $request
     * @return void
     */
    public function execute(Zend_Controller_Request_Abstract $request);

    /**
     * @return boolean
     */
    public function isNotAuthenticated();

    /**
     * @return boolean
     */
    public function isNotAuthorized();

}