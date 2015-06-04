<?php
/**
 * Contains all the information related to the properties being sold or rented
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
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Contains all the information related to the properties being sold or rented
 *
 * @property integer $id
 * @property string $name
 * @property string $url
 * @property string $description
 * @property string $price
 * @property float $totalSurface
 * @property float $metersOffered
 * @property float $metersFront
 * @property enum $landUse
 * @property date $creationDate
 * @property enum $availabilityFor
 * @property integer $showOnWeb
 * @property string $contactName
 * @property string $contactPhone
 * @property string $contactCellphone
 * @property integer $categoryId
 * @property integer $version
 * @property Doctrine_Collection $Address
 * @property Doctrine_Collection $Picture
 *
 * @category   Application
 * @package    Edeco
 * @subpackage Model
 * @author     LNJ <lemuel.nonoal@mandragora-web-systems.com>
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class   App_Model_Property
extends Mandragora_Model_Abstract
{
    /**
     * @var array
     */
    protected $properties = array(
        'name' => null, 'url' => null, 'description' => null,
        'price' => null, 'totalSurface' => null, 'metersOffered' => null,
        'metersFront' => null, 'landUse' => null, 'creationDate' => null,
        'availabilityFor' => null, 'showOnWeb' => null, 'contactName' => null,
        'contactPhone' => null, 'contactCellphone' => null,
        'categoryId' => null, 'version' => null, 'Category' => null,
        'Address' => null, 'Picture' => null, 'RecommendedProperty' => null,
    );

    /**
     * @var array
     */
    protected $identifier = array('id');

    /**
     * @var Zend_Cache
     */
    protected $cache;

    /**
     * @param string $propertyName
     * @return void
     */
    public function setUrl($propertyName)
    {
        $url = new Mandragora_Model_Property_Url($this->properties['name']);
        $this->properties['url'] = $url;
    }

    /**
     * @param string $address
     */
    public function setAddress($values)
    {
        if (!is_null($values)) {
            $modelAddress = Mandragora_Model::factory('Address', $values);
            $this->properties['Address'] = $modelAddress;
        }
    }

    /**
     * Overridden setter for the property's picture
     *
     * @param array | Edeco_Model_Collection_Picture $pictureCollection
     * @return void
     */
    public function setPicture($pictures)
    {
        if (is_array($pictures)) {
            $pictures = new App_Model_Collection_Picture($pictures);
        } elseif (!($pictures instanceof App_Model_Collection_Picture)) {
            throw new InvalidArgumentException(
                'Expected array or App_Model_Collection_Picture'
            );
        }
        $this->properties['Picture'] = $pictures;
    }

    /**
     * @param string $creationDate
     */
    public function setCreationDate($creationDate)
    {
        $creationDate = new Mandragora_Model_Property_Date($creationDate);
        $this->properties['creationDate'] = $creationDate;
    }

    /**
     * @param string $showOnWeb
     */
    public function setShowOnWeb($showOnWeb)
    {
        $values = array('no', 'yes');
        $showOnWeb = new Mandragora_Model_Property_Boolean($showOnWeb, $values);
        $this->properties['showOnWeb'] = $showOnWeb;
    }

    /**
     * @param float $totalSurface
     * @return void
     */
    public function setTotalSurface($totalSurface)
    {
        $totalSurface = new Mandragora_Model_Property_SquareMeter($totalSurface);
        $this->properties['totalSurface'] = $totalSurface;
    }

    /**
     * @param float $metersOffered
     * @return void
     */
    public function setMetersOffered($metersOffered)
    {
        $metersOffered = new Mandragora_Model_Property_SquareMeter($metersOffered);
        $this->properties['metersOffered'] = $metersOffered;
    }

    /**
     * @param float $metersFront
     * @return void
     */
    public function setMetersFront($metersFront)
    {
        $metersFront = new Mandragora_Model_Property_Meter($metersFront);
        $this->properties['metersFront'] = $metersFront;
    }

    /**
     * @param string $phone
     * @return void
     */
    public function setContactPhone($phone)
    {
        if (!is_null($phone) && trim($phone) !== '') {
            $phone = new Mandragora_Model_Property_Telephone($phone);
            $this->properties['contactPhone'] = $phone;
        }
    }

    /**
     * @param string $phone
     * @return void
     */
    public function setContactCellphone($phone)
    {
        if (!is_null($phone) && trim($phone) !== '') {
            $mobile = '/(\d{5})(\d{2})(\d{2})(\d{2})(\d{2})/i';
            $phone = new Mandragora_Model_Property_Telephone($phone, $mobile);
            $this->properties['contactCellphone'] = $phone;
        }
    }

    /**
     * @param array $values
     * @return void
     */
    public function setCategory(array $values)
    {
        $category = Mandragora_Model::factory('Category', $values);
        $this->properties['Category'] = $category;
    }

    public function setRecommendedProperty(array $collection)
    {
        $collection = new App_Model_Collection_RecommendedProperty($collection);
        $this->properties['RecommendedProperty'] = $collection;
    }

    /**
     * @return void
     */
    public function prepareForShowing()
    {
        unset($this->properties['url']);
    }

    /**
     * @param array $properties
     * @return string
     */
    public function propertiesToJson(array $properties)
    {
        $jsonProperties = $this->getCache()->load('jsonProperties');
        if (!$jsonProperties) {
            $dtoProperties = array();
            $translator = Zend_Controller_Router_Route::getDefaultTranslator();
            $availability = App_Enum_PropertyAvailability::values();
            foreach ($properties as $property) {
                unset($property['price']);
                $property['availabilityFor'] =
                    $availability[$property['availabilityFor']];
                if (count($property['Picture']) > 0) {
                    $property['Picture'] = $property['Picture'][0];
                    $picture = new App_Model_Picture($property['Picture']);
                    unset($property['Picture']['id']);
                    unset($property['Picture']['propertyId']);
                    unset($property['Picture']['version']);
                    $pathToImage =
                        App_Model_PictureFileHandler::getPicturesDirectory()
                        . '/' . $property['Picture']['filename'];
                    $imageHandler = new Mandragora_Image($pathToImage);
                    $property['Picture']['height'] = $imageHandler->getHeight();
                    $property['Picture']['width'] = $imageHandler->getWidth();
                    $parts = explode('/', (string)$picture);
                    $property['Picture']['filename'] = $parts[count($parts) - 1];
                    $size = $picture->getThumbnailWidthAndHeight();
                    $property['Picture']['thumbHeight'] = $size['height'];
                    $property['Picture']['thumbWidth'] = $size['width'];
                    $dtoProperties[] = $property;
                }
            }
            $jsonProperties = Zend_Json::encode($dtoProperties);
            $this->getCache()->save(
                $jsonProperties, 'jsonProperties',
                array(Edeco_Paginator_Property::PROPERTIES_TAG,)
            );
        }
        return $jsonProperties;
    }

    /**
     * @return string
     */
    public function toJson()
    {
        $property = $this->toArray();
        $cacheId = 'jsonProperty' . $property['id'];
        $jsonProperty = $this->getCache()->load($cacheId);
        if (!$jsonProperty) {
            $propertyId = $property['id'];
            unset($property['id']);
            unset($property['url']);
            unset($property['description']);
            unset($property['price']);
            unset($property['Category']);
            unset($property['categoryId']);
            unset($property['totalSurface']);
            unset($property['metersOffered']);
            unset($property['metersFront']);
            unset($property['landUse']);
            unset($property['creationDate']);
            unset($property['availabilityFor']);
            unset($property['showOnWeb']);
            unset($property['contactName']);
            unset($property['contactPhone']);
            unset($property['contactCellphone']);
            unset($property['version']);
            $property['Picture'] =
                isset($property['Picture']) && is_array($property['Picture'])
                ? $property['Picture']
                : array();
            $pictures = array();
            foreach ($property['Picture'] as $picture) {
                //Transform to array to encode the picture as a JSON object
                $picture = $picture->toArray();
                unset($picture['propertyId']);
                $pathToImage =
                    App_Model_PictureFileHandler::getPicturesDirectory()
                    . '/' . $picture['filename'];
                $imageHandler = new Mandragora_Image($pathToImage);
                $picture['height'] = $imageHandler->getHeight();
                $picture['width'] = $imageHandler->getWidth();
                $pictures[] = $picture;
            }
            $property['Picture'] = $pictures;
            $address = $property['Address'];
            $property['Address'] = $address->toHtml();
            $property['latitude'] = $address->latitude;
            $property['longitude'] = $address->longitude;
            $jsonProperty = Zend_Json::encode($property);
            $this->getCache()->save(
                $jsonProperty, $cacheId,
                array(
                    'property' . $propertyId,
                    Edeco_Paginator_Property::PROPERTIES_TAG,
                )
            );
        }
        return $jsonProperty;
    }

    /**
     * @return Zend_Cache
     */
    protected function getCache()
    {
        if (!$this->cache) {
            $fc = Zend_Controller_Front::getInstance();
            $cache = $fc->getParam('bootstrap')
                        ->getResource('cachemanager')
                        ->getCache('gateway');
            $this->cache = $cache;
        }
        return $this->cache;
    }

    /**
     * @return void
     */
    public function audit()
    {
        $now = new Zend_Date();
        $this->properties['creationDate'] = $now->toString('yyyy-MM-dd');
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $property = '<strong>' . $this->properties['name'] . '</strong><br />';
        if ($this->properties['Address']) {
            $property .= $this->properties['Address']->toHtml() . '<br />';
        }
        if ($this->properties['Picture']) {
            $view = Zend_Layout::getMvcInstance()->getView();
            $property .= '<em>' . $this->properties['Picture'] . ' '
                      . $view->translate('pictures') . '</em><br />';
        }
        return $property;
    }

}