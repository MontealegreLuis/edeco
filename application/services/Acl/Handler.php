<?php
class App_Service_Acl_Handler implements Mandragora_Service_Acl_Interface
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
            if ($this->acl->has($resource) &&
                !$this->acl->isAllowed('guest', $resource, $action)) {
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
            $acl = Mandragora_Acl_Wrapper::getInstance();
            $rolesService = Mandragora_Service::factory('Role');
            $rolesService->setCacheManager($cacheManager);
            $rolesService->setDoctrineManager($doctrineManager);
            $permissionsService = Mandragora_Service::factory('Permission');
            $permissionsService->setCacheManager($cacheManager);
            $permissionsService->setDoctrineManager($doctrineManager);
            $resourceService = Mandragora_Service::factory('Resource');
            $resourceService->setCacheManager($cacheManager);
            $resourceService->setDoctrineManager($doctrineManager);
            $acl->setRoles($rolesService->retrieveAllRoles());
            $acl->setResources($resourceService->retrieveAllResources());
            $acl->setPermissions($permissionsService->retrieveAllPermissions());
            $cache->save($acl, 'acl');
        }
        Mandragora_Acl_Wrapper::setInstance($acl); //Recover acl from cache
        $this->acl = Mandragora_Acl_Wrapper::getInstance();
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
            Mandragora_Log::info($auditInformation);
        }
    }

}