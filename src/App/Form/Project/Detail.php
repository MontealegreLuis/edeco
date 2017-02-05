<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Form\Project;

use Mandragora\Form\Crud\AbstractCrud;
use Zend_Validate_File_Upload;

/**
 * Form for adding/updating projects
 */
class Detail extends AbstractCrud
{
    public function init()
    {
        $validator = $this->getElement('attachment')->getValidator('Upload');
        $validator->setMessages(
            array(
                Zend_Validate_File_Upload::FILE_NOT_FOUND =>
                    'project.attachment.fileUploadErrorFileNotFound'
            )
        );
    }

    /**
     * @param string $newName
     */
    public function saveAttachmentFile($newName)
    {
        $powerPointFile = $this->getElement('attachment');
        $powerPointFile->addFilter('Rename',
            array(
                'source' => '*',
                'target' => $newName,
                'overwrite' => true
            )
        );
        $powerPointFile->receive();
    }

    /**
     * @return boolean
     */
    public function hasNewPowerPoint()
    {
        return $this->getElement('attachment')->getValue() != null;
    }

    /**
     * @return string
     */
    public function getAttachmentOriginalFileName()
    {
        return $this->getElement('attachment')->getValue();
    }

    /**
     * @return void
     */
    public function prepareForCreating()
    {
        $this->removeElement('id');
        $this->removeElement('version');
    }

    /**
     * @return void
     */
    public function prepareForEditing() {}
}
