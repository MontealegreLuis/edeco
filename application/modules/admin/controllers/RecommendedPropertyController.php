<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Mandragora\Controller\Action\AbstractAction;
use Mandragora\Service;

/**
 * RecommendedProperty controller
 */
class   Admin_RecommendedPropertyController extends AbstractAction
{
    /**
     * @var array
     */
    protected $validMethods = [
        'save' => ['method' => 'post'],
        'update' => ['method' => 'post'],
    ];

    /**
     * @return void
     */
    public function init()
    {
        $this->service = Service::factory('RecommendedProperty');
        $this->service->setCacheManager($this->getCacheManager());
        $doctrine = $this->getInvokeArg('bootstrap')->getResource('doctrine');
        $this->service->setDoctrineManager($doctrine);
        $actions = $this->_helper->actionsBuilder($this->getRequest());
        $this->view->actions = $actions;
    }

    /**
     * @return void
     */
    public function listAction()
    {
        $propertyId = (int)$this->param($this->view->translate('propertyId'));
        $stateId = (int)$this->param($this->view->translate('stateId'));
        $this->service->setPaginatorOptions($this->getAppSetting('paginator'));
        $page = (int)$this->param($this->view->translate('page'), 1);
        $collection = $this->service->retrieveRecommendedPropertyCollection(
            $page, $propertyId
        );
        $this->view->collection = $collection;
        $this->view->paginator = $this->service->getPaginator($page);
        $this->view->stateId = $stateId;
        $this->view->propertyId = $propertyId;
    }

    /**
     * @return void
     */
    public function createAction()
    {
        $action = $this->view->url(array('action' => 'save'), 'controllers');
        $propertyId = $this->param($this->view->translate('propertyId'));
        $stateId = $this->param($this->view->translate('stateId'));
        $form = $this->service->getFormForCreating($action);
        $form->setPropertyId($propertyId);
        $this->view->recommendedPropertyForm = $form;
        $this->view->stateId = $stateId;
        $this->view->propertyId = $propertyId;
        $this->view->properties = $this->getRecommendedProperties(
            $stateId, $propertyId
        );
    }

    /**
     *  @retun void
     */
    public function saveAction()
    {
        $action = $this->view->url(array('action' => 'save'), 'controllers');
        $recommendedPropertyForm = $this->service->getFormForCreating($action);
        if ($recommendedPropertyForm->isValid($this->post())) {
            $this->service->createRecommendedProperty();
            $this->flash('success')->addMessage('recommendedProperty.created');
            $stateId = (int)$this->param($this->view->translate('stateId'));
            $params = array(
            	$this->view->translate('page') => 1,
            	$this->view->translate('propertyId') => $this->post('id'),
            	$this->view->translate('stateId') => $stateId,
            );
            $this->redirectToRoute('list', $params);
        } else {
            $this->view->recommendedPropertyForm = $recommendedPropertyForm;
            $this->renderScript('recommended-Property/create.phtml');
        }
    }

    /**
     * @return void
     */
    public function deleteAction()
    {
        $id = (int)$this->param('id');
        $propertyId = (int)$this->param($this->view->translate('propertyId'));
        $stateId = (int)$this->param($this->view->translate('stateId'));
        $recommendedProperty = $this->service->retrieveRecommendedPropertyBy(
            $id, $propertyId
        );
        $params = array(
            $this->view->translate('page') => 1,
            $this->view->translate('propertyId') => $id,
            $this->view->translate('stateId') => $stateId,
        );
        if (!$recommendedProperty) {
            $this->flash('error')->addMessage('recommendedProperty.not.found');
            $this->redirectToRoute('list', $params);
        } else {
            try {
                $this->service->deleteRecommendedProperty();
                $this->flash('success')
                     ->addMessage('recommendedProperty.deleted');
                $this->redirectToRoute('list', $params);
            } catch (Doctrine_Connection_Exception $ce) {
                if ($ce->getPortableCode() == Doctrine_Core::ERR_CONSTRAINT) {
                    $this->flash('error')
                         ->addMessage('recommendedProperty.constraintError');
                    $params = array('id' => $recommendedProperty->id);
                    $this->redirectToRoute('show', $params);
                }
            }
        }
    }

    /**
     * Search a property located near by the current property
     *
     * @param int $stateId
     * @param int $propertyId
     * @return App_Model_Collection_Property
     */
    protected function getRecommendedProperties($stateId, $propertyId)
    {
        $service = Service::factory('Property');
        $service->setCacheManager($this->getCacheManager());
        $doctrine = $this->getInvokeArg('bootstrap')->getResource('doctrine');
        $service->setDoctrineManager($doctrine);
        return $service->findRecommendedWebProperties($stateId, $propertyId);
    }
}
