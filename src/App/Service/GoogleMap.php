<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use App\Model\Address;
use Mandragora\Model\AbstractModel;
use Mandragora\Service\Crud\Doctrine\DoctrineCrud;
use Mandragora\Gateway;

/**
 * Service class for Address model
 */
class GoogleMap extends DoctrineCrud
{
    public function init()
    {
        $this->openConnection();
        $this->setModel(new Address());
        $this->setGateway(Gateway::factory('Address'));
        $this->decorateGateway('Address');
    }

    /**
     * @param array
     */
    public function geocodeAddress(int $id)
    {
        $this->init();
        $addressValues = $this->getGateway()->findOneById((int) $id);
        $this->getModel()->fromArray($addressValues);
        return $this->getModel()->geocode();
    }

    /**
     * @return string
     */
    public function placeMarksToJson(array $placeMarks)
    {
        $this->init();
        return $this->getModel()->placeMarksToJson($placeMarks);
    }

    /**
     * @return void
     */
    public function saveGeoPosition(int $propertyId, array $geoPosition)
    {
        $this->init();
        $this->getGateway()->saveGeoPosition((int)$propertyId, $geoPosition);
    }

    /**
     * @return array
     */
    public function retrievePropertyLatitudeAndLogitude(int $propertyId)
    {
        $property = $this->getGateway()->findOneById($propertyId);
        return [
            'latitude' => $property['latitude'],
            'longitude' => $property['longitude'],
        ];
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
     * @return \Mandragora\Form\Crud\CrudForm
     */
    public function getFormForEditing($action)
    {
        $this->getForm()->prepareForEditing();
        $this->getForm()->setAction($action);
        return $this->getForm();
    }

    public function getModel(array $values = null): ?AbstractModel
    {
        return null;
    }
}
