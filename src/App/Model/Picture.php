<?php
/**
 * Contains all the information related to the pictures of the properties being
 * sold or rented
 *
 * PHP version 5
 *
 * LICENSE: Redistribution and use of this file in source and binary forms,
 * with or without modification, is not permitted under any circumstance
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   Application
 * @package    Edeco
 * @subpackage Model
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Contains all the information related to the pictures of the properties being
 * sold or rented
 *
 * @property integer $id
 * @property string $shortDescription
 * @property string $filename
 * @property integer $propertyId
 *
 * @category   Application
 * @package    Edeco
 * @subpackage Model
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class   App_Model_Picture
extends Mandragora_Model_Abstract
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
            $filteredName = App_Model_PictureFileHandler::filterFileName(
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
            $this->pictureFileHandler = new App_Model_PictureFileHandler(
                $this->filename
            );
        }
    }

    /**
     * @return string
     */
    public function galleryToString()
    {
        return App_Enum_Directories::Gallery . $this->filename
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
        $fullPath = App_Model_PictureFileHandler::getThumbsDirectory()
            . DIRECTORY_SEPARATOR . $this->filename;
        $imageHandler = new Mandragora_Image($fullPath);
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
        Mandragora_Gateway_Decorator_CacheAbstract $gateway
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
        return App_Enum_Directories::Properties . $this->filename
            . '?mo=' . $this->pictureFileHandler->getPictureLastModifiedTime();
    }


}