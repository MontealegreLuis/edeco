<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use Mandragora\Service\Crud\Doctrine\DoctrineCrud;
use App\Model\Collection\RecommendedProperty as AppModelCollectionRecommendedProperty;
use Mandragora\Gateway\NoResultsFoundException;

/**
 * Service class for RecommendedProperty model
 */
class RecommendedProperty extends DoctrineCrud
{
    /**
     * @return void
     */
    protected function init()
    {
        $this->openConnection();
        $this->decorateGateway();
    }

    /**
     * @param int $pageNumber
     * @param int $id
     * @return App_Model_Collection_RecommendedProperty
     */
    public function retrieveRecommendedPropertyCollection($pageNumber, $id)
    {
        $this->init();
        $this->query = $this->getGateway()->getQueryFindAll($id);
        $items = (array)$this->getPaginator($pageNumber)->getCurrentItems();
        return new AppModelCollectionRecommendedProperty($items);
    }

    /**
     * @return void
     */
    public function createRecommendedProperty()
    {
        $this->init();
        $properties = explode(',', $this->getForm()->getValue('properties'));
        $propertyId = $this->getForm()->getValue('id');
        $this->getGateway()->insertProperties($properties, $propertyId);
    }

    /**
     * @param string $action
     * @return Mandragora_Form_Abstract
     */
    public function getFormForCreating($action)
    {
        $this->getForm('Detail')->setAction($action);
        $this->getForm()->prepareForCreating();
        return $this->getForm();
    }

    /**
     * @param int $id
     * @param int $propertyId
     * @return App_Model_RecommendedProperty
     * @throws Mandragora_Gateway_NoResultsFoundException
     */
    public function retrieveRecommendedPropertyBy($id, $propertyId)
    {
        try {
            $this->init();
            $values = $this->getGateway()
                           ->findOneBy((int)$id, (int)$propertyId);
            return $this->getModel($values);
        } catch (NoResultsFoundException $nrfe) {
            return false;
        }
    }

    /**
     * @param string $action
     * @return Mandragora_Form_Abstract
     */
    public function getFormForEditing($action)
    {
        $this->getForm('Detail')->setAction($action);
        $this->getForm()->prepareForEditing();
        return $this->getForm();
    }

    /**
     * @param int $id
     * @return void
     */
    public function deleteRecommendedProperty()
    {
        $this->init();
        $this->getGateway()->delete($this->getModel());
    }
}
