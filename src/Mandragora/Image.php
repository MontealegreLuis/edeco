<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora;

/**
 * Handle image size information
 */
class Image
{
    /** @var resource */
    protected $image;

    public function __construct(string $pathToImage)
    {
        $this->image = imagecreatefromjpeg($pathToImage);
    }

     /**
      * Image width in pixels
      */
    public function getWidth(): int
    {
        return imagesx($this->image);
    }

    /**
     * Image height in pixels
     */
    public function getHeight(): int
    {
        return imagesy($this->image);
    }
}
