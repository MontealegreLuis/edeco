<?php
/**
 * Application's Address controller
 *
 * PHP version 5
 *
 * LICENSE: Redistribution and use of this file in source and binary forms,
 * with or without modification, is not permitted under any circumstance
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   Application
 * @package    Edeco
 * @subpackage Controller
 * @author     LNJ <lemuel.nonoal@mandragora-web-systems.com>
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Application's Address controller
 *
 * @category   Application
 * @package    Edeco
 * @subpackage Controller
 * @author     LNJ <lemuel.nonoal@mandragora-web-systems.com>
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class Admin_AddressController extends Mandragora_Controller_Action_Abstract
{
    /**
     * @var array
     */
    protected $validMethods = array(
        'save' => array('method' => 'post'),
        'update' => array('method' => 'post'),
    );

    /**
     * Initialize the service object
     *
     * @return void
     */
    public function init()
    {
        $this->service = Mandragora_Service::factory('Address');
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
            $this->redirect('show', $params);
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
            $this->redirect('list', array('page' => 1), 'property');
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
            $this->redirect('list', array('page' => 1), 'property');
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
                $this->redirect('show', $params, 'property');
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
                    $this->redirect('show', array('id' => $propertyId));
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
            $this->redirect('show', array('id' => $id), 'property');
        } else {
            try {
                $this->service->deleteAddress($id);
                $this->flash('success')->addMessage('address.deleted');
                $this->redirect('show', array('id' => $id), 'property');
            } catch (Doctrine_Connection_Exception $ce) {
                if ($ce->getPortableCode() == Doctrine_Core::ERR_CONSTRAINT) {
                    $this->flash('error')
                    ->addMessage('address.constraintError');
                    $params = array('id' => $address->id);
                    $this->redirect('show', $params);
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