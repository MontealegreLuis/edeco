<?php


namespace Mandragora\File\Size;

use Mandragora\File\Size\AbstractSize;
use Mandragora\File\Size\Factory;


class MegaByte extends AbstractSize
{
    public function __construct($sizeInBytes)
    {
        $this->size = $sizeInBytes / Factory::MegaByte;
    }

    public function __toString()
    {
        return $this->size . 'Mb.';
    }

}