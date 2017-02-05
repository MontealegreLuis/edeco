<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Acl;

use Zend_Acl;

/**
 * Custom ACL class to inject in the controller plugin that handles ACL
 */
class Wrapper extends Zend_Acl
{
    /**
     * @var
     */
    protected static $acl;

    /**
     * @return void
     */
    private function __construct() {}

    /**
     * @param array $roles
     * @return void
     */
    public function setRoles(array $roles)
    {
        foreach ($roles as $role) {
            $this->addRole($role['name'], $role['parentRole']);
        }
    }

    /**
     * @param array $resources
     * @return void
     */
    public function setResources(array $resources)
    {
        foreach ($resources as $controller) {
            if ($controller['name'] != '*') {
                $this->addResource($controller['name']);
            }
        }
    }

    /**
     * @param $permissions
     * @return void
     */
    public function setPermissions(array $permissions)
    {
        foreach($permissions as $permission) {
            if ($permission['resourceName'] == '*') {
               $this->allow($permission['roleName'], null);
            } else  if ($permission['name'] == '*') {
                $this->allow(
                   $permission['roleName'], $permission['resourceName'], null
               );
            } else {
                $this->allow(
                   $permission['roleName'], $permission['resourceName'],
                   $permission['name']
                );
            }
        }
    }

    /**
     * @return Mandragora_Acl_Wrapper
     */
    public static function getInstance()
    {
        if (is_null(self::$acl)) {
            self::$acl = new self();
        }
        return self::$acl;
    }

    /**
     * @param Wrapper $acl
     * @return void
     */
    public static function setInstance(Wrapper $acl)
    {
        self::$acl = $acl;
    }
}
