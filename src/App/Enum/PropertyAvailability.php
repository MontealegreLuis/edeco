<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace App\Enum;

use Mandragora\Enum\EnumInterface;

/**
 * Availability of a property (rent/sale)
 */
abstract class PropertyAvailability implements EnumInterface
{
    const Rent = 'rent';

    const Sale = 'sale';

    /**
     * @return string[]
     */
    public static function values(): array
    {
        return [
            self::Rent => 'renta',
            self::Sale => 'venta',
        ];
    }
}
