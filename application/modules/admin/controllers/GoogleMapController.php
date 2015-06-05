<?php
/**
 * Application's Address controller
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
 * @author     LNJ <lemuel.nonoal@mandragora-web-systems.com>
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Application's Address controller
 *
 * @category   Application
 * @package    Edeco
 * @subpackage Controller
 * @author     LNJ <lemuel.nonoal@mandragora-web-systems.com>
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class Admin_GoogleMapController extends Mandragora_Controller_Action_Abstract
{
    /**
     * @var array
     */
    protected $validMethods = array(
        'save' => array('method' => 'post'),
        'update' => array('method' => 'post'),
    );

    /**
     * Initialize the service object
     *
     * @return void
     */
    public function init()
    {
        $this->service = Mandragora_Service::factory('GoogleMap');
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
        $params = array('id' => $propertyId);
        $this->view->gMapActions = array('gmap.action.create' => $params,);
        $this->view->title = $this->view->translate('gmap.action.create');

    }

    /**
     * Save the google map information in the data source
     *
     * @return void
     */
    public function saveAction()
    {
        $addressId = (int)$this->param('id');
        $action = array(
            'action' => 'save', 'controller' => 'google-map',
            $this->view->translate('propertyId') => $addressId
        );
        $action = $this->view->url($action, 'controllers', true);
        $googleForm = $this->service->getFormForCreating($action);
        $googleForm->setAddressId($addressId);
        $geoPosition = $this->post();
        if ($googleForm->isValid($geoPosition)) {
            $addressId = (int)$this->post('addressId');
            $this->service->saveGeoPosition($addressId, $geoPosition);
            $this->flash('success')->addMessage('gmap.saved');
            $this->redirectToRoute('show', array('id' => $addressId), 'google-map');
        } else {
            $googleMapsKey = $this->_helper->googleMaps($this->getRequest());
            $this->view->googleMapsKey = $googleMapsKey;
            $placeMarks = $this->service->geocodeAddress($addressId);
            $this->view->jsonPlaceMarkers = $this->service
                                                 ->placeMarksToJson($placeMarks);
            $this->view->placeMarks = $placeMarks;
            $this->view->googleForm = $googleForm;
            $this->view->addressId = $addressId;
            $this->view->gMapActions = array();
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
        $addressId = (int)$this->param('id');
        $googleMapsKey = $this->_helper->googleMaps($this->getRequest());
        $propertyService = Mandragora_Service::factory('Property');
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
        $formValues = $this->service
                           ->retrievePropertyLatitudeAndLogitude($addressId);
        $this->view->title = $this->view->translate('gmap.action.edit');
        $this->view->googleForm->populate($formValues);
        $params = array('id' => $addressId);
        $this->view->gMapActions = array(
            'gmap.action.show' => $params,
            'gmap.action.edit' => $params,
        );
        $this->renderScript('google-map/create.phtml');
    }

    /**
     * @return void
     */
    protected function geocodeAddressResults()
    {
        $addressId = (int)$this->param('id');
        $action = array(
            'action' => 'save', 'controller' => 'google-map', 'id' => $addressId
        );
        $action = $this->view->url($action, 'controllers', true);
        $googleForm = $this->service->getFormForCreating($action);
        $googleForm->setAddressId($addressId);
        $googleForm->setAction($action);
        $googleMapsKey = $this->_helper->googleMaps($this->getRequest());
        $this->view->googleMapsKey = $googleMapsKey;
        $placeMarks = $this->service->geocodeAddress($addressId);
        $this->view->jsonPlaceMarkers = $this->service
                                             ->placeMarksToJson($placeMarks);
        $this->view->placeMarks = $placeMarks;
        $this->view->googleForm = $googleForm;
        $this->view->addressId = $addressId;
    }

}