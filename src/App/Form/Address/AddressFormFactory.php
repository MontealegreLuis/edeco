<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Form\Address;

use App\Model\Gateway\Cache\City;
use App\Model\Gateway\Cache\State;
use Mandragora\FormFactory;

class AddressFormFactory
{
    /** @var  State */
    private $stateGateway;

    /** @var City */
    private $cityGateway;

    /** @var FormFactory */
    private $factory;

    public function __construct(
        State $stateGateway,
        City $cityGateway,
        FormFactory $factory
    )
    {
        $this->stateGateway = $stateGateway;
        $this->cityGateway = $cityGateway;
        $this->factory = $factory;
    }

    public function forCreating(string $action, array $input): Detail
    {
        $form = $this->addressForm();
        $form->prepareForCreating();
        $form->setAction($action);
        $form->setStates($this->stateGateway->findAll());
        $form->setNoCitiesOption();
        $form->setIdValue($input['id']);
        $this->setCities($form, $input['state'] ?? null);

        return $form;
    }

    public function forEditing(string $action, array $input): Detail
    {
        $form = $this->addressForm();
        $form->prepareForEditing();
        $form->setAction($action);
        $form->setStates($this->stateGateway->findAll());
        $form->setIdValue($input['id']);
        $form->populate($input);
        $this->setCities($form, $input['state'] ?? $input['City']['State']['id']);

        return $form;
    }

    private function setCities(Detail $form, ?int $stateId): void
    {
        if ($stateId !== null) {
            $form->setStateId($stateId);
            $form->setCities($this->cityGateway->findAllByStateId($stateId));
            return;
        }

        $form->removeCityValidator();
    }

    private function addressForm(): Detail
    {
        $form = new Detail();
        $this->factory->configure($form);

        return $form;
    }
}
