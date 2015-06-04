<?php
class Mandragora_File_Size_GigaByte extends Mandragora_File_Size_Abstract
{
    public function __construct($sizeInBytes)
    {
        $this->size = $sizeInBytes / Mandragora_File_Size_Factory::GigaByte;
    }

    public function __toString()
    {
        return $this->size . 'Gb.';
    }

}