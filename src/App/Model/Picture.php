<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model;

use Mandragora\Model\AbstractModel;
use App\Model\PictureFileHandler;
use App\Enum\Directories;
use Edeco_Enum_Directories;
use Mandragora\Image;
use Mandragora\Gateway\Decorator\CacheAbstract;

/**
 * Contains all the information related to the pictures of the properties being
 * sold or rented
 *
 * @property integer $id
 * @property string $shortDescription
 * @property string $filename
 * @property integer $propertyId
 */
class Picture extends AbstractModel
{
    /**
     * @var array
     */
    protected $properties = array(
        'id' => null, 'shortDescription' => null, 'filename' => null,
        'propertyId' => null
    );

    /**
     * @var array
     */
    protected $identifier = array('id');

    /**
     * @var Edeco_Model_PictureFile
     */
    protected $pictureFileHandler = null;

    /**
     * @var Mandragora_Gateway_Decorator_CacheAbstract
     */
    protected $gateway;

    /**
     * Overridden method used to filter the name of the picture to make it a
     * valid file name
     *
     * @param string $filename
     *      The name of the property followed by the description of the picture
     * @return void
     */
    public function setFilename($filename)
    {
        if (!preg_match('/.*\.jpg/', $filename)) {
            $filteredName = PictureFileHandler::filterFileName(
                (string)$filename
            );
            $this->properties['filename'] = $filteredName;
        } else {
            // Name is already filtered, so only display it
            $this->properties['filename'] = $filename;
        }
    }

    /**
     * Create thumbnail and gallery images
     *
     * @return void
     */
    public function createImages()
    {
        $this->refreshPictureFileHandler();
        $this->pictureFileHandler->createThumbnail();
        $this->pictureFileHandler->createGalleryImage();
    }

    /**
     * @return void
     */
    public function deleteAllImages()
    {
        $this->refreshPictureFileHandler();
        $this->pictureFileHandler->deletePicture();
        $this->pictureFileHandler->deleteGallery();
        $this->pictureFileHandler->deleteThumbnail();
    }

    /**
     * @param string $newFilename
     */
    public function changeAllFilenames($newFilename)
    {
        $this->refreshPictureFileHandler();
        $this->pictureFileHandler->changePictureFilename((string)$newFilename);
        $this->pictureFileHandler->changeGalleryFilename((string)$newFilename);
        $this->pictureFileHandler->changeThumbnailFilename((string)$newFilename);
    }

    /**
     * @return void
     */
    protected function refreshPictureFileHandler()
    {
        if (!$this->pictureFileHandler) {
            $this->pictureFileHandler = new PictureFileHandler(
                $this->filename
            );
        }
    }

    /**
     * @return string
     */
    public function galleryToString()
    {
        return Directories::Gallery . $this->filename
               . '?mo=' . $this->getGalleryLastModifiedTime();
    }

    /**
     * @return int
     */
    protected function getGalleryLastModifiedTime()
    {
        $this->refreshPictureFileHandler();
        return $this->pictureFileHandler->getGalleryLastModifiedTime();
    }

    /**
     * @return string
     */
    public function thumbnailToString()
    {
        return Edeco_Enum_Directories::Thumbnails . $this->filename
            . '?mo=' . $this->getThumbnailLastModifiedTime();
    }

    /**
     * @return int
     */
    protected function getThumbnailLastModifiedTime()
    {
        $this->refreshPictureFileHandler();
        return $this->pictureFileHandler->getThumbnailLastModifiedTime();
    }

    /**
     * @return array
     */
    public function getThumbnailWidthAndHeight()
    {
        $fullPath = PictureFileHandler::getThumbsDirectory()
            . DIRECTORY_SEPARATOR . $this->filename;
        $imageHandler = new Image($fullPath);
        return array(
            'width' => $imageHandler->getWidth(),
            'height' => $imageHandler->getHeight()
        );
    }

    /**
     * @param Mandragora_Gateway_Decorator_CacheAbstract $gateway
     * @return void
     */
    public function setGateway(
        CacheAbstract $gateway
    )
    {
        $this->gateway = $gateway;
    }

    /**
     * Build the filename by concatenating the property name followed by the
     * picture's short description
     *
     * @param string $propertyId
     * @param string $description
     * @return string
     */
    public function buildFilename($propertyId, $description)
    {
        return $this->gateway->findPropertyNameById($this->propertyId)
            . ' ' . $description;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $this->refreshPictureFileHandler();
        return Directories::Properties . $this->filename
            . '?mo=' . $this->pictureFileHandler->getPictureLastModifiedTime();
    }
}
