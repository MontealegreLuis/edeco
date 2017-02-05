<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service\Acl;

use Mandragora\Acl\Wrapper;
use Mandragora\Log;
use Mandragora\Service\Acl\AclInterface;
use Mandragora\Service;
use Zend_Auth;
use Zend_Controller_Request_Abstract;
use Zend_Date;

class Handler implements AclInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var boolean
     */
    protected $notAuthenticated;

    /**
     * @var boolean
     */
    protected $notAuthorized;

    /**
     * @var Zend_Acl
     */
    protected $acl;

    /**
     * @var Zend_Controller_Request_Abstract
     */
    protected $request;

    /**
     * @param array $options
     * @return void
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function execute(Zend_Controller_Request_Abstract $request)
    {
        $this->createAcl();
        $action = $request->getActionName();
        $resource = sprintf(
            '%s_%s',  $request->getModuleName(), $request->getControllerName()
        );
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $identity = Zend_Auth::getInstance()->getIdentity();
            $roleName = $identity->roleName;
            if ($this->acl->has($resource) &&
                !$this->acl->isAllowed($roleName, $resource, $action)){
                $this->notAuthorized = true;
            } else {
                $this->request = $request;
                $this->auditActivity($identity->username);
            }
        } else {
            if ($this->acl->has($resource) && !$this->acl->isAllowed('guest', $resource, $action)) {
                $this->notAuthenticated = true;
            }
        }
    }

    /**
     * @return void
     */
    public function createAcl()
    {
        $cacheManager = $this->options['cacheManager'];
        $doctrineManager = $this->options['doctrineManager'];
        $cache = $cacheManager->getCache('default');
        $acl = $cache->load('acl');
        if (!$acl) {
            $acl = Wrapper::getInstance();
            $rolesService = Service::factory('Role');
            $rolesService->setCacheManager($cacheManager);
            $rolesService->setDoctrineManager($doctrineManager);
            $permissionsService = Service::factory('Permission');
            $permissionsService->setCacheManager($cacheManager);
            $permissionsService->setDoctrineManager($doctrineManager);
            $resourceService = Service::factory('Resource');
            $resourceService->setCacheManager($cacheManager);
            $resourceService->setDoctrineManager($doctrineManager);
            $acl->setRoles($rolesService->retrieveAllRoles());
            $acl->setResources($resourceService->retrieveAllResources());
            $acl->setPermissions($permissionsService->retrieveAllPermissions());
            $cache->save($acl, 'acl');
        }
        Wrapper::setInstance($acl); //Recover acl from cache
        $this->acl = Wrapper::getInstance();
    }

    /**
     * @return boolean
     */
    public function isNotAuthenticated()
    {
        return $this->notAuthenticated;
    }

    /**
     * @return boolean
     */
    public function isNotAuthorized()
    {
        return $this->notAuthorized;
    }

    /**
     * @param string $username
     * @return void
     */
    public function auditActivity($username)
    {
        $uri = $this->request->getRequestUri();
        $parts = explode('/', $uri);
        $staticControllers = array('styles', 'images', 'scripts', 'estados');
        if (!in_array($parts[1], $staticControllers)) {
            $date = new Zend_Date();
            $auditInformation = sprintf(
                'Usuario: %s, página %s, fecha y hora: %s', $username, $uri,
                $date
            );
            Log::info($auditInformation);
        }
    }
}
