<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\File\Size;

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
