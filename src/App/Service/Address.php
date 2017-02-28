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
    /**
     * @return void
     * @throws \Doctrine_Exception
     */
    protected function init()
    {
        $this->openConnection();
        $this->decorateGateway();
    }

    /**
     * @return void
     * @throws \Doctrine_Exception
     */
    public function createAddress()
    {
        $this->init();

        /** @var \App\Form\Address\Detail $addressForm */
        $addressForm = $this->getForm();

        /** @var \App\Model\Address $address */
        $address = $this->getModel($addressForm->getValues());

        /** @var \App\Model\Gateway\Address $addressGateway */
        $addressGateway = $this->getGateway();

        $addressGateway->insert($address);
    }

    /**
     * @param string $action
     * @return \App\Form\Address\Detail
     */
    public function getFormForCreating($action)
    {
        /** @var \App\Form\Address\Detail $addressForm */
        $addressForm = $this->getForm();

        $addressForm->prepareForCreating();
        $addressForm->setAction($action);
        $addressForm->setStates($this->getStates());
        $addressForm->setNoCitiesOption();

        return $addressForm;
    }

    /**
     * @param string $action
     * @return \App\Form\Address\Detail
     */
    public function getFormForEditing($action)
    {
        /** @var \App\Form\Address\Detail $addressForm */
        $addressForm = $this->getForm();
        $addressForm->prepareForEditing();
        $addressForm->setAction($action);
        $addressForm->setStates($this->getStates());
        $addressForm->setNoCitiesOption();

        return $addressForm;
    }

    /**
     * @return array
     */
    protected function getStates()
    {
        /** @var \App\Service\State $stateService */
        $stateService = Service::factory('State');
        $stateService->setCacheManager($this->cacheManager);
        $stateService->setDoctrineManager($this->doctrineManager);

        return $stateService->retrieveAllStates();
    }

    /**
     * @return void
     */
    public function setCities(int $stateId)
    {
        /** @var \App\Form\Address\Detail $addressForm */
        $addressForm = $this->getForm();
        if (is_numeric($stateId)) {
            /** @var \App\Service\City $cityService */
            $cityService = Service::factory('City');
            $cityService->setCacheManager($this->cacheManager);
            $cityService->setDoctrineManager($this->doctrineManager);

            $options = [];
            foreach ($cityService->retrieveAllByStateId($stateId) as $city) {
                $options[$city['id']] = $city['name'];
            }
            $addressForm->setCities($options);
            $addressForm->setStateId($stateId);
        } else {
            $addressForm->getElement('cityId')->removeValidator('InArray');
        }
    }

    /**
     * @return AddressModel|false
     * @throws \Doctrine_Exception
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
     * @throws \Doctrine_Exception
     */
    public function updateAddress()
    {
        $this->init();

        /** @var \App\Model\Address $address */
        $address = $this->getModel();

        /** @var \App\Form\Address\Detail $addressForm */
        $addressForm = $this->getForm();
        $address->fromArray($addressForm->getValues());

        /** @var \App\Model\Gateway\Address $addressGateway */
        $addressGateway = $this->getGateway();
        $addressGateway->update($address);
    }

    /**
     * @return void
     * @throws \Doctrine_Exception
     */
    public function deleteAddress()
    {
        $this->init();

        /** @var \App\Model\Gateway\Address $addressGateway */
        $addressGateway = $this->getGateway();

        /** @var \App\Model\Address $address */
        $address = $this->getModel();

        $addressGateway->delete($address);
    }

    public function getModel(array $values = null): AbstractModel
    {
        if (!$this->model) {
            $this->model = new AddressModel($values);
        }

        return $this->model;
    }
}
