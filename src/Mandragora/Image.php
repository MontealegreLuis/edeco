<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora;

/**
 * Handle image size information
 */
class Image
{
    /**
     * @var resource
     */
    protected $image;

    /**
     * @param string $pathToImage
     */
    public function __construct($pathToImage)
    {
        $image = ImageCreateFromJpeg((string)$pathToImage);
        $this->image = $image;
    }

     /**
     * Get Image Width in Pixels
     *
     * @return int
     */
    public function getWidth()
    {
        return imagesx($this->image);
    }

    /**
     * Get Image Height in Pixels
     *
     * @return int
     */
    public function getHeight()
    {
        return imagesy($this->image);
    }
}
