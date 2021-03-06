<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use App\Model\Collection\Picture as PictureCollection;
use App\Model\Gateway\Cache\Property;
use App\Model\Picture as PictureModel;
use App\Model\PictureFileHandler;
use Mandragora\Model\AbstractModel;
use Mandragora\Service\Crud\Doctrine\DoctrineCrud;
use Mandragora\Gateway;

/**
 * Service class for Property model
 */
class Picture extends DoctrineCrud
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
     * @return PictureCollection
     */
    public function retrieveAllPicturesByPropertyId(int $propertyId, int $pageNumber)
    {
        $this->init();
        $query = $this->getGateway()->getQueryFindAllByPropertyId($propertyId);
        $this->setPaginatorQuery($query);
        $items = (array) $this->getPaginator($pageNumber)->getCurrentItems();
        return new PictureCollection($items);
    }

    /**
     * @return void
     */
    public function retrievePictureByIdAndPropertyId(int $id, int $propertyId)
    {
        $this->init();
        $pictureInformation = $this->getGateway()->findOneByIdAndPropertyId($id, $propertyId);
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
        $this->setModel(new PictureModel($pictureValues));
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
                $newFilename = PictureFileHandler::filterFileName(
                    $this->buildFilename()
                );
                $this->getModel()->changeAllFilenames($newFilename);
            }
        }
        $pictureValues = $this->getForm()->getValues();
        $this->setModel(new PictureModel($pictureValues));
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
        $fileName = PictureFileHandler::filterFileName(
            $this->buildFilename()
        );
        $fullName = PictureFileHandler::getPicturesDirectory()
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
        $gateway = Gateway::factory('Property');
        $gateway = new Property($gateway);
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
     * @return \App\Form\Picture\Detail
     */
    public function getFormForCreating($action)
    {
        $this->createForm();
        $this->getForm()->setAction($action);
        $this->getForm()->prepareForCreating();
        return $this->getForm();
    }

    /**
     * @param string $action
     * @return \App\Form\Picture\Detail
     */
    public function getFormForEditing($action)
    {
        $this->createForm();
        $this->getForm()->setAction($action);
        $this->getForm()->prepareForEditing();
        return $this->getForm();
    }

    /**
     * @return void
     */
    protected function createForm()
    {
        //Do not use cache in forms with 'file' elements
        $this->getForm('Detail', true);
    }

    public function getModel(array $values = null): AbstractModel
    {
        if (!$this->model) {
            $this->model = new PictureModel($values);
        }

        return $this->model;
    }
}
