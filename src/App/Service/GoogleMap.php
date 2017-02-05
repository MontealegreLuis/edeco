<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use Mandragora\Service\Crud\Doctrine\DoctrineCrud;
use Mandragora\Model;
use Mandragora\Gateway;

/**
 * Service class for Address model
 */
class GoogleMap extends DoctrineCrud
{
    public function init()
    {
        $this->openConnection();
        $this->setModel(Model::factory('Address'));
        $this->setGateway(Gateway::factory('Address'));
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
