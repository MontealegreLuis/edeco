<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use App\Model\RecommendedProperty as RecommendedPropertyModel;
use Mandragora\Model\AbstractModel;
use Mandragora\Service\Crud\Doctrine\DoctrineCrud;
use App\Model\Collection\RecommendedProperty as RecommendedPropertyCollection;
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
     * @return RecommendedPropertyCollection
     */
    public function retrieveRecommendedPropertyCollection(int $pageNumber, int $id)
    {
        $this->init();
        $this->query = $this->getGateway()->getQueryFindAll($id);
        $items = (array) $this->getPaginator($pageNumber)->getCurrentItems();
        return new RecommendedPropertyCollection($items);
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
     * @return \Mandragora\Form\SecureForm
     */
    public function getFormForCreating($action)
    {
        $this->getForm('Detail')->setAction($action);
        $this->getForm()->prepareForCreating();
        return $this->getForm();
    }

    /**
     * @return RecommendedPropertyModel|false
     * @throws NoResultsFoundException
     */
    public function retrieveRecommendedPropertyBy(int $id, int $propertyId)
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
     * @return \Mandragora\Form\SecureForm
     */
    public function getFormForEditing($action)
    {
        $this->getForm('Detail')->setAction($action);
        $this->getForm()->prepareForEditing();
        return $this->getForm();
    }

    /**
     * @return void
     */
    public function deleteRecommendedProperty()
    {
        $this->init();
        $this->getGateway()->delete($this->getModel());
    }

    public function getModel(array $values = null): ?AbstractModel
    {
        if (!$this->model) {
            $this->model = new RecommendedPropertyModel($values);
        }

        return $this->model;
    }
}
