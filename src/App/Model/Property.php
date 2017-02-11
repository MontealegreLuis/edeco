<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model;

use Mandragora\Model\AbstractModel;
use Mandragora\Model\Property\Url;
use Mandragora\Model;
use App\Model\Collection\Picture as Pictures;
use InvalidArgumentException;
use Mandragora\Model\Property\Date;
use Mandragora\Model\Property\Boolean;
use Mandragora\Model\Property\SquareMeter;
use Mandragora\Model\Property\Meter;
use Mandragora\Model\Property\Telephone;
use App\Model\Collection\RecommendedProperty;
use Zend_Controller_Router_Route;
use App\Enum\PropertyAvailability;
use App\Model\Picture as Picture;
use Mandragora\Image;
use Zend_Json;
use Edeco\Paginator\Property as EdecoPaginatorProperty;
use Zend_Controller_Front;
use Zend_Date;
use Zend_Layout;

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
 */
class Property extends AbstractModel
{
    /**
     * @var array
     */
    protected $properties = [
        'name' => null, 'url' => null, 'description' => null,
        'price' => null, 'totalSurface' => null, 'metersOffered' => null,
        'metersFront' => null, 'landUse' => null, 'creationDate' => null,
        'availabilityFor' => null, 'showOnWeb' => null, 'contactName' => null,
        'contactPhone' => null, 'contactCellphone' => null,
        'categoryId' => null, 'version' => null, 'Category' => null,
        'Address' => null, 'Picture' => null, 'RecommendedProperty' => null,
    ];

    /**
     * @var array
     */
    protected $identifier = ['id'];

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
        $this->properties['url'] = new Url($this->properties['name']);
    }

    /**
     * @param string $address
     */
    public function setAddress($values)
    {
        if (!is_null($values)) {
            $modelAddress = Model::factory('Address', $values);
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
            $pictures = new Pictures($pictures);
        } elseif (!($pictures instanceof Pictures)) {
            throw new InvalidArgumentException(
                'Expected array or ' . Pictures::class
            );
        }
        $this->properties['Picture'] = $pictures;
    }

    /**
     * @param string $creationDate
     */
    public function setCreationDate($creationDate)
    {
        $this->properties['creationDate'] = new Date($creationDate);
    }

    /**
     * @param string $showOnWeb
     */
    public function setShowOnWeb($showOnWeb)
    {
        $this->properties['showOnWeb'] = new Boolean($showOnWeb, ['no', 'yes']);
    }

    /**
     * @param float $totalSurface
     * @return void
     */
    public function setTotalSurface($totalSurface)
    {
        $this->properties['totalSurface'] = new SquareMeter($totalSurface);
    }

    /**
     * @param float $metersOffered
     * @return void
     */
    public function setMetersOffered($metersOffered)
    {
        $this->properties['metersOffered'] = new SquareMeter($metersOffered);
    }

    /**
     * @param float $metersFront
     * @return void
     */
    public function setMetersFront($metersFront)
    {
        $this->properties['metersFront'] = new Meter($metersFront);
    }

    /**
     * @param string $phone
     * @return void
     */
    public function setContactPhone($phone)
    {
        if (!is_null($phone) && trim($phone) !== '') {
            $this->properties['contactPhone'] = new Telephone($phone);
        }
    }

    /**
     * @param string $phone
     * @return void
     */
    public function setContactCellphone($phone)
    {
        if (null !== $phone && trim($phone) !== '') {
            $mobile = '/(\d{5})(\d{2})(\d{2})(\d{2})(\d{2})/i';
            $this->properties['contactCellphone'] = new Telephone($phone, $mobile);
        }
    }

    /**
     * @param array $values
     * @return void
     */
    public function setCategory(array $values)
    {
        $category = Model::factory('Category', $values);
        $this->properties['Category'] = $category;
    }

    public function setRecommendedProperty(array $collection)
    {
        $this->properties['RecommendedProperty'] = new RecommendedProperty($collection);
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
            $dtoProperties = [];
            Zend_Controller_Router_Route::getDefaultTranslator();
            $availability = PropertyAvailability::values();
            foreach ($properties as $property) {
                unset($property['price']);
                $property['availabilityFor'] = $availability[$property['availabilityFor']];
                if (count($property['Picture']) > 0) {
                    $property['Picture'] = $property['Picture'][0];
                    $picture = new Picture($property['Picture']);
                    unset(
                        $property['Picture']['id'],
                        $property['Picture']['propertyId'],
                        $property['Picture']['version']
                    );
                    $pathToImage = PictureFileHandler::getPicturesDirectory()
                        . '/' . $property['Picture']['filename'];
                    $imageHandler = new Image($pathToImage);
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
                [EdecoPaginatorProperty::PROPERTIES_TAG,]
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
                $pathToImage = PictureFileHandler::getPicturesDirectory()
                    . '/' . $picture['filename'];
                $imageHandler = new Image($pathToImage);
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
                [
                    'property' . $propertyId,
                    EdecoPaginatorProperty::PROPERTIES_TAG,
                ]
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
        $this->properties['creationDate'] = (new Zend_Date())->toString('yyyy-MM-dd');
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
