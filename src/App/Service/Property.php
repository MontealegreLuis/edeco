<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

/**
 * Service class for Property model
 *
 * @category   Panel
 * @package    Panel
 * @subpackage Service
 * @author     LNJ <lemuel.nonoal@mandragora-web-systems.com>
 * @author     LMV <montealegreluis@gmail.com>
 */
class   App_Service_Property
extends Mandragora_Service_Crud_Doctrine_Abstract
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
     * @return App_Model_Collection_Property
     */
    public function retrieveAllPropertiesWithPictures($pageNumber)
    {
        $this->init();
        $this->query = $this
            ->getGateway()
            ->getQueryFindAllWebPropertiesWithPictures()
        ;
        $this->setPropertiesPaginator();
        $items = (array) $this->getPaginator($pageNumber)->getCurrentItems();

        return new App_Model_Collection_Property($items);
    }

    /**
     * @param int $pageNumber
     * @return Edeco_Model_Collection_Property
     */
    public function retrievePropertyCollection($pageNumber)
    {
        $this->init();
        $query = $this->getGateway()->getQueryFindAll();
        $this->setPaginatorQuery($query);
        $items = (array)$this->getPaginator($pageNumber)->getCurrentItems();
        return new App_Model_Collection_Property($items);

    }

    /**
     * @param string $state
     * @param string $category
     * @param string $availability
     * @param int $page
     * @return App_Model_Collection_Property
     * @todo Deprecated?
     */
    public function retrievePropertiesBy($state, $category, $availability, $page)
    {
        $this->init();
        $this->query = $this
            ->getGateway()
            ->getQueryFindPropertiesBy($state, $category, $availability)
        ;
        $this->setPropertiesPaginator();
        $items = (array) $this->getPaginator((int) $page)->getCurrentItems();
    	return new App_Model_Collection_Property($items);
    }

    /**
     * @param string $category
     * @return array
     */
    public function retrieveCountByCategory($category)
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
        $adapter = new Mandragora_Paginator_Adapter_DoctrineQuery($this->query);
        $this->paginator = new Edeco_Paginator_Property($adapter);
        $itemsPerPage = (int) $this->paginatorOptions['itemCountPerPage'];
        $this->paginator->setItemCountPerPage($itemsPerPage);
        $pageRange = (int)$this->paginatorOptions['pageRange'];
        $this->paginator->setPageRange($pageRange);
        $this->paginator->setCache($this->getCache('gateway'));
    }

    /**
     * @param string $url
     * @return Edeco_Model_Property
     */
    public function retrievePropertyByUrl($url)
    {
        $this->init();
        $propertyValues = $this->getGateway()->findOneByUrl((string)$url);
        return $this->getModel($propertyValues);
    }

    /**
     * @param string $action
     * @return Mandragora_Zend_Form_Abstract
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
     * @return Mandragora_Form_Abstract
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
        $this->getForm()
             ->setAvailabilities(App_Enum_PropertyAvailability::values());
        $this->getForm()->setLandUses(App_Enum_PropertyLandUse::values());
    }

    /**
     * @return array
     */
    public function getCategories()
    {
        $serviceCategory = Mandragora_Service::factory('Category');
        $serviceCategory->setCacheManager($this->cacheManager);
        $serviceCategory->setDoctrineManager($this->doctrineManager);
        return $serviceCategory->retrieveAllCategories();
    }

    /**
     * @return App_Model_Property | boolean
     */
    public function retrievePropertyById($id)
    {
        $this->init();
        try {
            $propertyValues = $this->getGateway()->findOneById((int)$id);
            return $this->getModel($propertyValues);
        } catch (Mandragora_Gateway_NoResultsFoundException $nrfe) {
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
     * @param int $id
     * @return void
     */
    public function deleteProperty($id)
    {
        $propertyValues = $this->getGateway()->findOneById((int)$id);
        $this->getModel()->fromArray($propertyValues);
        $this->getGateway()->delete($this->getModel());
    }

    /**
     * @param array $properties
     * @return string
     */
    public function propertiesToJson(array $properties)
    {
        return $this->getModel()->propertiesToJson($properties);
    }

    /**
     * @param array $property
     * @param string $address
     * @return string
     */
    public function propertyToJson(array $property, $address)
    {
        return $this->getModel()->propertyToJson($property, (string)$address);
    }

    /**
     * @param string $propertyName
     * @return App_Model_Collection_Property
     */
    public function findPropertiesByNameLike($propertyName)
    {
        $this->init();
    	return new App_Model_Collection_Property(
    	   $this->getGateway()->findAllPropertiesWithNameLike($propertyName)
    	);
    }

    /**
    * @param string $propertyName
    * @return array
    */
    public function findWebPropertiesByNameLike($propertyName)
    {
        $this->init();
        return $this->getGateway()
                    ->findAllWebPropertiesWithNameLike($propertyName);
    }

    /**
     * @param int $stateId
     * @param int $propertyId
     * @return App_Model_Collection_Property
     */
    public function findRecommendedWebProperties($stateId, $propertyId)
    {
        $this->init();
        $properties = $this->getGateway()->findRecommendedWebProperties(
            (int)$stateId, (int)$propertyId
        );
        return new App_Model_Collection_Property($properties);
    }

    /**
     * @param Zend_Navigation $container
     * @return void
     */
    public function addPropertiesToSitemap(Zend_Navigation $container)
    {

        $properties = $this->getGateway()->findAllWebProperties();
        $view = Zend_Layout::getMvcInstance()->getView();
        foreach ($properties as $property) {
            $availability = $view->translate($property['availabilityFor']);
            $mvcPage = Zend_Navigation_Page::factory(
                array(
                    'controller' => 'property',
                    'action' => 'new-detail', 'module' => 'default',
                    'route' => 'newdetail', 'label' => $property['name'],
                    'params' => array(
                        'propertyUrl' => $property['url'],
                        'availability' => $availability,
                        'state' => $property['Address']['City']['State']['url'],
                        'category' => $property['Category']['url']
                    )
                )
            );
            $container->addPage($mvcPage);
        }

    }

}