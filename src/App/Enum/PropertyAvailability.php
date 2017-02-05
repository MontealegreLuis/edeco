<?php
/**
 * PHP version 5
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
    /**
     * @var string
     */
    const Rent = 'rent';

    /**
     * @var string
     */
    const Sale = 'sale';

    /**
     * Return enum values as an associative array
     *
     * @return array
     */
    public static function values()
    {
        return array(
            self::Rent => 'renta',
            self::Sale => 'venta',
        );
    }
}
