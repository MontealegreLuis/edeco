<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use App\Form\Address\AddressFormFactory;
use App\Form\Address\Detail;
use App\Model\Address;
use App\Model\Gateway\AddressGateway;
use Mandragora\Gateway\NoResultsFoundException;

class AddressService
{
    /** @var AddressGateway */
    private $gateway;

    /** @var Detail */
    private $form;

    /** @var AddressFormFactory */
    private $formFactory;

    public function __construct(
        AddressGateway $gateway,
        AddressFormFactory $form
    ) {
        $this->gateway = $gateway;
        $this->formFactory = $form;
    }

    /**
     * @throws \Doctrine_Exception
     */
    public function createAddress(): void
    {
        $this->gateway->insert(new Address($this->form->getValues()));
    }

    public function getFormForCreating(string $action, array $input): Detail
    {
        $this->form = $this->formFactory->forCreating($action, $input);

        return $this->form;
    }

    public function getFormForEditing(string $action, array $input): Detail
    {
        $this->form = $this->formFactory->forEditing($action, $input);

        return $this->form;
    }

    /**
     * @throws \Doctrine_Exception
     */
    public function retrieveAddressById(int $id): ?Address
    {
        try {
            return new Address($this->gateway->findOneById($id));
        } catch (NoResultsFoundException $notFound) {
            return null;
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
     * @throws NoResultsFoundException
     * @throws \Doctrine_Exception
     */
    public function deleteAddress(int $id): void
    {
        $this->gateway->delete(new Address($this->gateway->findOneById($id)));
    }
}
