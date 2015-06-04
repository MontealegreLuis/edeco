<?php
class Mandragora_File_Size_MegaByte extends Mandragora_File_Size_Abstract
{
    public function __construct($sizeInBytes)
    {
        $this->size = $sizeInBytes / Mandragora_File_Size_Factory::MegaByte;
    }

    public function __toString()
    {
        return $this->size . 'Mb.';
    }

}