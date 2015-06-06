<?php
class Mandragora_File_Size_Byte extends Mandragora_File_Size_Abstract
{
    public function __construct($sizeInBytes)
    {
        $this->size = $sizeInBytes;
    }

    public function __toString()
    {
        return $this->size . 'bytes.';
    }

}