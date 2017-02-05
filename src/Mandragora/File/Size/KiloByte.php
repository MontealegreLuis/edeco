<?php


namespace Mandragora\File\Size;

use Mandragora\File\Size\AbstractSize;
use Mandragora\File\Size\Factory;


class KiloByte extends AbstractSize
{
    public function __construct($sizeInBytes)
    {
        $this->size = $sizeInBytes / Factory::KiloByte;
    }

    public function __toString()
    {
        return $this->size . 'Kb.';
    }

}