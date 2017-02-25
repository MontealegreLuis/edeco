<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use App\Model\Address as AddressModel;
use Mandragora\Model\AbstractModel;
use Mandragora\Service\Crud\Doctrine\DoctrineCrud;
use Mandragora\Service;
use Mandragora\Gateway\NoResultsFoundException;

/**
 * Service class for Address model
 */
class Address extends DoctrineCrud
{
    /** @var \App\Form\GoogleMap\Detail */
    protected $googleForm;

	/**
     * @return void
     */
    protected function init()
    {
        $this->openConnection();
        $this->decorateGateway();
    }

    /**
    * @return void
    */
    public function createAddress()
    {
        $this->init();
        $this->getModel($this->getForm()->getValues());
        $this->getGateway()->insert($this->getModel());
    }

    /**
     * @param string $action
     * @return \App\Form\Address\Detail
     */
    public function getFormForCreating($action)
    {
        $this->getForm()->prepareForCreating();
        $this->setStates();
        $this->getForm()->setAction($action);
        return $this->getForm();
    }

    /**
     * @param string $action
     * @return \App\Form\Address\Detail
     */
    public function getFormForEditing($action)
    {
        $this->getForm()->prepareForEditing();
        $this->setStates();
        $this->getForm()->setAction($action);
        return $this->getForm();
    }

    /**
     * @return void
     */
    protected function setStates()
    {
        $stateService = Service::factory('State');
        $stateService->setCacheManager($this->cacheManager);
        $stateService->setDoctrineManager($this->doctrineManager);
        $states = $stateService->retrieveAllStates();
        $this->getForm()->setStates($states);
        $this->getForm()->setNoCitiesOption();
    }

    /**
     * @param int $stateId
     * @return boolean
     */
    public function setCities($stateId)
    {
        if (is_numeric($stateId)) {
            $cityService = Service::factory('City');
            $cityService->setCacheManager($this->cacheManager);
            $cityService->setDoctrineManager($this->doctrineManager);
            $cities = $cityService->retrieveAllByStateId((int) $stateId);
            $options = [];
            foreach ($cities as $city) {
                $options[$city['id']] = $city['name'];
            }
            $this->getForm()->setCities($options);
            $this->getForm()->setStateId($stateId);
        } else {
            $this->getForm()->getElement('cityId')->removeValidator('InArray');
        }
    }

    /**
     * @param int $id
     * @return AddressModel|false
     */
    public function retrieveAddressById(int $id)
    {
        try {
            $this->init();
            return $this->getModel($this->getGateway()->findOneById($id));
        } catch (NoResultsFoundException $nrfe) {
            return false;
        }
    }

    /**
     * @return void
     */
    public function updateAddress()
    {
        $this->init();
        $this->getModel()->fromArray($this->getForm()->getValues());
        $this->getGateway()->update($this->getModel());
    }

    /**
     * @return void
     */
    public function deleteAddress()
    {
        $this->init();
        $this->getGateway()->delete($this->getModel());
    }

    public function getModel(array $values = null): AbstractModel
    {
        if ($this->model) {
            $this->model = new AddressModel($values);
        }

        return $this->model;
    }
}
