<?php


namespace Mandragora\File\Size;

use Mandragora\File\Size\Byte;
use Mandragora\File\Size\KiloByte;
use Mandragora\File\Size\MegaByte;
use Mandragora\File\Size\GigaByte;


class Factory
{
    const KiloByte = 1024;
    const MegaByte = 1048576;
    const GigaByte = 1073741824;

    protected function __construct() { }

    /**
     * @param Mandragora_File_Size_Abstract $sizeInBytes
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