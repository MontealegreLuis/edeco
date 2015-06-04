<?php
/**
 * Custom ACL class to inject in the controller plugin that handles ACL
 *
 * PHP version 5
 *
 * LICENSE: Redistribution and use of this file in source and binary forms,
 * with or without modification, is not permitted under any circumstance
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   Library
 * @package    Mandragora
 * @subpackage Acl
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Custom ACL class to inject in the controller plugin that handles ACL
 *
 *
 * @category   Library
 * @package    Mandragora
 * @subpackage Acl
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class Mandragora_Acl_Wrapper extends Zend_Acl
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
     * @param Mandragora_Acl_Wrapper $acl
     * @return void
     */
    public static function setInstance(Mandragora_Acl_Wrapper $acl)
    {
        self::$acl = $acl;
    }

}