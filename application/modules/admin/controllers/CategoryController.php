<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Doctrine_Connection_Exception as ConnectionException;
use Doctrine_Core as DoctrineCore;
use Mandragora\Controller\Action\AbstractAction;
use Mandragora\Service;

/**
 * CRUD for categories
 */
class Admin_CategoryController extends AbstractAction
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
        $page = (int) $this->param($this->view->translate('page'), 1);
        $collection = $this->service->retrieveCategoryCollection($page);
        $this->view->collection = $collection;
        $this->view->paginator = $this->service->getPaginator($page);
    }

    /**
     * @return void
     */
    public function createAction()
    {
        $this->view->categoryForm = $this->service->getFormForCreating(
            $this->view->url(['action' => 'save'], 'controllers')
        );
    }

    /**
     *  @retun void
     */
    public function saveAction()
    {
        $categoryForm = $this->service->getFormForCreating($this->view->url(
            ['action' => 'save'], 'controllers'
        ));
        $this->service->openConnection();
        if ($categoryForm->isValid($this->post())) {
            $this->service->createCategory();
            $this->flash('success')->addMessage('category.created');
            $this->redirectToRoute('show', ['id' => $this->service->getModel()->id]);
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
        $category = $this->service->retrieveCategoryById((int) $this->param('id'));
        if (!$category) {
            $this->flash('error')->addMessage('category.not.found');
            $this->redirectToRoute('list', [$this->view->translate('page') => 1]);
        } else {
            $this->view->category = $category;
        }
    }

    /**
     * @return void
     */
    public function editAction()
    {
        $category = $this->service->retrieveCategoryById((int) $this->param('id'));
        if (!$category) {
            $this->flash('error')->addMessage('category.not.found');
            $this->redirectToRoute('list', ['page' => 1]);
        } else {
            $categoryForm = $this->service->getFormForEditing($this->view->url(
                ['action' => 'update']
            ));
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
        $categoryForm = $this->service->getFormForEditing($this->view->url(
            ['action' => 'update'], 'controllers'
        ));
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
        $id = (int) $this->param('id');
        $category = $this->service->retrieveCategoryById($id);
        if (!$category) {
            $this->flash('error')->addMessage('category.not.found');
            $this->redirectToRoute(
                'list', [$this->view->translate('page') => 1]
            );
        } else {
            try {
                $this->service->deleteCategory($id);
                $this->flash('success')->addMessage('category.deleted');
                $this->redirectToRoute('list', [$this->view->translate('page') => 1]);
            } catch (ConnectionException $ce) {
                if ($ce->getPortableCode() === DoctrineCore::ERR_CONSTRAINT) {
                    $this->flash('error')
                         ->addMessage('category.constraintError');
                    $this->redirectToRoute('show', ['id' => $category->id]);
                }
            }
        }
    }
}
