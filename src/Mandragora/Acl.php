<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora;

/**
 * FActory for ACL objects.
 *
 * It verifies if the user is logged and has permission to access the current
 * action during the predispatch phase.
 */
class Acl
{
    /**
     * @param atring $className
     * @param array $options
     * @return \Mandragora\Service\Acl\AclInterface
     */
    public static function factory($className, $options = array())
    {
        $aclClassName = sprintf('App\Service\Acl\%s', $className);
        $acl = new $aclClassName();
        $acl->setOptions($options);
        return $acl;
    }
}
