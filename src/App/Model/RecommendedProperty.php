<?php
class   App_Model_RecommendedProperty
extends Mandragora_Model_Abstract
{
    /**
     * @var array
     */
    protected $properties = array(
    	'propertyId' => null, 'recommendedPropertyId' => null,
    	'RecommendedProperty' => null
    );

    /**
     * @var array
     */
    protected $identifier = array('propertyId', 'recommendedPropertyId');

    /**
     * @param array | null $values
     */
    public function setRecommendedProperty($values = null)
    {
        if (is_array($values)) {
            $property = new App_Model_Property($values);
            $this->properties['RecommendedProperty'] = $property;
        }
    }

    /**
     * @see Mandragora_String_Interface::__toString()
     */
    public function __toString()
    {
        return (string)$this->properties['RecommendedProperty'];
    }

}