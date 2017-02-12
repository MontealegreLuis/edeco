<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use Mandragora\Service\Crud\Doctrine\DoctrineCrud;
use Mandragora\Service;
use Mandragora\Gateway\NoResultsFoundException;

/**
 * Service class for Address model
 */
class Address extends DoctrineCrud
{
    /**
     * @var App_Form_Address_GoogleMap
     */
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
     * @param string $formName
     * @return void
     */
    protected function setStates()
    {
        $stateService = Service::factory('State');
        $stateService->setCacheManager($this->cacheManager);
        $stateService->setDoctrineManager($this->doctrineManager);
        $states = $stateService->retrieveAllStates();
        $this->getForm()->setStates($states);
        //Add default option to cityId select element
        $options = array('' => 'form.emptyOption');
        $this->getForm()->getElement('cityId')->setMultioptions($options);
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
     * @param int $propertyId
     * @return void
     */
    public function retrieveAddressById($id)
    {
        try {
            $this->init();
            $values = $this->getGateway()->findOneById((int)$id);
            return $this->getModel($values);
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
    * @param int $id
    * @return void
    */
    public function deleteAddress($id)
    {
        $this->init();
        $this->getGateway()->delete($this->getModel());
    }
}
