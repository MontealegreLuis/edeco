<?php
/**
 * Contains all the information related to the Address
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
 * @subpackage Model
 * @author     LNJ <lemuel.nonoal@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
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
 * @version    SVN: $Id$
 * @copyright  Mandrágora Web-Based Systems 2010
 * @category   Application
 * @package    Edeco
 * @subpackage Model
 */
class      App_Model_Address
extends    Mandragora_Model_Abstract
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