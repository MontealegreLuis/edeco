<?php
/**
 * PHP version 7.1
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
    const Housing = 'housing';

    const Commercial = 'commercial';

    const Industrial = 'industrial';

    const Mixed = 'mixed';

    /**
     * @return string[]
     */
    public static function values(): array
    {
        return [
            self::Housing => 'Habitacional',
            self::Commercial => 'Comercial',
            self::Industrial => 'Industrial',
            self::Mixed => 'Mixto',
        ];
    }
}
