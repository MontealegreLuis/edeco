<?php
/**
 * Edeco's ACL
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
 * @category   Panel
 * @package    Edeco
 * @subpackage Breadcumbs
 * @author     LNJ <lemuel.nonoal@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Edeco's ACL
 *
 *
 * @author     LNJ <lemuel.nonoal@mandragora-web-systems.com>
 * @version    SVN: $Id$
 * @copyright  Mandrágora Web-Based Systems 2010
 * @category   Panel
 * @package    Edeco
 * @subpackage BreadscrumbsBuilder
 * @history    20 may 2010
 *             LNJ
 *             - Class Creation
 */
class Edeco_Acl extends Zend_Acl
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
            self::$acl = new Edeco_Acl();
    	}
    	return self::$acl;
    }

    public static function setInstance(Edeco_Acl $acl)
    {
        self::$acl = $acl;
    }

}