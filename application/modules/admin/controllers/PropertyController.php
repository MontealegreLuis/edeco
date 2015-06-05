<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright  Mandrágora Web-Based Systems 2010-2015 (http://www.mandragora-web-systems.com)
 */

/**
 * Property' Controller for Edeco Panel Application
 *
 * @author     LNJ <lemuel.nonoal@mandragora-web-systems.com>
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 */
class Admin_PropertyController extends Mandragora_Controller_Action_Abstract
{
    /**
     * @var array
     */
    protected $validMethods = array(
        'save' => array('method' => 'post'),
        'update' => array('method' => 'post'),
        'search' => array('method' => 'post'),
    );

    /**
     * Initialize the service object and build the breadcrumbs
     *
     * @return void
     */
    public function init()
    {
        $this->service = Mandragora_Service::factory('Property');
        $this->service->setCacheManager($this->getCacheManager());
        $doctrine = $this->getInvokeArg('bootstrap')->getResource('doctrine');
        $this->service->setDoctrineManager($doctrine);
        $actions = $this->_helper->actionsBuilder($this->getRequest());
        $this->view->actions = $actions;
    }

    /**
     * Show all the available properties
     *
     * @return void
     */
    public function listAction()
    {
        //Setup search properties form
        $searchForm = $this->service->getForm('Search');
        $action = $this->view->url(array('action'=>'search'), 'controllers');
        $searchForm->setAction($action);
        $this->view->searchForm = $searchForm;
        //Setup properties list
        $this->service->setPaginatorOptions($this->getAppSetting('paginator'));
        $page = (int)$this->param($this->view->translate('page'), 1);
        $collection = $this->service->retrievePropertyCollection($page);
        $this->view->collection = $collection;
        $this->view->paginator = $this->service->getPaginator($page);
    }

    /**
     * Show the form for creating a new property
     *
     * @return void
     */
    public function createAction()
    {
        $action = $this->view->url(array('action' => 'save'), 'controllers');
        $this->view->propertyForm = $this->service->getFormForCreating($action);
    }

    /**
     * Save the property information to the data source or show the
     * corresponding error messages if needed
     *
     *  @retun void
     */
    public function saveAction()
    {
        $action = $this->view->url(array('action' => 'save'), 'controllers');
        $propertyForm = $this->service->getFormForCreating($action);
        $this->service->openConnection();
        if ($propertyForm->isValid($this->post())) {
            $this->service->createProperty();
            $this->flash('success')->addMessage('property.created');
            $params = array('id' => $this->service->getModel()->id);
            $this->redirectToRoute('show', $params);
        } else {
            $this->view->propertyForm = $propertyForm;
            $this->renderScript('property/create.phtml');
        }
    }

    /**
     * Show the property information, including the address and Google maps info
     *
     * @return void
     */
    public function showAction()
    {
        $id = (int)$this->param('id');
        $property = $this->service->retrievePropertyById($id);
        if (!$property) {
            $this->flash('error')->addMessage('property.not.found');
            $this->redirectToRoute('list', array($this->view->translate('page') => 1));
        } else {
            if (!$property->Address) {
                $page = $this->view->actions->findOneByLabel('address.action.show');
                $this->view->actions->removePage($page);
            } else {
                $page = $this->view->actions->findOneByLabel('address.action.create');
                $this->view->actions->removePage($page);
                $this->view->stateId = $property->Address->City->State->id;
            }
            if (!$property->showOnWeb->getValue()) {
                $page = $this->view->actions->findOneByLabel('recommendedProperty.action.list');
                $this->view->actions->removePage($page);
            }
            $property->prepareForShowing();
            $this->view->property = $property;
        }
    }

    /**
     * Show the form for editing the current property
     *
     * @return void
     */
    public function editAction()
    {
        $id = (int)$this->param('id');
        $property = $this->service->retrievePropertyById($id);
        if (!$property) {
            $this->flash('error')->addMessage('property.not.found');
            $this->redirectToRoute('list', array('page' => 1));
        } else {
            $action = $this->view->url(array('action' => 'update'));
            $propertyForm = $this->service->getFormForEditing($action);
            $propertyForm->populate($property->toArray());
            $this->view->property = $property;
            $this->view->propertyForm = $propertyForm;
        }
    }

    /**
     * Update a property in the database
     *
     * @return void
     */
    public function updateAction()
    {
        $action = $this->view->url(array('action' => 'update'), 'controllers');
        $propertyForm = $this->service->getFormForEditing($action);
        $propertyValues = $this->post();
        if ($propertyForm->isValid($propertyValues)) {
            $id = (int)$this->param('id');
            $property = $this->service->retrievePropertyById($id);
            if (!$property) {
                $this->flash('error')->addMessage('property.not.found');
                $this->redirectToRoute('list', array('page' => 1));
            } else {
                if ($property->version > $propertyValues['version']) {
                    $this->flash('error')->addMessage(
                        'property.optimistic.locking.failure'
                    );
                    $propertyForm->populate($property->toArray());
                    $this->view->propertyForm = $propertyForm;
                    $this->renderScript('property/edit.phtml');
                } else {
                    $this->service->updateProperty();
                    $this->flash('success')->addMessage('property.updated');
                    $this->redirectToRoute('show', array('id' => $property->id));
                }
            }
        } else {
            $values = $propertyForm->getValues();
            $this->view->property = new Edeco_Model_Property($values);
            $this->view->propertyForm = $propertyForm;
            $this->renderScript('property/edit.phtml');
        }
    }

    /**
     * Changes the value of the field active to zero
     *
     * @return void
     */
    public function deleteAction()
    {
        $id = (int)$this->param('id');
        $property = $this->service->retrievePropertyById($id);
        if (!$property) {
            $this->flash('error')->addMessage('property.not.found');
            $this->redirectToRoute(
                'list', array($this->view->translate('page') => 1)
            );
        } else {
            try {
                $params = array($this->view->translate('page') => 1);
                $this->service->deleteProperty($id);
                $this->flash('success')->addMessage('property.deleted');
                $this->redirectToRoute('list', $params);
            } catch (Doctrine_Connection_Exception $ce) {
                if ($ce->getPortableCode() == Doctrine_Core::ERR_CONSTRAINT) {
                    $this->flash('error')
                         ->addMessage('property.constraintError');
                    $params = array('id' => $property->id);
                    $this->redirectToRoute('show', $params);
                }
            }
        }
    }

    /**
     * Quick search of properties by name
     *
     * @return void
     */
    public function searchAction()
    {
    	$searchForm = $this->service->getForm('Search');
    	$propertyName = $this->post();
    	$this->service->openConnection();
    	if ($searchForm->isValid($propertyName)) {
    		$this->view->properties = $this->service->findPropertiesByNameLike(
                $this->post('name')
            );
    	} else {
    	    $params = array($this->view->translate('page') => 1);
    	    $this->redirectToRoute('list', $params);
    	}
    }

}