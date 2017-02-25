<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use App\Model\Property as PropertyModel;
use Mandragora\Model\AbstractModel;
use Mandragora\Service\Crud\Doctrine\DoctrineCrud;
use App\Model\Collection\Property as PropertyCollection;
use Mandragora\Paginator\Adapter\DoctrineQuery;
use Edeco\Paginator\Property as PropertyPaginator;
use App\Enum\PropertyAvailability;
use App\Enum\PropertyLandUse;
use Mandragora\Service;
use Mandragora\Gateway\NoResultsFoundException;
use Zend_Navigation as Navigation;
use Zend_Layout as Layout;
use Zend_Navigation_Page as Page;

/**
 * Service class for Property model
 */
class Property extends DoctrineCrud
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
     * @return PropertyCollection
     */
    public function retrieveAllPropertiesWithPictures(int $pageNumber)
    {
        $this->init();
        $this->query = $this
            ->getGateway()
            ->getQueryFindAllWebPropertiesWithPictures()
        ;
        $this->setPropertiesPaginator();
        $items = (array) $this->getPaginator($pageNumber)->getCurrentItems();

        return new PropertyCollection($items);
    }

    /**
     * @return PropertyCollection
     */
    public function retrievePropertyCollection(int $pageNumber)
    {
        $this->init();
        $query = $this->getGateway()->getQueryFindAll();
        $this->setPaginatorQuery($query);
        $items = (array) $this->getPaginator($pageNumber)->getCurrentItems();
        return new PropertyCollection($items);
    }

    /**
     * @return PropertyCollection
     * @todo Deprecated?
     */
    public function retrievePropertiesBy(
        string $state,
        string $category,
        string $availability,
        int $page
    ) {
        $this->init();
        $this->query = $this
            ->getGateway()
            ->getQueryFindPropertiesBy($state, $category, $availability)
        ;
        $this->setPropertiesPaginator();
        $items = (array) $this->getPaginator($page)->getCurrentItems();
    	return new PropertyCollection($items);
    }

    /**
     * @return array
     */
    public function retrieveCountByCategory(string $category)
    {
        $this->init();
        return $this->getGateway()->getCountByCategory($category);
    }

    /**
     * Set custom paginator for queries in default module in order to
     * synchronize collections with the admin module
     *
     * @return void
     */
    public function setPropertiesPaginator()
    {
        $this->paginator = new PropertyPaginator(new DoctrineQuery($this->query));
        $itemsPerPage = (int) $this->paginatorOptions['itemCountPerPage'];
        $this->paginator->setItemCountPerPage($itemsPerPage);
        $pageRange = (int)$this->paginatorOptions['pageRange'];
        $this->paginator->setPageRange($pageRange);
        $this->paginator->setCache($this->getCache('gateway'));
    }

    /**
     * @return PropertyModel
     */
    public function retrievePropertyByUrl(string $url)
    {
        $this->init();
        $propertyValues = $this->getGateway()->findOneByUrl((string)$url);
        return $this->getModel($propertyValues);
    }

    /**
     * @param string $action
     * @return \Mandragora\Form\SecureForm
     */
    public function getFormForCreating($action)
    {
        $this->getForm()->setAction($action);
        $this->getForm()->prepareForCreating();
        $this->setSelectOptions();
        return $this->getForm();
    }

    /**
     * @return void
     */
    public function createProperty()
    {
        $this->init();
        $this->getModel()->fromArray($this->getForm()->getValues());
        $this->getModel()->audit();
        $this->getModel()->url = $this->getForm()->getValue('name');
        $this->getGateway()->clearRelated();
        $this->getGateway()->insert($this->getModel());
    }

    /**
     * Get the form customized for updating a property
     *
     * @param string $action
     * @return \Mandragora\Form\SecureForm
     */
    public function getFormForEditing($action)
    {
        $this->getForm()->setAction($action);
        $this->getForm()->prepareForEditing();
        $this->setSelectOptions();
        return $this->getForm();
    }

    /**
     * @return void
     */
    protected function setSelectOptions()
    {
        $this->getForm()->setCategories($this->getCategories());
        $this->getForm()->setAvailabilities(PropertyAvailability::values());
        $this->getForm()->setLandUses(PropertyLandUse::values());
    }

    /**
     * @return array
     */
    public function getCategories()
    {
        $serviceCategory = Service::factory('Category');
        $serviceCategory->setCacheManager($this->cacheManager);
        $serviceCategory->setDoctrineManager($this->doctrineManager);
        return $serviceCategory->retrieveAllCategories();
    }

    /**
     * @return PropertyModel | boolean
     */
    public function retrievePropertyById($id)
    {
        $this->init();
        try {
            $propertyValues = $this->getGateway()->findOneById((int)$id);
            return $this->getModel($propertyValues);
        } catch (NoResultsFoundException $nrfe) {
            return false;
        }
    }

    /**
     * @return void
     */
    public function updateProperty()
    {
        $propertyInformation = $this->getForm()->getValues();
        $this->getModel()->fromArray($propertyInformation);
        $this->getModel()->url = $this->getForm()->getValue('name');
        $this->getGateway()->clearRelated();
        $this->getGateway()->update($this->getModel());
    }

    /**
     * @return void
     */
    public function deleteProperty(int $id)
    {
        $propertyValues = $this->getGateway()->findOneById($id);
        $this->getModel()->fromArray($propertyValues);
        $this->getGateway()->delete($this->getModel());
    }

    /**
     * @return string
     */
    public function propertiesToJson(array $properties)
    {
        return $this->getModel()->propertiesToJson($properties);
    }

    /**
     * @return string
     */
    public function propertyToJson(array $property, string $address)
    {
        return $this->getModel()->propertyToJson($property, $address);
    }

    /**
     * @return PropertyCollection
     */
    public function findPropertiesByNameLike(string $propertyName)
    {
        $this->init();
    	return new PropertyCollection(
    	   $this->getGateway()->findAllPropertiesWithNameLike($propertyName)
    	);
    }

    /**
    * @return array
    */
    public function findWebPropertiesByNameLike(string $propertyName)
    {
        $this->init();
        return $this->getGateway()
                    ->findAllWebPropertiesWithNameLike($propertyName);
    }

    /**
     * @return PropertyCollection
     */
    public function findRecommendedWebProperties(int $stateId, int $propertyId)
    {
        $this->init();
        $properties = $this->getGateway()->findRecommendedWebProperties($stateId, $propertyId);
        return new PropertyCollection($properties);
    }

    /**
     * @param Navigation $container
     * @return void
     * @throws \Zend_Exception
     */
    public function addPropertiesToSitemap(Navigation $container)
    {
        /** @var array $properties */
        $properties = $this->getGateway()->findAllWebProperties();
        $view = Layout::getMvcInstance()->getView();
        foreach ($properties as $property) {
            $availability = $view->translate($property['availabilityFor']);
            $mvcPage = Page::factory([
                'controller' => 'property',
                'action' => 'new-detail', 'module' => 'default',
                'route' => 'newdetail', 'label' => $property['name'],
                'params' => [
                    'propertyUrl' => $property['url'],
                    'availability' => $availability,
                    'state' => $property['Address']['City']['State']['url'],
                    'category' => $property['Category']['url']
                ]
            ]);
            $container->addPage($mvcPage);
        }
    }

    public function getModel(array $values = null): ?AbstractModel
    {
        if (!$this->model) {
            $this->model = new PropertyModel($values);
        }

        return $this->model;
    }
}
