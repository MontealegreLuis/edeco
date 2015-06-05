<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright  Mandrágora Web-Based Systems 2010-2015 (http://www.mandragora-web-systems.com)
 */

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
 *
 * @author     LNJ <lemuel.nonoal@mandragora-web-systems.com>
 */
class App_Model_Address extends Mandragora_Model_Abstract
{
    /**
     * @var array
     */
    protected $properties = array(
        'streetAndNumber' => '', 'neighborhood' => null, 'zipCode' => null,
        'City' => null, 'addressReference' => null, 'latitude' => null,
        'longitude' => null, 'cityId' => null,
    );

    /**
     * @var array
     * @return string
     */
    protected $identifier = array('id');

    /**
     * @param array $values
     */
    public function setCity($values)
    {
        if (is_array($values)) {
            $city = Mandragora_Model::factory('City', $values);
            $this->properties['City'] = $city;
        } else {
            $this->properties['City'] = null;
        }
    }

    /**
     * @return array  Mandragora_Geocoder_Placemark
     */
    public function geocode()
    {
        $googleMapskey = Zend_Registry::get('googleMapsKey');
        $adapter = new Mandragora_Geocoder_Adapter($googleMapskey);
        $address = str_replace('|', ', ', (string)$this);
        $results = $adapter->lookup($address);
        return $results;
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
        $formater = new Edeco_Geocoder_PlaceMark_JsonFormater();
        $jsonPlaceMarkers = $formater->format($placeMarks);
        return $jsonPlaceMarkers;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->properties['streetAndNumber'] . ', '
             . $this->properties['neighborhood'] . ', '
             . $this->properties['City']->name . ', '
             . $this->properties['City']->State->name . ', México';
    }

   /**
    * @return string
    */
    public function toHtml()
    {
        $zipCode = $this->properties['zipCode'] != ''
        ? '<br />C. P. ' . $this->properties['zipCode']
        : '';
        return $this->properties['streetAndNumber'] . '<br />'
        . $this->properties['neighborhood'] . '<br />'
        . $this->properties['City']->name
        . ', ' . $this->properties['City']->State->name
        . ', México' . $zipCode;
    }

}