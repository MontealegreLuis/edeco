<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Form\Picture;

use Mandragora\Form\Crud\AbstractCrud;
use Mandragora\Validate\Db\Doctrine\NoRecordExists;

/**
 * Pictures' form
 */
class Detail extends AbstractCrud
{
    /**
     * Remove both the control for the picture's id, and the one for showing the
     * image
     *
     *  @return void
     */
    public function prepareForCreating()
    {
        $this->removeElement('id');
        $this->removeElement('version');
        $this->removeElement('image');
    }

    /**
     * Make the file optional as it may not be changed
     *
     * @return void
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

    /**
     * @param int $pictureId
     */
    public function setPictureIdValue(int $pictureId)
    {
        $this->getElement('id')->setValue($pictureId);
    }

    /**
     * Set the src parameter of the image control
     */
    public function setSrcImage(string $filename)
    {
        $this->getElement('image')->setImage($filename);
    }

    /**
     * Return the name of the file that is being uploaded
     */
    public function getPictureFileValue(): string
    {
        return $this->getElement('filename')->getValue();
    }

    /**
     * Save the image file and rename it
     */
    public function savePictureFile(string $filename)
    {
        $imageFile = $this->getElement('filename');
        $imageFile->addFilter('Rename', [
            'source' => '*',
            'target' => $filename,
            'overwrite' => true
        ]);
        $imageFile->receive();
    }
}
