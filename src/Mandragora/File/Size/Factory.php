<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\File\Size;

class Factory
{
    const KiloByte = 1024;
    const MegaByte = 1048576;
    const GigaByte = 1073741824;

    protected function __construct() { }

    /**
     * @param int $sizeInBytes
     * @return AbstractSize
     */
    public static function create($sizeInBytes)
    {
        if ($sizeInBytes < self::KiloByte) {
            return new Byte($sizeInBytes);
        } else if ($sizeInBytes < self::MegaByte) {
            return new KiloByte($sizeInBytes);
        } else if ($sizeInBytes < self::GigaByte) {
            return new MegaByte($sizeInBytes);
        }
        return new GigaByte($sizeInBytes);
    }
}
