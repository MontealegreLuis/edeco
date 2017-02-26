<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Model\Property;

/**
 * Utility class for handling model's boolean properties
 */
class Boolean implements PropertyInterface
{
    /**
     * @var string
     */
    protected $value;

    /**
     * @var array
     */
    protected $labels;

    /**
     * @param int $value
     * @param array $labels
     */
    public function __construct($value, array $labels)
    {
        $this->value = $value;
        $this->labels = $labels;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->labels[$this->value];
    }

    /**
     * @return boolean
     */
    public function getValue()
    {
        return $this->value === '1';
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->value;
    }

    /**
     * @return string
     */
    public function render()
    {
        return $this->getLabel();
    }
}
