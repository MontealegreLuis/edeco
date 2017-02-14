<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Mandragora\Controller\Action\AbstractAction;
use Mandragora\Service;

/**
 * CRUD for google maps
 */
class Admin_GoogleMapController extends AbstractAction
{
    /** @var array */
    protected $validMethods = [
        'save' => ['method' => 'post'],
        'update' => ['method' => 'post'],
    ];

    /**
     * Initialize the service object
     *
     * @return void
     */
    public function init()
    {
        $this->service = Service::factory('GoogleMap');
        $this->service->setCacheManager($this->getCacheManager());
        $doctrine = $this->getInvokeArg('bootstrap')->getResource('doctrine');
        $this->service->setDoctrineManager($doctrine);
        $actions = $this->_helper->actionsBuilder($this->getRequest());
        $this->view->actions = $actions;
    }

    /**
     * Create Google map for a given property's address
     *
     * @return void
     */
    public function createAction()
    {
        $this->geocodeAddressResults();
        $propertyId = $this->param('id');
        $this->view->gMapActions = ['gmap.action.create' => ['id' => $propertyId]];
        $this->view->title = $this->view->translate('gmap.action.create');
    }

    /**
     * Save the google map information in the data source
     *
     * @return void
     */
    public function saveAction()
    {
        $addressId = (int) $this->param('id');
        $action = [
            'action' => 'save', 'controller' => 'google-map',
            $this->view->translate('propertyId') => $addressId
        ];
        $action = $this->view->url($action, 'controllers', true);
        $googleForm = $this->service->getFormForCreating($action);
        $googleForm->setAddressId($addressId);
        $geoPosition = $this->post();
        if ($googleForm->isValid($geoPosition)) {
            $addressId = (int)$this->post('addressId');
            $this->service->saveGeoPosition($addressId, $geoPosition);
            $this->flash('success')->addMessage('gmap.saved');
            $this->redirectToRoute('show', ['id' => $addressId], 'google-map');
        } else {
            $googleMapsKey = $this->_helper->googleMaps($this->getRequest());
            $this->view->googleMapsKey = $googleMapsKey;
            $placeMarks = $this->service->geocodeAddress($addressId);
            $this->view->jsonPlaceMarkers = $this->service->placeMarksToJson($placeMarks);
            $this->view->placeMarks = $placeMarks;
            $this->view->googleForm = $googleForm;
            $this->view->addressId = $addressId;
            $this->view->gMapActions = [];
            $this->view->title = $this->view->translate('gmap.action.save');
            $this->renderScript('google-map/create.phtml');
        }
    }

    /**
     * Show Google map
     *
     * @return void
     */
    public function showAction()
    {
        $addressId = (int) $this->param('id');
        $googleMapsKey = $this->_helper->googleMaps($this->getRequest());
        $propertyService = Service::factory('Property');
        $propertyService->setCacheManager($this->getCacheManager());
        $doctrine = $this->getInvokeArg('bootstrap')->getResource('doctrine');
        $propertyService->setDoctrineManager($doctrine);
        $property = $propertyService->retrievePropertyById($addressId);
        $address = $property->Address->toHtml();
        $this->view->propertyJson = $property->toJson();
        $this->view->googleMapsKey = $googleMapsKey;
        $this->view->addressId = $addressId;
        $this->view->property = $property;
        $this->view->address =$address;
    }

    /**
     * Edit the geolocation of a property's address
     *
     * @return void
     */
    public function editAction()
    {
        $this->geocodeAddressResults();
        $addressId = $this->view->addressId;
        $formValues = $this->service->retrievePropertyLatitudeAndLogitude($addressId);
        $this->view->title = $this->view->translate('gmap.action.edit');
        $this->view->googleForm->populate($formValues);
        $params = ['id' => $addressId];
        $this->view->gMapActions = [
            'gmap.action.show' => $params,
            'gmap.action.edit' => $params,
        ];
        $this->renderScript('google-map/create.phtml');
    }

    /**
     * @return void
     */
    protected function geocodeAddressResults()
    {
        $addressId = (int) $this->param('id');
        $action = [
            'action' => 'save', 'controller' => 'google-map', 'id' => $addressId
        ];
        $action = $this->view->url($action, 'controllers', true);
        $googleForm = $this->service->getFormForCreating($action);
        $googleForm->setAddressId($addressId);
        $googleForm->setAction($action);
        $googleMapsKey = $this->_helper->googleMaps($this->getRequest());
        $this->view->googleMapsKey = $googleMapsKey;
        $placeMarks = $this->service->geocodeAddress($addressId);
        $this->view->jsonPlaceMarkers = $this->service->placeMarksToJson($placeMarks);
        $this->view->placeMarks = $placeMarks;
        $this->view->googleForm = $googleForm;
        $this->view->addressId = $addressId;
    }
}
