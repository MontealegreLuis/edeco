<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use App\Container\AddressContainer;
use Doctrine_Connection_Exception as ConnectionException;
use Doctrine_Core as DoctrineCore;
use Mandragora\Controller\Action\AbstractAction;

/**
 * CRUD for addresses.
 */
class Admin_AddressController extends AbstractAction
{
    /** @var array */
    protected $validMethods = [
        'save' => ['method' => 'post'],
        'update' => ['method' => 'post'],
    ];

    /**
     * @return void
     */
    public function init()
    {
        $this->service = (new AddressContainer())->getAddressService();
        $actions = $this->_helper->actionsBuilder($this->getRequest());
        $this->view->actions = $actions;
    }

    /**
     * @return void
     */
    public function createAction()
    {
        $addressForm = $this->service->getFormForCreating($this->view->url(
            ['action' => 'save', 'controller' => 'address'],
            'controllers',
            true
        ));
        $addressId = (int) $this->param('id');
        $addressForm->setIdValue($addressId);
        $this->view->addressForm = $addressForm;
        $this->view->addressId = $addressId;
    }

    /**
     * @return void
     */
    public function saveAction()
    {
        $addressForm = $this->service->getFormForCreating($this->view->url(
            ['action' => 'save'],
            'controllers'
        ));
        $this->service->setCities((string) $this->post('state'));
        if ($addressForm->isValid($this->post())) {
            $this->service->createAddress();
            $this->flash('success')->addMessage('address.created');
            $this->redirectToRoute('show', ['id' => (int) $this->post('id')]);
        } else {
            $this->view->addressForm = $addressForm;
            $this->renderScript('address/create.phtml');
        }
    }

    /**
     * @return void
     */
    public function showAction()
    {
        $address = $this->service->retrieveAddressById((int) $this->param('id'));
        if (!$address) {
            $this->flash('error')->addMessage('address.not.found');
            $this->redirectToRoute('list', ['page' => 1], 'property');
        } else {
            $this->view->address = $address;
            $this->setGoogleMapActions();
        }
    }

    /**
     * Show the form for editing a property's address
     *
     * @return void
     */
    public function editAction()
    {
        $address = $this->service->retrieveAddressById((int) $this->param('id'));
        if (!$address) {
            $this->flash('error')->addMessage('address.not.found');
            $this->redirectToRoute('list', ['page' => 1], 'property');
        } else {
            $action = $this->view->url(['action' => 'update']);
            $addressForm = $this->service->getFormForEditing($action);
            $addressForm->populate($address->toArray());
            $this->service->setCities($address->City->State->id);
            $this->view->address = $address;
            $this->view->addressForm = $addressForm;
            $this->setGoogleMapActions();
        }
    }

    /**
     * @return void
     */
    public function updateAction()
    {
        $addressForm = $this->service->getFormForEditing($this->view->url(
            ['action' => 'update'],
            'controllers'
        ));
        $addressValues = $this->post();
        $this->service->setCities((string) $this->post('state'));
        if ($addressForm->isValid($addressValues)) {
            $propertyId = (int) $this->param('id');
            $address = $this->service->retrieveAddressById($propertyId);
            if (!$address) {
                $this->flash('error')->addMessage('address.not.found');
                $this->redirectToRoute('show', ['id' => $propertyId], 'property');
            } else {
                if ($address->version > $addressValues['version']) {
                    $this->flash('error')->addMessage(
                        'address.optimistic.locking.failure'
                    );
                    $addressForm->populate($address->toArray());
                    $this->view->addressForm = $addressForm;
                    $this->renderScript('address/edit.phtml');
                } else {
                    $this->service->updateAddress();
                    $this->flash('success')->addMessage('address.updated');
                    $this->redirectToRoute('show', ['id' => $propertyId]);
                }
            }
        } else {
            $this->view->propertyId = (int) $this->param('id');
            $this->view->addressForm = $addressForm;
            $this->setGoogleMapActions();
            $this->renderScript('address/edit.phtml');
        }
    }

    /**
     * @return void
     */
    public function deleteAction()
    {
        $id = (int) $this->param('id');
        $address = $this->service->retrieveAddressById($id);
        if (!$address) {
            $this->flash('error')->addMessage('address.not.found');
            $this->redirectToRoute('show', ['id' => $id], 'property');
        } else {
            try {
                $this->service->deleteAddress($id);
                $this->flash('success')->addMessage('address.deleted');
                $this->redirectToRoute('show', ['id' => $id], 'property');
            } catch (ConnectionException $ce) {
                if ($ce->getPortableCode() === DoctrineCore::ERR_CONSTRAINT) {
                    $this->flash('error')->addMessage('address.constraintError');
                    $this->redirectToRoute('show', ['id' => $address->id]);
                }
            }
        }
    }

    /**
     * @return void
     */
    protected function setGoogleMapActions()
    {
        $param = ['id' => $this->view->address->id];
        $this->view->gMapActions =
            !is_null($this->view->address->latitude)
            ? ['gmap.action.edit' => $param, 'gmap.action.show' => $param]
            : ['gmap.action.create' => $param];
    }
}
