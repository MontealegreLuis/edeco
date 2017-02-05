<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Enum;

/**
 * Enumeration for the application directories
 */
abstract class Directories
{
    /**
     * @var string
     */
    const Properties = '/images/properties/';

    /**
     * @var string
     */
    const Thumbnails = '/images/thumbs/';

    /**
     * @var string
     */
    const Gallery = '/images/gallery/';
}
