<?php
/**
 * Model for projects
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
 * Model for projects
 *
 * @property int $id
 * @property string $name
 * @property string $attachment
 *
 * @package    Edeco
 * @subpackage Model
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class App_Model_Project extends Mandragora_Model_Abstract
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
            App_Model_ProjectAttachment::createAttachment(
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
            App_Model_ProjectAttachment::retrieveAttachment(
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