<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Model\Property;

use Zend_Date;

/**
 * Utility class for handling model's date properties
 */
class Date implements PropertyInterface
{
    /**
     * @var Zend_Date
     */
    protected $date;

    /**
     * @var string
     */
    protected $renderFormat;

    /**
     * @var string
     */
    protected $stringFormat;

    /**
     * @param string $date
     * @param string $renderFormat = Zend_Date::DATE_FULL
     * @param string $stringFormat = 'YYYY-MM-dd'
     */
    public function __construct(
        $date,
        $renderFormat = Zend_Date::DATE_FULL,
        $stringFormat = 'YYYY-MM-dd'
    )
    {
        $this->renderFormat = $renderFormat;
        $this->stringFormat = $stringFormat;
        $this->date = new Zend_Date($date, $this->stringFormat);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->date->toString($this->stringFormat);
    }

    /**
     * @return string
     */
    public function render()
    {
        return ucfirst($this->date->toString(Zend_Date::DATE_FULL));
    }
}
