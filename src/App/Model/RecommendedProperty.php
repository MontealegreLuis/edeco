<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model;

use Mandragora\Model\AbstractModel;

class RecommendedProperty extends AbstractModel
{
    /**
     * @var array
     */
    protected $properties = [
    	'propertyId' => null, 'recommendedPropertyId' => null,
    	'RecommendedProperty' => null
    ];

    /**
     * @var array
     */
    protected $identifier = ['propertyId', 'recommendedPropertyId'];

    /**
     * @param array | null $values
     */
    public function setRecommendedProperty($values = null)
    {
        if (is_array($values)) {
            $property = new Property($values);
            $this->properties['RecommendedProperty'] = $property;
        }
    }

    /**
     * @see Mandragora_String_Interface::__toString()
     */
    public function __toString()
    {
        return (string) $this->properties['RecommendedProperty'];
    }
}
