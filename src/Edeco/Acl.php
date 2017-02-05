<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Edeco;

use Zend_Acl;
use Edeco\Acl as EdecoAcl;

/**
 * Edeco's ACL
 */
class Acl extends Zend_Acl
{
	/**
	 * @var
	 */
	protected static $acl;

    /**
     * Sets Edeco's ACL
     *
     * @return void
     */
    protected function __construct() {}

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
     * @return Edeco_Acl
     */
    public static function getInstance()
    {
    	if (self::$acl == null) {
            self::$acl = new EdecoAcl();
    	}
    	return self::$acl;
    }

    public static function setInstance(EdecoAcl $acl)
    {
        self::$acl = $acl;
    }
}
