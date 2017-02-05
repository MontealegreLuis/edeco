<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Mandragora\Controller\Action\AbstractAction;
use Mandragora\Service;

/**
 * Category controller
 */
class Admin_CategoryController extends AbstractAction
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
        $this->service = Service::factory('Category');
        $this->service->setCacheManager($this->getCacheManager());
        $doctrine = $this->getInvokeArg('bootstrap')->getResource('doctrine');
        $doctrine->setUp();
        $this->service->setDoctrineManager($doctrine);
        $actions = $this->_helper->actionsBuilder($this->getRequest());
        $this->view->actions = $actions;
    }

    /**
     * @return void
     */
    public function listAction()
    {
        $this->service->setPaginatorOptions($this->getAppSetting('paginator'));
        $page = (int)$this->param($this->view->translate('page'), 1);
        $collection = $this->service->retrieveCategoryCollection($page);
        $this->view->collection = $collection;
        $this->view->paginator = $this->service->getPaginator($page);
    }

    /**
     * @return void
     */
    public function createAction()
    {
        $action = $this->view->url(array('action' => 'save'), 'controllers');
        $this->view->categoryForm = $this->service->getFormForCreating($action);
    }

    /**
     *  @retun void
     */
    public function saveAction()
    {
        $action = $this->view->url(array('action' => 'save'), 'controllers');
        $categoryForm = $this->service->getFormForCreating($action);
        $this->service->openConnection();
        if ($categoryForm->isValid($this->post())) {
            $this->service->createCategory();
            $this->flash('success')->addMessage('category.created');
            $params = array('id' => $this->service->getModel()->id);
            $this->redirectToRoute('show', $params);
        } else {
            $this->view->categoryForm = $categoryForm;
            $this->renderScript('category/create.phtml');
        }
    }

    /**
     * @return void
     */
    public function showAction()
    {
        $id = (int)$this->param('id');
        $category = $this->service->retrieveCategoryById($id);
        if (!$category) {
            $this->flash('error')->addMessage('category.not.found');
            $this->redirectToRoute('list', array($this->view->translate('page') => 1));
        } else {
            $this->view->category = $category;
        }
    }

    /**
     * @return void
     */
    public function editAction()
    {
        $id = (int)$this->param('id');
        $category = $this->service->retrieveCategoryById($id);
        if (!$category) {
            $this->flash('error')->addMessage('category.not.found');
            $this->redirectToRoute('list', array('page' => 1));
        } else {
            $action = $this->view->url(array('action' => 'update'));
            $categoryForm = $this->service->getFormForEditing($action);
            $categoryForm->populate($category->toArray());
            $this->view->category = $category;
            $this->view->categoryForm = $categoryForm;
        }
    }

    /**
     * @return void
     */
    public function updateAction()
    {
        $action = $this->view->url(['action' => 'update'], 'controllers');
        $categoryForm = $this->service->getFormForEditing($action);
        $values = $this->post();
        if ($categoryForm->isValid($values)) {
            $category = $this->service->retrieveCategoryById((int) $this->param('id'));
            if (!$category) {
                $this->flash('error')->addMessage('category.not.found');
                $this->redirectToRoute('list', ['page' => 1]);
            } else {
                if ($category->version > $values['version']) {
                    $this->flash('error')
                         ->addMessage('category.optimistic.locking.failure');
                    $categoryForm->populate($category->toArray());
                    $this->view->categoryForm = $categoryForm;
                    $this->renderScript('category/edit.phtml');
                } else {
                    $this->service->updateCategory();
                    $this->flash('success')->addMessage('category.updated');
                    $this->redirectToRoute('show', ['id' => $category->id]);
                }
            }
        } else {
            $values = $categoryForm->getValues();
            $this->view->category = $this->service->getModel($values);
            $this->view->categoryForm = $categoryForm;
            $this->renderScript('category/edit.phtml');
        }
    }

    /**
     * @return void
     */
    public function deleteAction()
    {
        $id = (int)$this->param('id');
        $category = $this->service->retrieveCategoryById($id);
        if (!$category) {
            $this->flash('error')->addMessage('category.not.found');
            $this->redirectToRoute(
                'list', array($this->view->translate('page') => 1)
            );
        } else {
            try {
                $this->service->deleteCategory($id);
                $this->flash('success')->addMessage('category.deleted');
                $params = array($this->view->translate('page') => 1);
                $this->redirectToRoute('list', $params);
            } catch (Doctrine_Connection_Exception $ce) {
                if ($ce->getPortableCode() == Doctrine_Core::ERR_CONSTRAINT) {
                    $this->flash('error')
                         ->addMessage('category.constraintError');
                    $params = array('id' => $category->id);
                    $this->redirectToRoute('show', $params);
                }
            }
        }
    }
}
