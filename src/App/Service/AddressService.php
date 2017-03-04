<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use App\Form\Address\Detail;
use App\Model\Address;
use App\Model\Gateway\AddressGateway;
use App\Model\Gateway\Cache\City;
use App\Model\Gateway\Cache\State;
use Mandragora\Gateway\NoResultsFoundException;

class AddressService
{
    /** @var AddressGateway */
    private $gateway;

    /** @var Detail */
    private $form;

    /** @var City */
    private $cityGateway;

    /** @var State */
    private $stateGateway;

    public function __construct(
        AddressGateway $gateway,
        Detail $form,
        City $cityGateway,
        State $stateGateway
    ) {
        $this->gateway = $gateway;
        $this->form = $form;
        $this->cityGateway = $cityGateway;
        $this->stateGateway = $stateGateway;
    }

    /**
     * @throws \Doctrine_Exception
     */
    public function createAddress(): void
    {
        $this->gateway->insert(new Address($this->form->getValues()));
    }

    public function getFormForCreating(string $action): Detail
    {
        $this->form->prepareForCreating();
        $this->form->setAction($action);
        $this->form->setStates($this->stateGateway->findAll());
        $this->form->setNoCitiesOption();

        return $this->form;
    }

    public function getFormForEditing(string $action): Detail
    {
        $this->form->prepareForEditing();
        $this->form->setAction($action);
        $this->form->setStates($this->stateGateway->findAll());
        $this->form->setNoCitiesOption();

        return $this->form;
    }

    public function setCities(int $stateId): void
    {
        if (is_numeric($stateId)) {
            $options = [];
            foreach ($this->cityGateway->findAllByStateId($stateId) as $city) {
                $options[$city['id']] = $city['name'];
            }
            $this->form->setCities($options);
            $this->form->setStateId($stateId);
        } else {
            $this->form->getElement('cityId')->removeValidator('InArray');
        }
    }

    /**
     * @return Address|false
     * @throws \Doctrine_Exception
     */
    public function retrieveAddressById(int $id)
    {
        try {
            return new Address($this->gateway->findOneById($id));
        } catch (NoResultsFoundException $nrfe) {
            return false;
        }
    }

    /**
     * @throws \Doctrine_Exception
     */
    public function updateAddress(): void
    {
        $this->gateway->update(new Address($this->form->getValues()));
    }

    /**
     * @throws \Mandragora\Gateway\NoResultsFoundException
     * @throws \Doctrine_Exception
     */
    public function deleteAddress(int $id): void
    {
        $address = $this->gateway->findOneById($id);

        $this->gateway->delete(new Address($address));
    }
}
