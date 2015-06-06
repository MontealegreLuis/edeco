<?php
/**
 * Service class for Address model
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
 * @subpackage Service
 * @author     LNJ <lemuel.nonoal@mandragora-web-systems.com>
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Service class for Address model
 *
 * @category   Application
 * @package    Edeco
 * @subpackage Service
 * @author     LNJ <lemuel.nonoal@mandragora-web-systems.com>
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class App_Service_GoogleMap extends Mandragora_Service_Crud_Doctrine_Abstract
{
    public function init()
    {
        $this->openConnection();
        $this->setModel(Mandragora_Model::factory('Address'));
        $this->setGateway(Mandragora_Gateway::factory('Address'));
        $this->decorateGateway('Address');
    }

    /**
     * @param int $id
     * @param array
     */
    public function geocodeAddress($id)
    {
        $this->init();
        $addressValues = $this->getGateway()->findOneById((int)$id);
        $this->getModel()->fromArray($addressValues);
        return $this->getModel()->geocode();
    }

    /**
     * @param array $placeMarks
     * @return string
     */
    public function placeMarksToJson(array $placeMarks)
    {
        $this->init();
        return $this->getModel()->placeMarksToJson($placeMarks);
    }

    /**
     * @param int $propertyId
     * @param array $geoPostition
     * @return void
     */
    public function saveGeoPosition($propertyId, array $geoPosition)
    {
        $this->init();
        $this->getGateway()->saveGeoPosition((int)$propertyId, $geoPosition);
    }

    /**
     * @param int $propertyId
     * @return array
     */
    public function retrievePropertyLatitudeAndLogitude($propertyId)
    {
        $property = $this->getGateway()->findOneById($propertyId);
        return array(
            'latitude' => $property['latitude'],
            'longitude' => $property['longitude']
        );
    }

    /**
     * @param string $action
     * @return void
     */
    public function getFormForCreating($action)
    {
        $this->getForm()->prepareForCreating();
        $this->getForm()->setAction($action);
        return $this->getForm();
    }

    /**
     * @param string $action
     * @return Mandrgora_Form_Crud_Abstract
     */
    public function getFormForEditing($action)
    {
        $this->getForm()->prepareForEditing();
        $this->getForm()->setAction($action);
        return $this->getForm();
    }

}