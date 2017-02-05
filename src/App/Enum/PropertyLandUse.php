<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace App\Enum;

use Mandragora\Enum\EnumInterface;

/**
 * Enum for describing the legal use of the properties
 */
class PropertyLandUse implements EnumInterface
{
    /**
     * @var string
     */
    const Housing = 'housing';

    /**
     * @var string
     */
    const Commercial = 'commercial';

    /**
     * @var string
     */
    const Industrial = 'industrial';

    /**
     * @var string
     */
    const Mixed = 'mixed';

    /**
     * Return all the available types as an associative array
     *
     * @return array
     */
    public static function values()
    {
        return array(
            self::Housing => 'Habitacional',
            self::Commercial => 'Comercial',
            self::Industrial => 'Industrial',
            self::Mixed => 'Mixto',
        );
    }
}
