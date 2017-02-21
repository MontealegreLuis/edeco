<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Form\Picture;

use Mandragora\Form\Crud\CrudForm;
use Mandragora\Validate\Db\Doctrine\NoRecordExists;

/**
 * Pictures' form
 */
class Detail extends CrudForm
{
    /**
     * Remove following elements: id, version, image
     */
    public function prepareForCreating()
    {
        $this->removeElement('id');
        $this->removeElement('version');
        $this->removeElement('image');
    }

    /**
     * Make the file optional, and remove validators. It may not be changed
     * Remove the unique validator for the image's short description
     */
    public function prepareForEditing()
    {
        $this->getElement('filename')
             ->clearValidators()
             ->setRequired(false);
        $this->getElement('shortDescription')
             ->removeValidator(NoRecordExists::class);
    }

    public function setPropertyId(int $propertyId)
    {
        $this->getElement('propertyId')->setValue($propertyId);
    }

    public function setPictureIdValue(int $pictureId)
    {
        $this->getElement('id')->setValue($pictureId);
    }

    /**
     * Set the src parameter of the image control
     */
    public function setSrcImage(string $filename)
    {
        /** @var \Zend_Form_Element_Image $image */
        $image = $this->getElement('image');
        $image->setImage($filename);
    }

    public function getPictureFileValue(): ?string
    {
        return $this->getElement('filename')->getValue();
    }

    /**
     * Save the image file and rename it
     */
    public function savePictureFile(string $filename)
    {
        /** @var \Zend_Form_Element_File $imageFile */
        $imageFile = $this->getElement('filename');
        $imageFile->addFilter('Rename', [
            'source' => '*',
            'target' => $filename,
            'overwrite' => true
        ]);
        $imageFile->receive();
    }
}
