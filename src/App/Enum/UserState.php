<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Enum;

use Mandragora\Enum\EnumInterface;

/**
 * Enumeration for the user account states
 */
abstract class UserState implements EnumInterface
{
    const Active = 'active';

    const Unconfirmed = 'unconfirmed';

    const Inactive = 'inactive';

    const Banned = 'banned';

    /**
     * @return string[]
     */
    public static function values(): array
    {
        return [
            self::Active => 'Activo',
            self::Unconfirmed => 'Sin confirmar',
            self::Inactive => 'Inactivo',
            self::Banned => 'Bloqueado',
        ];
    }
}
