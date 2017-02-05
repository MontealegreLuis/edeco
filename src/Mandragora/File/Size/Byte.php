<?php


namespace Mandragora\File\Size;

use Mandragora\File\Size\AbstractSize;


class Byte extends AbstractSize
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