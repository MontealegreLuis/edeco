<?php
/**
 * Handler for the picture's files: original, gallery and thumbnail
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
 * Handler for the picture's files: original, gallery and thumbnail
 *
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @version    SVN: $Id$
 * @copyright  Mandrágora Web-Based Systems 2010
 * @category   Application
 * @package    Edeco
 * @subpackage Model
 */
class App_Model_PictureFileHandler
{
    /**
     * @var string
     */
    protected static $imagesDirectory;

    /**
     * @var string
     */
    protected static $picturesDirectory;

    /**
     * @var string
     */
    protected static $galleryDirectory;

    /**
     * @var string
     */
    protected static $thumbsDirectory;

    /**
     * @var string
     */
    protected $pictureFilename;

    /**
     * @var string
     */
    protected $pictureFullPath;

    /**
     * @var string
     */
    protected $galleryFullPath;

    /**
     * @var string
     */
    protected $thumbnailsFullPath;

    /**
     * @var Mandragora_File
     */
    protected $pictureFileHandler;

    /**
     * @var Mandragora_File
     */
    protected $galleryFileHandler;

    /**
     * @var Mandragora_File
     */
    protected $thumbnailFileHandler;

    /**
     * @param string $filename
     * @throws Mandragora_File_Exception
     *      Exception is thrown when the file cannot be found
     */
    public function __construct($filename)
    {
        $this->pictureFilename = (string)$filename;
        $this->pictureFullPath = self::getPicturesDirectory()
            . DIRECTORY_SEPARATOR . $this->pictureFilename;
        $this->pictureFileHandler = new Mandragora_File($this->pictureFullPath);
        $this->createFileHandlers();
    }

    /**
     * Create the file handlers for the gallery and thumbnails images if their
     * corresponding files exist
     *
     * @return void
     */
    protected function createFileHandlers()
    {
        $this->galleryFullPath = self::getGalleryDirectory()
            . DIRECTORY_SEPARATOR . $this->pictureFilename;
        $this->thumbnailsFullPath = self::getThumbsDirectory()
            . DIRECTORY_SEPARATOR . $this->pictureFilename;

        $this->galleryFileHandler =
            $this->createFileHandler($this->galleryFullPath);
        $this->thumbnailFileHandler =
            $this->createFileHandler($this->thumbnailsFullPath);
    }

    /**
     * @param string $path
     * @return Mandragora_File | null
     */
    protected function createFileHandler($path)
    {
        if (Mandragora_File::exists($path)) {
            return new Mandragora_File($path);
        }
        return null;
    }

    /**
     * @return string
     */
    public function readGalleryImage()
    {
        echo $this->galleryFileHandler->read();
    }

    /**
     * @param string $newFilename
     * @return boolean
     */
    public function changePictureFilename($newFilename)
    {
        $newPath =
            self::getPicturesDirectory() . DIRECTORY_SEPARATOR . $newFilename;
        return $this->pictureFileHandler->rename($newPath);
    }

    /**
     * @return boolean
     */
    public function deletePicture()
    {
        return $this->pictureFileHandler->delete();
    }

    /**
     * Get the time when this picture's file was modified
     *
     * @return int
     */
    public function getPictureLastModifiedTime()
    {
        return $this->pictureFileHandler->getLastModifiedTime();
    }

    /**
     * @param string $newFilename
     * @return boolean
     */
    public function changeGalleryFilename($newFilename)
    {
        $newPath =
            self::getGalleryDirectory() . DIRECTORY_SEPARATOR . $newFilename;
        return $this->galleryFileHandler->rename($newPath);
    }

    /**
     * @return boolean
     */
    public function deleteGallery()
    {
        return $this->galleryFileHandler->delete();
    }

    /**
     * @return int
     */
    public function getGalleryLastModifiedTime()
    {
        return $this->galleryFileHandler->getLastModifiedTime();
    }

    /**
     * @param string $newFilename
     * @return boolean
     */
    public function changeThumbnailFilename($newFilename)
    {
        $newPath =
            self::getThumbsDirectory() . DIRECTORY_SEPARATOR . $newFilename;
        return $this->thumbnailFileHandler->rename($newPath);
    }

    /**
     * @return boolean
     */
    public function deleteThumbnail()
    {
        return $this->thumbnailFileHandler->delete();
    }

    /**
     * @return int
     */
    public function getThumbnailLastModifiedTime()
    {
        return $this->thumbnailFileHandler->getLastModifiedTime();
    }

    /**
     * @return void
     */
    public function createThumbnail()
    {
        $thumb = PhpThumb_Factory::create($this->pictureFullPath);
        $thumb->resize(80, 55);
        $thumb->save(
            self::getThumbsDirectory()
            . DIRECTORY_SEPARATOR
            . $this->pictureFileHandler->getNameAndExtension()
        );
    }

    /**
     * @return void
     */
    public function createGalleryImage()
    {
        $thumb = PhpThumb_Factory::create($this->pictureFullPath);
        $thumb->resize(275, 190);
        $thumb->save(
            self::getGalleryDirectory()
            . DIRECTORY_SEPARATOR
            . $this->pictureFileHandler->getNameAndExtension()
        );
    }

    /**
     * @return string
     */
    public static function getPicturesDirectory()
    {
        if (self::$picturesDirectory == null) {
            self::$picturesDirectory = realpath(
                self::getImagesDirectory() . App_Enum_Directories::Properties
            );
        }
        return self::$picturesDirectory;
    }

    /**
     * @return string
     */
    protected static function getGalleryDirectory()
    {
        if (self::$galleryDirectory == null) {
            self::$galleryDirectory = realpath(
                self::getImagesDirectory() . App_Enum_Directories::Gallery
            );
        }
        return self::$galleryDirectory;
    }

    /**
     * @return string
     */
    public static function getThumbsDirectory()
    {
        if (self::$thumbsDirectory == null) {
            self::$thumbsDirectory = realpath(
                self::getImagesDirectory() . App_Enum_Directories::Thumbnails
            );
        }
        return self::$thumbsDirectory;
    }

    /**
     * @return string
     */
    protected static function getImagesDirectory()
    {
        if (!self::$imagesDirectory) {
            $config = new Zend_Config_Ini(
                APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV
            );
            self::$imagesDirectory = realpath($config->app->images->directory);
        }
        return self::$imagesDirectory;
    }

    /**
     * Filter the filename to only contain alphanumeric characters and dashes
     *
     * @param string $filename
     * @return string
     */
    public static function filterFileName($filename)
    {
        $filenameFilter = new Mandragora_Filter_FriendlyUrl();
        return $filenameFilter->filter((string)$filename) . '.jpg';
    }

}