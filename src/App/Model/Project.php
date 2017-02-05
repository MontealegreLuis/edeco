<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model;

use Mandragora\Model\AbstractModel;
use Edeco_Model_Collection_Picture;
use InvalidArgumentException;
use App\Model\ProjectAttachment;

/**
 * Model for projects
 *
 * @property int $id
 * @property string $name
 * @property string $attachment
 */
class Project extends AbstractModel
{
    /**
     * @var array
     */
    protected $properties = array(
        'name' => null, 'attachment' => null, 'active' => 1,
        'version' => 1,
    );

    /**
     * @var array
     */
    protected $identifier = array('id');

    /**
     * @var Edeco_Model_ProjectAttachment
     */
    protected $attachmentFileHandler;

    /**
     * @var Zend_Cache
     */
    protected $cache;

    /**
     * Overridden setter for the property's picture
     *
     * @param array | Edeco_Model_Collection_Picture $pictureCollection
     * @return void
     */
    public function setPicture($pictures)
    {
        if (is_array($pictures)) {
            $pictures = new Edeco_Model_Collection_Picture($pictures);
        } elseif (!($pictures instanceof Edeco_Model_Collection_Picture)) {
            throw new InvalidArgumentException(
                'Expected array or Edeco_Model_Collection_Picture'
            );
        }
        $this->properties['Picture'] = $pictures;
    }

    /**
     * @param string $fileName
     * @return string
     */
    public function getFileExtension($fileName)
    {
        $fileParts = explode('.', $fileName);
        return $fileParts[count($fileParts) - 1];
    }

    /**
     * @param $fileName
     * @param $extension
     */
    public function createAttachmentFileHandler($fileName, $extension)
    {
        $this->attachmentFileHandler =
            ProjectAttachment::createAttachment(
                $fileName, $extension
            );
        $this->attachment = $this->attachmentFileHandler->getFileName();
    }

    /**
     * @return void
     */
    public function initAttachmentFileHandler()
    {
        $this->attachmentFileHandler =
            ProjectAttachment::retrieveAttachment(
                $this->attachment
            );
    }

    /**
     * @return string
     */
    public function getAttachmentFullName()
    {
        $this->attachment = $this->attachmentFileHandler->getFileName();
        return $this->attachmentFileHandler->getFullName();
    }

    /**
     * @param string $fileName
     * @return Mandragora_File
     */
    public function getAttachmentFileHandler($fileName)
    {
        return $this->attachmentFileHandler->getFile($fileName);
    }

    /**
     * @return void
     */
    public function deleteAttachmentFile()
    {
        return $this->attachmentFileHandler->deleteFile();
    }

    /**
     * @param string $newName
     * @return void
     */
    public function renameAttachmentFile($newName)
    {
        return $this->attachmentFileHandler->renameFile($newName);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->properties['name'];
    }
}
