<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Service\Acl;

use Zend_Controller_Request_Abstract;

interface AclInterface
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
