<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use Mandragora\Service\Crud\Doctrine\DoctrineCrud;
use App\Model\Collection\Category as AppModelCollectionCategory;
use Mandragora\Gateway\NoResultsFoundException;
use Zend_Navigation;
use Zend_Navigation_Page;

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
     * @param int $pageNumber
     * @return App_Model_Collection_Category
     */
    public function retrieveCategoryCollection($pageNumber)
    {
        $this->init();
        $this->query = $this->getGateway()->getQueryFindAll();
        $items = (array)$this->getPaginator($pageNumber)->getCurrentItems();
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
    * @return App_Model_Property | boolean
    */
    public function retrieveCategoryByUrl($url)
    {
        $this->init();
        try {
            $categoryValues = $this->getGateway()->findOneByUrl((string)$url);
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
     * @return Mandragora_Form_Abstract
     */
    public function getFormForCreating($action)
    {
        $this->getForm('Detail')->setAction($action);
        $this->getForm()->prepareForCreating();
        return $this->getForm();
    }

    /**
     * @return App_Model_Category
     * @throws Mandragora_Gateway_NoResultsFoundException
     */
    public function retrieveCategoryById($id)
    {
        try {
            $this->init();
            $values = $this->getGateway()->findOneById((int)$id);
            return $this->getModel($values);
        } catch (NoResultsFoundException $nrfe) {
            return false;
        }
    }

    /**
     * @param string $action
     * @return Mandragora_Form_Abstract
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
     * @param int $id
     * @return void
     */
    public function deleteCategory($id)
    {
        $this->init();
        $this->getGateway()->delete($this->getModel());
    }

    /**
    * @param Zend_Navigation $container
    * @return void
    */
    public function addCategoriesToSitemap(Zend_Navigation $container)
    {
        $this->init();
        $query = $this->getGateway()->getQueryFindAll();
        $categories = $query->fetchArray();
        $i = 0;
        foreach ($categories as $category) {
            $mvcPage = Zend_Navigation_Page::factory(
                array(
                    'controller' => 'property',
                    'action' => 'list', 'module' => 'default',
                    'route' => 'property', 'label' => $category['name'],
                    'params' => array('category' => $category['url'],)
                )
            );
            if ($i !== 0) {
                $container->findBy('label', $label)->addPage($mvcPage);
            } else {
                $container->addPage($mvcPage);
                $label = $category['name'];
            }
            $i++;
        }
    }
}
