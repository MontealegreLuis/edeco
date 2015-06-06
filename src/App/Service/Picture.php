<?php
/**
 * Service class for Picture model
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
 * @subpackage Service
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Service class for Property model
 *
 * @category   Application
 * @package    Edeco
 * @subpackage Service
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class   App_Service_Picture
extends Mandragora_Service_Crud_Doctrine_Abstract
{
    /**
     * @return void
     */
    public function init()
    {
        $this->openConnection();
        $this->decorateGateway();
    }

    /**
     * @return Edeco_Model_Collection_Picture
     */
    public function retrieveAllPicturesByPropertyId($propertyId, $pageNumber)
    {
        $this->init();
        $query = $this->getGateway()->getQueryFindAllByPropertyId($propertyId);
        $this->setPaginatorQuery($query);
        $items = (array)$this->getPaginator($pageNumber)->getCurrentItems();
        return new App_Model_Collection_Picture($items);
    }

    /**
     * @param int $id Picture's id
     * @param int $propertyId
     * @return void
     */
    public function retrievePictureByIdAndPropertyId($id, $propertyId)
    {
        $this->init();
        $pictureInformation = $this->getGateway()->findOneByIdAndPropertyId(
            (int)$id, (int)$propertyId
        );
        return $this->getModel($pictureInformation);
    }

    /**
     * @return string
     */
    public function getPictureFileName()
    {
        return $this->model->filename;
    }

    /**
     * @return void
     */
    public function createPicture()
    {
        $this->getModel()->fromArray($this->getForm()->getValues());
        $this->saveImageFileInForm();
        $this->getModel()->filename = $this->buildFilename();
        $this->getModel()->createImages();
        $this->getGateway()->insert($this->getModel());
    }

    /**
     * Updates the given picture in the database and renames the file if needed
     *
     * @return void
     *
     */
    public function updatePicture()
    {
        $id = $this->getForm()->getValue('id');
        $propertyId = $this->getForm()->getValue('propertyId');
        $pictureValues = $this->getGateway()
                              ->findOneByIdAndPropertyId($id, $propertyId);
        $this->setModel(Mandragora_Model::factory('Picture', $pictureValues));
        if ($this->isTheImageNew()) {
            $this->saveImageFileInForm();
            if ($this->isNewDescription()) {
                //$this->getModel()->deleteAllImages();
            }
            // Filter the new name to create the images accordingly
            $this->getModel()->filename = $this->buildFilename();
            $this->getModel()->createImages();
        } else {
            if ($this->isNewDescription()) {
                $newFilename = App_Model_PictureFileHandler::filterFileName(
                    $this->buildFilename()
                );
                $this->getModel()->changeAllFilenames($newFilename);
            }
        }
        $pictureValues = $this->getForm()->getValues();
        $this->setModel(Mandragora_Model::factory('Picture', $pictureValues));
        $this->getModel()->filename = $this->buildFilename();
        $this->getGateway()->update($this->getModel());
    }

    /**
     * Use the property name and picture description for naming the picture
     *
     * @return void
     */
    protected function saveImageFileInForm()
    {
        $fileName = App_Model_PictureFileHandler::filterFileName(
            $this->buildFilename()
        );
        $fullName = App_Model_PictureFileHandler::getPicturesDirectory()
            . DIRECTORY_SEPARATOR . (string)$fileName;
        $this->getForm()->savePictureFile($fullName);
    }

    /**
     * Create the name for the new picture's file by concatenating the name of
     * the property and the picture's short description
     *
     * @return string
     */
    protected function buildFilename()
    {
        $gateway = Mandragora_Gateway::factory('Property');
        $gateway = new App_Model_Gateway_Cache_Property($gateway);
        $gateway->setCache($this->getCache('gateway'));
        $this->getModel()->setGateway($gateway);
        return $this->getModel()->buildFilename(
            $this->getForm()->getValue('propertyId'),
            $this->getForm()->getValue('shortDescription')
        );
    }

    /**
     * Verifies if the administrator is uploading a new image
     *
     * @return boolean
     *      True if the form contains a new image
     */
    protected function isTheImageNew()
    {
        return $this->getForm()->getPictureFileValue() != null;
    }

    /**
     * Determine if the short description has changed or not
     *
     * @return boolean
     */
    protected function isNewDescription()
    {
        return $this->getModel()->shortDescription
            != $this->getForm()->getValue('shortDescription');
    }

    /**
     * Delete the picture from the database and the corresponding image files
     *
     * @return void
     */
    public function deletePicture($id, $propertyId)
    {
        $propertyInformation = $this->getGateway()->findOneByIdAndPropertyId(
            (int)$id, (int)$propertyId
        );
        $this->getModel()->fromArray($propertyInformation);
        $this->getModel()->deleteAllImages();
        $this->getGateway()->delete($this->getModel());
    }

    /**
     * @param string $action
     * @return Edeco_Form_Picture_Detail
     */
    public function getFormForCreating($action)
    {
        $this->createForm('Detail');
        $this->getForm()->setAction($action);
        $this->getForm()->prepareForCreating();
        return $this->getForm();
    }

    /**
     * @param string $action
     * @return Edeco_Form_Picture_Detail
     */
    public function getFormForEditing($action)
    {
        $this->createForm('Detail');
        $this->getForm()->setAction($action);
        $this->getForm()->prepareForEditing();
        return $this->getForm();
    }

    /**
     * @return void
     */
    protected function createForm($formName)
    {
        //Do not use cache in forms with 'file' elements
        $this->getForm($formName, true, true);
    }

}