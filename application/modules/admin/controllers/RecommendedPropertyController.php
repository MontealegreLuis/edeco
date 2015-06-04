<?php
/**
 * RecommendedProperty controller
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
 * @package    App
 * @subpackage Controller
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2011
 * @version    SVN: $Id$
 */

/**
 * RecommendedProperty controller
 *
 * @package    App
 * @subpackage Controller
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2011
 * @version    SVN: $Id$
 */
class   Admin_RecommendedPropertyController
extends Mandragora_Controller_Action_Abstract
{
    /**
     * @var array
     */
    protected $validMethods = array(
        'save' => array('method' => 'post'),
        'update' => array('method' => 'post'),
    );

    /**
     * @return void
     */
    public function init()
    {
        $this->service = Mandragora_Service::factory('RecommendedProperty');
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
            $this->redirect('list', $params);
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
            $this->redirect('list', $params);
        } else {
            try {
                $this->service->deleteRecommendedProperty();
                $this->flash('success')
                     ->addMessage('recommendedProperty.deleted');
                $this->redirect('list', $params);
            } catch (Doctrine_Connection_Exception $ce) {
                if ($ce->getPortableCode() == Doctrine_Core::ERR_CONSTRAINT) {
                    $this->flash('error')
                         ->addMessage('recommendedProperty.constraintError');
                    $params = array('id' => $recommendedProperty->id);
                    $this->redirect('show', $params);
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
        $service = Mandragora_Service::factory('Property');
        $service->setCacheManager($this->getCacheManager());
        $doctrine = $this->getInvokeArg('bootstrap')->getResource('doctrine');
        $service->setDoctrineManager($doctrine);
        return $service->findRecommendedWebProperties($stateId, $propertyId);
    }

}