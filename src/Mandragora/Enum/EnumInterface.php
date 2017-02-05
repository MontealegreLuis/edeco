<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Enum;

/**
 * Interface for "enum implementations"
 */
interface EnumInterface
{
    /**
     * Get all the enum values as an associative array
     *
     * @return array
     */
    public static function values();
}
