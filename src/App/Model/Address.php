<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model;

use Mandragora\Model\AbstractModel;
use Mandragora\Model;
use Zend_Registry as Registry;
use Mandragora\Geocoder\Adapter;
use Edeco\Geocoder\PlaceMark\JsonFormatter;

/**
 * Contains all the information related to the Address
 *
 * @property integer $id
 * @property string $streetAndNumber
 * @property string $neighborhood
 * @property string $zipCode
 * @property string $addressReference
 * @property float $latitude
 * @property float $longitude
 * @property integer $cityId
 * @property integer $propertyId
 * @property integer $version
 * @property App_Model_City $City
 */
class Address extends AbstractModel
{
    /**
     * @var array
     */
    protected $properties = [
        'streetAndNumber' => '', 'neighborhood' => null, 'zipCode' => null,
        'City' => null, 'addressReference' => null, 'latitude' => null,
        'longitude' => null, 'cityId' => null,
    ];

    /**
     * @var array
     * @return string
     */
    protected $identifier = ['id'];

    /**
     * @param array $values
     */
    public function setCity($values)
    {
        if (is_array($values)) {
            $city = Model::factory('City', $values);
            $this->properties['City'] = $city;
        } else {
            $this->properties['City'] = null;
        }
    }

    /**
     * @return \Mandragora\Geocoder\Placemark[]
     */
    public function geocode()
    {
        $adapter = new Adapter(Registry::get('googleMapsKey'));
        $address = str_replace('|', ', ', (string) $this);
        return $adapter->lookup($address);
    }

    /**
     * Concatenates street and number and neighborhood
     *
     * @return string
     */
    public function location()
    {
        return sprintf(
            '%s %s', $this->properties['streetAndNumber'],
            $this->properties['neighborhood']
        );
    }

    /**
     * @param array $placeMarks
     * @return string
     */
    public function placeMarksToJson(array $placeMarks)
    {
        return (new JsonFormatter())->format($placeMarks);
    }

    /**
     * Format for addresses is:
     * - Street and number
     * - neighborhood
     * - city
     * - state
     * - country (Mexico is the default)
     *
     * Any null value will be removed from the output
     */
    public function __toString()
    {
        return implode(', ', array_filter([
            $this->properties['streetAndNumber'],
            $this->properties['neighborhood'],
            $this->properties['City']->name,
            $this->properties['City']->State->name,
            'México'
        ], function ($property) { return null !== $property; }));
    }

   /**
    * @return string
    */
    public function toHtml()
    {
        $zipCode = $this->properties['zipCode'] !== ''
        ? '<br />C. P. ' . $this->properties['zipCode']
        : '';
        return $this->properties['streetAndNumber'] . '<br />'
        . $this->properties['neighborhood'] . '<br />'
        . $this->properties['City']->name
        . ', ' . $this->properties['City']->State->name
        . ', México' . $zipCode;
    }
}
