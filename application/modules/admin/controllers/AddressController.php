<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Mandragora\Controller\Action\AbstractAction;
use Mandragora\Service;

/**
 * Application's Address controller
 */
class Admin_AddressController extends AbstractAction
{
    /**
     * @var array
     */
    protected $validMethods = [
        'save' => ['method' => 'post'],
        'update' => ['method' => 'post'],
    ];

    /**
     * Initialize the service object
     *
     * @return void
     */
    public function init()
    {
        $this->service = Service::factory('Address');
        $this->service->setCacheManager($this->getCacheManager());
        $doctrine = $this->getInvokeArg('bootstrap')->getResource('doctrine');
        $this->service->setDoctrineManager($doctrine);
        $actions = $this->_helper->actionsBuilder($this->getRequest());
        $this->view->actions = $actions;
    }

    /**
     * @return void
     */
    public function createAction()
    {
        $params = array('action' => 'save', 'controller' => 'address');
        $action = $this->view->url($params, 'controllers', true);
        $addressForm = $this->service->getFormForCreating($action);
        $addressId = (int)$this->param('id');
        $addressForm->setIdValue($addressId);
        $this->view->addressForm = $addressForm;
        $this->view->addressId = $addressId;
    }

    /**
     * Save the address information in the data source
     *
     * @return void
     */
    public function saveAction()
    {
        $action = $this->view->url(array('action' => 'save'), 'controllers');
        $addressForm = $this->service->getFormForCreating($action);
        $this->service->setCities((string)$this->post('state'));
        if ($addressForm->isValid($this->post())) {
            $this->service->createAddress();
            $this->flash('success')->addMessage('address.created');
            $params = array('id' => (int)$this->post('id'));
            $this->redirectToRoute('show', $params);
        } else {
            $this->view->addressForm = $addressForm;
            $this->renderScript('address/create.phtml');
        }
    }

    /**
     * Show the address information
     *
     * @return void
     */
    public function showAction()
    {
        $address = $this->service->retrieveAddressById((int)$this->param('id'));
        if (!$address) {
            $this->flash('error')->addMessage('address.not.found');
            $this->redirectToRoute('list', array('page' => 1), 'property');
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
        $address = $this->service->retrieveAddressById((int)$this->param('id'));
        if (!$address) {
            $this->flash('error')->addMessage('address.not.found');
            $this->redirectToRoute('list', array('page' => 1), 'property');
        } else {
            $action = $this->view->url(array('action' => 'update'));
            $addressForm = $this->service->getFormForEditing($action);
            $addressForm->populate($address->toArray());
            $addressForm->getElement('state')
                        ->setValue($address->City->State->id);
            $stateId = $addressForm->getElement('state')->getValue();
            $this->service->setCities($stateId);
            $this->view->address = $address;
            $this->view->addressForm = $addressForm;
            $this->setGoogleMapActions();
        }
    }

    /**
     * Update the address in the data source
     *
     * @return void
     */
    public function updateAction()
    {
        $action = $this->view->url(array('action' => 'update'), 'controllers');
        $addressForm = $this->service->getFormForEditing($action);
        $addressValues = $this->post();
        $this->service->setCities((string)$this->post('state'));
        if ($addressForm->isValid($addressValues)) {
            $propertyId = (int)$this->param('id');
            $address = $this->service->retrieveAddressById($propertyId);
            if (!$address) {
                $this->flash('error')->addMessage('address.not.found');
                $params = array('id' => $propertyId);
                $this->redirectToRoute('show', $params, 'property');
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
                    $this->redirectToRoute('show', array('id' => $propertyId));
                }
            }
        } else {
            $values = $addressForm->getValues();
            $this->view->propertyId = (int)$this->param('id');
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
        $id = (int)$this->param('id');
        $address = $this->service->retrieveAddressById($id);
        if (!$address) {
            $this->flash('error')->addMessage('address.not.found');
            $this->redirectToRoute('show', array('id' => $id), 'property');
        } else {
            try {
                $this->service->deleteAddress($id);
                $this->flash('success')->addMessage('address.deleted');
                $this->redirectToRoute('show', array('id' => $id), 'property');
            } catch (Doctrine_Connection_Exception $ce) {
                if ($ce->getPortableCode() == Doctrine_Core::ERR_CONSTRAINT) {
                    $this->flash('error')
                    ->addMessage('address.constraintError');
                    $params = array('id' => $address->id);
                    $this->redirectToRoute('show', $params);
                }
            }
        }
    }

    /**
     * @return void
     */
    protected function setGoogleMapActions()
    {
        $address = $this->view->address;
        $param = array('id' => $address->id);
        $this->view->gMapActions =
            !is_null($address->latitude)
            ? array('gmap.action.edit' => $param, 'gmap.action.show' => $param)
            : array('gmap.action.create' => $param);
    }
}
