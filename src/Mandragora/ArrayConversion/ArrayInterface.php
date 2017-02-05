<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\ArrayConversion;

/**
 * The objects implementing this interface can be converted to array
 */
interface ArrayInterface
{
    /**
     * @return array
     */
    public function toArray();
}
