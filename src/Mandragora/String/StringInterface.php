<?php
/**
 * The objects implementing this interface can be converted to string
 *
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\String;

/**
 * The objects implementing this interface can be converted to string
 */
interface StringInterface
{
    /**
     * @return string
     */
    public function __toString();
}