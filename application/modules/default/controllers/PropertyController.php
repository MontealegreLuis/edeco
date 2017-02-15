<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use App\Model\Collection\Category as Categories;
use Mandragora\Controller\Action\AbstractAction;
use Mandragora\Gateway;
use Mandragora\Gateway\NoResultsFoundException;
use Mandragora\Service;
use Zend_Controller_Action_Exception as ActionException;

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
     * @throws \Zend_Controller_Action_Exception If the category does not exist
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
            throw new ActionException("Category $category does not exist", 404);
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
     * @throws \Zend_Controller_Action_Exception If the state does not exist
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
        } catch (NoResultsFoundException $nrf) {
            throw new ActionException($nrf->getMessage(), 404);
        }
    }

    /**
     * Perform 301 redirect to the new url
     *
     * @throws Zend_Controller_Action_Exception If the property does not exist
     */
    public function detailAction()
    {
        try {
            $propertyUrl = (string) $this->param('propertyUrl');
            $property = $this->service->retrievePropertyByUrl($propertyUrl);
            $availability = $this->view->translate($property->availabilityFor);
            $newRoute = $this->view->url(
                [
                	'propertyUrl' => $property->url->render(),
                	'category' => $property->Category->url,
                	'state' => $property->Address->City->State->url,
                	'availability' => $availability,
                ],
            	'newdetail'
            );
            return $this->redirect($newRoute, ['code' => 301]);
        } catch (Exception $e) {
            throw new ActionException('Page not found', 404);
        }
    }

    /**
     * Show the details of a property
     *
     * @throws Zend_Controller_Action_Exception If there's no property with the
     * given characteristics
     */
    public function newDetailAction()
    {
        try {
            $this->view->googleMapsKey = $this->_helper->googleMaps($this->getRequest());
            $property = $this->service->retrievePropertyByUrl((string) $this->param('propertyUrl'));
            $category = (string) $this->param('category');
            $state = (string) $this->param('state');
            $availability = (string) $this->param('availability');
            $availability = $availability === 'renta'
                ? 'rent' : ($availability === 'venta' ? 'sale' : null);
            if ($category !== (string) $property->Category->url
                || $state !== (string) $property->Address->City->State->url
                || $availability !== $property->availabilityFor) {
                throw new ActionException('Parameters do not match property values');
            }
            $template = 'widgets/social-share.phtml';
            $this->view->socialWidget = $this->_helper->socialShareWidget($template);
            $this->view->category = $category;
            $this->view->propertyDetail = $property;
            $this->view->propertyJson = $property->toJson();
            $state = $this->view->breadcrumbs->findByAction('list');
            $state->setLabel($property->Category->name);
            $product = $this->view->breadcrumbs->findByAction('list-by-state');
            $product->setLabel(ucfirst($this->param('availability')).' en '.ucfirst($this->param('state')));
            $detail = $this->view->breadcrumbs->findByAction('new-detail');
            $detail->setLabel($property->name);
        } catch (NoResultsFoundException $e) {
            throw new ActionException($e->getMessage(), 404);
        } catch (Exception $e) {
            throw new ActionException($e->getMessage(), 404);
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
