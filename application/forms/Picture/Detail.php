<?php
/**
 * Pictures' form
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
 * @subpackage Form
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Pictures' form
 *
 * @category   Application
 * @package    Edeco
 * @subpackage Form
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class App_Form_Picture_Detail extends Mandragora_Form_Crud_Abstract
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
     * @param string $newName
     *      The new name for the image file
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