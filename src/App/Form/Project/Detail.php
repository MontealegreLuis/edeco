<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Form\Project;

use Mandragora\Form\Crud\CrudForm;
use Zend_Validate_File_Upload as FileUpload;

/**
 * Form for adding/updating projects
 */
class Detail extends CrudForm
{
    public function init()
    {
        $validator = $this->getElement('attachment')->getValidator('Upload');
        $validator->setMessages([
            FileUpload::FILE_NOT_FOUND => 'project.attachment.fileUploadErrorFileNotFound'
        ]);
    }

    public function saveAttachmentFile(string $newName)
    {
        $powerPointFile = $this->getElement('attachment');
        $powerPointFile->addFilter('Rename', [
            'source' => '*',
            'target' => $newName,
            'overwrite' => true
        ]);
        $powerPointFile->receive();
    }

    public function hasNewPowerPoint(): bool
    {
        return $this->getElement('attachment')->getValue() !== null;
    }

    public function getAttachmentOriginalFileName(): string
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
}
