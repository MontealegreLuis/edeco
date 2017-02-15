<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Enum;

use Mandragora\Enum\EnumInterface;

/**
 * Accuracy for the Addresses
 */
abstract class AddressAccuracy implements EnumInterface
{
    const ACCURACY_UNKNOWN = 0;

    const ACCURACY_COUNTRY = 1;

    const ACCURACY_REGION = 2;

    const ACCURACY_SUBREGION = 3;

    const ACCURACY_TOWN = 4;

    const ACCURACY_POSTCODE = 5;

    const ACCURACY_STREET = 6;

    const ACCURACY_INTERSECTION = 7;

    const ACCURACY_ADDRESS = 8;

    const ACCURACY_PREMISE = 9;

    /**
     * @return string[]
     */
    public static function values(): array
    {
        return [
            self::ACCURACY_UNKNOWN => 'Desconocido',
            self::ACCURACY_COUNTRY => 'País',
		    self::ACCURACY_REGION => 'Región',
		    self::ACCURACY_SUBREGION => 'Subregión',
		    self::ACCURACY_TOWN => 'Ciudad',
		    self::ACCURACY_POSTCODE => 'Código Postal',
		    self::ACCURACY_STREET => 'Calle',
		    self::ACCURACY_INTERSECTION => 'Intersección',
		    self::ACCURACY_ADDRESS => 'Dirección',
		    self::ACCURACY_PREMISE => 'Nombre del edificio, lugar público, centro comercial, etc.',
        ];
    }
}
