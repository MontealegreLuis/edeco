<?php
class Mandragora_File_Size_Factory
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
            return new Mandragora_File_Size_Byte($sizeInBytes);
        } else if ($sizeInBytes < self::MegaByte) {
            return new Mandragora_File_Size_KiloByte($sizeInBytes);
        } else if ($sizeInBytes < self::GigaByte) {
            return new Mandragora_File_Size_MegaByte($sizeInBytes);
        }
        return new Mandragora_File_Size_GigaByte($sizeInBytes);
    }

}