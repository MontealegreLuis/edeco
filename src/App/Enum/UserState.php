<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Enum;

use Mandragora\Enum\EnumInterface;

/**
 * Enumeration for the user account states
 *
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @version    SVN: $Id$
 * @copyright  Mandrágora Web-Based Systems 2010
 * @category   Application
 * @package    Edeco
 * @subpackage Enum
 * @history    may 27, 2010
 *             LMV
 *             - Class Creation
 */
abstract class UserState implements EnumInterface
{
    /**
     * User account active
     *
     * @var string
     */
    const Active = 'active';

    /**
     * New unconfirmed user account
     *
     * @var string
     */
    const Unconfirmed = 'unconfirmed';

    /**
     * Inactive user account
     *
     * @var string
     */
    const Inactive = 'inactive';

    /**
     * Banned user account
     *
     * @var string
     */
    const Banned = 'banned';

    /**
     * Return all the available types as an associative array
     *
     * @return array
     */
    public static function values()
    {
        return array(
            self::Active => 'Activo',
            self::Unconfirmed => 'Sin confirmar',
            self::Inactive => 'Inactivo',
            self::Banned => 'Bloqueado',
        );
    }
}
