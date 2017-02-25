<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use App\Model\Category as CategoryModel;
use Mandragora\Model\AbstractModel;
use Mandragora\Service\Crud\Doctrine\DoctrineCrud;
use App\Model\Collection\Category as AppModelCollectionCategory;
use Mandragora\Gateway\NoResultsFoundException;
use Zend_Navigation as Navigation;
use Zend_Navigation_Page as Page;

/**
 * Service class for Category model
 */
class Category extends DoctrineCrud
{
    /**
     * @return void
     */
    protected function init()
    {
        $this->openConnection();
        $this->decorateGateway();
    }

    /**
     * @return \App\Model\Collection\Category
     */
    public function retrieveCategoryCollection(int $pageNumber)
    {
        $this->init();
        $this->query = $this->getGateway()->getQueryFindAll();
        $items = (array) $this->getPaginator($pageNumber)->getCurrentItems();
        return new AppModelCollectionCategory($items);
    }

    /**
     * @return array
     */
    public function retrieveAllCategories()
    {
        $this->init();
        $this->query = $this->getGateway()->getQueryFindAll();
        return $this->query->fetchArray();
    }

    /**
    * @return CategoryModel | boolean
    */
    public function retrieveCategoryByUrl(string $url)
    {
        $this->init();
        try {
            $categoryValues = $this->getGateway()->findOneByUrl($url);
            return $this->getModel($categoryValues);
        } catch (NoResultsFoundException $nrfe) {
            return false;
        }
    }

    /**
     * @return void
     */
    public function createCategory()
    {
        $this->init();
        $this->getModel($this->getForm()->getValues());
        $this->getModel()->url = $this->getModel()->name;
        $this->getGateway()->insert($this->getModel());
    }

    /**
     * @param string $action
     * @return \Mandragora\Form\SecureForm
     */
    public function getFormForCreating($action)
    {
        $this->getForm('Detail')->setAction($action);
        $this->getForm()->prepareForCreating();
        return $this->getForm();
    }

    /**
     * @return CategoryModel
     * @throws NoResultsFoundException
     */
    public function retrieveCategoryById(int $id)
    {
        try {
            $this->init();
            $values = $this->getGateway()->findOneById($id);
            return $this->getModel($values);
        } catch (NoResultsFoundException $nrfe) {
            return false;
        }
    }

    /**
     * @param string $action
     * @return \Mandragora\Form\SecureForm
     */
    public function getFormForEditing($action)
    {
        $this->getForm('Detail')->setAction($action);
        $this->getForm()->prepareForEditing();
        return $this->getForm();
    }

    /**
     * @return void
     */
    public function updateCategory()
    {
        $this->init();
        $this->getModel()->fromArray($this->getForm()->getValues());
        $this->getModel()->url = $this->getModel()->name;
        $this->getGateway()->update($this->getModel());
    }

    /**
     * @return void
     */
    public function deleteCategory()
    {
        $this->init();
        $this->getGateway()->delete($this->getModel());
    }

    /**
     * @return void
     * @throws \Zend_Exception
     */
    public function addCategoriesToSitemap(Navigation $container)
    {
        $this->init();
        /** @var array $categories */
        $categories = $this->getGateway()->getQueryFindAll()->fetchArray();

        $i = 0;
        $label = '';
        foreach ($categories as $category) {
            $mvcPage = Page::factory([
                'controller' => 'property',
                'action' => 'list', 'module' => 'default',
                'route' => 'property', 'label' => $category['name'],
                'params' => ['category' => $category['url'],]
            ]);
            if ($i !== 0) {
                $container->findBy('label', $label)->addPage($mvcPage);
            } else {
                $container->addPage($mvcPage);
                $label = $category['name'];
            }
            $i++;
        }
    }

    public function getModel(array $values = null): AbstractModel
    {
        if (!$this->model) {
            $this->model = new CategoryModel($values);
        }

        return $this->model;
    }
}
