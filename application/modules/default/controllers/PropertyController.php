<?php
/**
 * Application's default controller
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
 * @category   Application
 * @package    Edeco
 * @subpackage Controller
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN $Id$
 */

/**
 * Application's default controller
 *
 * @category   Application
 * @package    Edeco
 * @subpackage Controller
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN $Id$
 */
class   PropertyController
extends Mandragora_Controller_Action_Abstract
{
    /**
     * @see Zend_Controller_Action::init()
     */
    public function init()
    {
        $this->service = Mandragora_Service::factory('Property');
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
        $serviceCategory = Mandragora_Service::factory('Category');
        $serviceCategory->setCacheManager($this->getCacheManager());
        $doctrine = $this->getInvokeArg('bootstrap')->getResource('doctrine');
        $serviceCategory->setDoctrineManager($doctrine);
        $url = (string)$this->param('category');
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
            $categoryGateway = Mandragora_Gateway::factory('Category');
            $category = $categoryGateway->findOneByUrl($categoryUrl);
            $stateGateway = Mandragora_Gateway::factory('State');
            $state = $stateGateway->findOneByUrl($stateUrl);
            $availability = $availability === 'renta' ? 'rent' : 'sale';
            $page = (int)$this->param('page', 1);
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
            $this->view->googleMapsKey = $this->_helper
                                              ->googleMaps($this->getRequest());
            $propertyUrl = (string)$this->param('propertyUrl');
            $property = $this->service->retrievePropertyByUrl($propertyUrl);
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
        $items = $this->service->getCategories();
        $categories = new App_Model_Collection_Property($items);
        $this->view->categories = $categories;
    }

}