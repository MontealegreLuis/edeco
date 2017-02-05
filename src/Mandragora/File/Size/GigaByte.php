<?php


namespace Mandragora\File\Size;

use Mandragora\File\Size\AbstractSize;
use Mandragora\File\Size\Factory;


class GigaByte extends AbstractSize
{
    public function __construct($sizeInBytes)
    {
        $this->size = $sizeInBytes / Factory::GigaByte;
    }

    public function __toString()
    {
        return $this->size . 'Gb.';
    }

}