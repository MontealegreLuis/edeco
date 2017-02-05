<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use App\Model\Collection\Category as Categories;
use Mandragora\Controller\Action\AbstractAction;
use Mandragora\Gateway;
use Mandragora\Service;

/**
 * Application's default controller
 */
class PropertyController extends AbstractAction
{
    /**
     * @see Zend_Controller_Action::init()
     */
    public function init()
    {
        $this->service = Service::factory('Property');
        $this->service->setCacheManager($this->getCacheManager());
        $doctrine = $this->getInvokeArg('bootstrap')->getResource('doctrine');
        $this->service->setDoctrineManager($doctrine);
        $this->service->setPaginatorOptions($this->getAppSetting('paginator'));
        $breadcrumbs = $this->_helper->breadcrumbs($this->getRequest());
        $this->view->breadcrumbs = $breadcrumbs;
    }

    /**
     * Show the list of available properties grouped by state
     *
     * @return void
     */
    public function listAction()
    {
        $serviceCategory = Service::factory('Category');
        $serviceCategory->setCacheManager($this->getCacheManager());
        $doctrine = $this->getInvokeArg('bootstrap')->getResource('doctrine');
        $serviceCategory->setDoctrineManager($doctrine);
        $url = (string) $this->param('category');
        $category = $serviceCategory->retrieveCategoryByUrl($url);
        if (!$category) {
            throw new Zend_Controller_Action_Exception(
                "Category $category does not exist", 404
            );
        } else {
            $properties = $this->service->retrieveCountByCategory($category);
            $this->view->properties = $properties;
            $this->view->category = $category;
            $url = $this->view->breadcrumbs->findByAction('list');
            $url->setLabel($category->name);
        }
    }

    /**
     * Show the list of properties filtered by state and availability
     * (rent or sale)
     *
     * @return void
     */
    public function listByStateAction()
    {
        try {
            $this->service->openConnection();
            $categoryUrl = $this->param('category');
            $stateUrl = $this->param('state');
            $availability = $this->param('availability');
            $categoryGateway = Gateway::factory('Category');
            $category = $categoryGateway->findOneByUrl($categoryUrl);
            $stateGateway = Gateway::factory('State');
            $state = $stateGateway->findOneByUrl($stateUrl);
            $availability = $availability === 'renta' ? 'rent' : 'sale';
            $page = (int) $this->param('page', 1);
            $properties = $this->service->retrievePropertiesBy(
                $stateUrl, $categoryUrl, $availability, $page
            );
            $this->view->properties = $properties;
            $this->view->category = $category['name'];
            $this->view->categoryUrl = $category['url'];
            $this->view->state = $state;
            $this->view->availability = $availability;
            $this->view->paginator = $this->service->getPaginator($page);
            $stateUrl = $this->view->breadcrumbs->findByAction('list');
            $stateUrl->setLabel($category['name']);
            $product = $this->view->breadcrumbs->findByAction('list-by-state');
            $product->setLabel(ucfirst($this->param('availability')).' en '.$state['name']);
        } catch (Mandragora_Gateway_NoResultsFoundException $nrf) {
            throw new Zend_Controller_Action_Exception($nrf->getMessage(), 404);
        }
    }

    /**
     * Perform 301 redirect to the new url
     *
     * @throws Zend_Controller_Action_Exception
     */
    public function detailAction()
    {
        try {
            $propertyUrl = (string)$this->param('propertyUrl');
            $property = $this->service->retrievePropertyByUrl($propertyUrl);
            $availability = $this->view->translate($property->availabilityFor);
            $newRoute = $this->view->url(
                array(
                	'propertyUrl' => $property->url->render(),
                	'category' => $property->Category->url,
                	'state' => $property->Address->City->State->url,
                	'availability' => $availability,
            	),
            	'newdetail'
            );
            return $this->_redirect($newRoute, array('code' => 301));
        } catch (Exception $e) {
            throw new Zend_Controller_Action_Exception('Page not found', 404);
        }
    }

    /**
     * Show the details of a property
     *
     * @return void
    */
    public function newDetailAction()
    {
        try {
            $this->view->googleMapsKey = $this->_helper->googleMaps($this->getRequest());
            $property = $this->service->retrievePropertyByUrl((string) $this->param('propertyUrl'));
            $category = (string)$this->param('category');
            $state = (string)$this->param('state');
            $availability = (string)$this->param('availability');
            $availability = $availability === 'renta'
                ? 'rent' : ($availability === 'venta' ? 'sale' : null);
            if ($category !== (string)$property->Category->url
                || $state !== (string)$property->Address->City->State->url
                || $availability !== $property->availabilityFor) {
                throw new Exception('Parameters do not match property values');
            }
            $template = 'widgets/social-share.phtml';
            $this->view->socialWidget = $this->_helper
                                             ->socialShareWidget($template);
            $this->view->category = $category;
            $this->view->propertyDetail = $property;
            $this->view->propertyJson = $property->toJson();
            $state = $this->view->breadcrumbs->findByAction('list');
            $state->setLabel($property->Category->name);
            $product = $this->view->breadcrumbs->findByAction('list-by-state');
            $product->setLabel(ucfirst($this->param('availability')).' en '.ucfirst($this->param('state')));
            $detail = $this->view->breadcrumbs->findByAction('new-detail');
            $detail->setLabel($property->name);
        } catch (Mandragora_Gateway_NoResultsFoundException $e) {
            throw new Zend_Controller_Action_Exception($e->getMessage(), 404);
        } catch (Exception $e) {
            throw new Zend_Controller_Action_Exception($e->getMessage(), 404);
        }
    }

    /**
     * Show the list of available categories
     */
    public function categoryListAction()
    {
        $this->view->categories = new Categories($this->service->getCategories());
    }
}
