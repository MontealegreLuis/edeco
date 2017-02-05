<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Form\Picture;

use Mandragora\Form\Crud\AbstractCrud;

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
             ->removeValidator('Db_Doctrine_NoRecordExists');
    }

    /**
     * @param int $propertyId
     */
    public function setPropertyId($propertyId)
    {
        $this->getElement('propertyId')->setValue((int)$propertyId);
    }

    /**
     * @param int $pictureId
     */
    public function setPictureIdValue($pictureId)
    {
        $this->getElement($this->pictureId)->setValue((int)$pictureId);
    }

    /**
     * Set the src parameter of the image control
     *
     * @param string $filename
     */
    public function setSrcImage($filename)
    {
        $this->getElement('image')->setImage((string)$filename);
    }

    /**
     * Return the name of the file that is being uploaded
     *
     * @return string
     */
    public function getPictureFileValue()
    {
        return $this->getElement('filename')->getValue();
    }

    /**
     * Save the image file and rename it
     *
     * @param string $filename The new name for the image file
     */
    public function savePictureFile($filename)
    {
        $imageFile = $this->getElement('filename');
        $imageFile->addFilter('Rename',
            array(
                'source' => '*',
                'target' => $filename,
                'overwrite' => true
            )
        );
        $imageFile->receive();
    }
}
