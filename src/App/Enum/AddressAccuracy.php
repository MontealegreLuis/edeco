<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace App\Enum;

use Mandragora\Enum\EnumInterface;

/**
 * Accuracy for the Addresss
 */
abstract class AddressAccuracy implements EnumInterface
{
    /**
     * Accurancy unknown for Address
     *
     * @var string
     */
    const ACCURACY_UNKNOWN = 0;

    /**
     * Accuracy country for Address
     *
     * @var string
     */
    const ACCURACY_COUNTRY = 1;

    /**
     * Accuracy region for Address
     *
     * @var string
     */
    const ACCURACY_REGION = 2;

    /**
     * Accuracy subregion for Address
     *
     * @var string
     */
    const ACCURACY_SUBREGION = 3;

    /**
     * Accuracy town for Address
     *
     * @var string
     */
    const ACCURACY_TOWN = 4;

    /**
     * Accuracy postcode for Address
     *
     * @var string
     */
    const ACCURACY_POSTCODE = 5;

    /**
     * Accuracy street for Address
     *
     * @var string
     */
    const ACCURACY_STREET = 6;

    /**
     * Accuracy intersection for Address
     *
     * @var string
     */
    const ACCURACY_INTERSECTION = 7;

    /**
     * Accuracy address for Address
     *
     * @var string
     */
    const ACCURACY_ADDRESS = 8;

    /**
     * Accuracy address for Premise (building name, property name,
     * shopping center, etc.) level accuracy
     *
     * @var string
     */
    const ACCURACY_PREMISE = 9;

    /**
     * Return all the accuracy as an associative array
     *
     * @return array
     */
    public static function values()
    {
        return array(
            self::ACCURACY_UNKNOWN => 'Desconocido',
            self::ACCURACY_COUNTRY => 'País',
		    self::ACCURACY_REGION => 'Región',
		    self::ACCURACY_SUBREGION => 'Subregión',
		    self::ACCURACY_TOWN => 'Ciudad',
		    self::ACCURACY_POSTCODE => 'Código Postal',
		    self::ACCURACY_STREET => 'Calle',
		    self::ACCURACY_INTERSECTION => 'Intersección',
		    self::ACCURACY_ADDRESS => 'Dirección',
		    self::ACCURACY_PREMISE =>
                'Nombre del edificio, lugar público, centro comercial, etc.',
        );
    }
}
