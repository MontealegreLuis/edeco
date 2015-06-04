<?php
class Mandragora_File_Size_KiloByte extends Mandragora_File_Size_Abstract
{
    public function __construct($sizeInBytes)
    {
        $this->size = $sizeInBytes / Mandragora_File_Size_Factory::KiloByte;
    }

    public function __toString()
    {
        return $this->size . 'Kb.';
    }

}