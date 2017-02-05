<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Validate;

use Zend_Validate_Abstract;
use Zend_Date;
use Zend_Validate_Date;

/**
 * Validate a date range
 */
class DateRange extends Zend_Validate_Abstract
{
    const INVALID_START_DATE = 'invalidStartDate';
    const INVALID_STOP_DATE = 'invalidStopDate';
    const STOP_DATE_OUT_OF_BOUNDS = 'stopDateOutOfBound';
    const INVALID_RANGE_DATE = 'invalidRangeDate';

    /**
     * @var string
     */
    protected $startDate;

    /**
     * @var string
     */
    protected $stopDate;

    /**
     * @var string
     */
    protected $maxStopDate;

    /**
     * @var Zend_Validate_Date
     */
    protected $dateValidator;

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID_START_DATE =>
            "'%startDate%' no es una fecha inicial válida",
        self::INVALID_STOP_DATE =>
            "'%stopDate%' no es una fecha final válida",
        self::INVALID_RANGE_DATE =>
            "'%startDate%' debe ser una fecha anterior a '%stopDate%'",
        self::STOP_DATE_OUT_OF_BOUNDS =>
            "'%stopDate%' debe ser una fecha anterior a '%maxStopDate%'",
    );

    /**
     * @var array
     */
    protected $_messageVariables = [
        'startDate' => 'startDate',
        'stopDate' => 'stopDate',
        'maxStopDate' => 'maxStopDate',
    ];

    public function __construct(Zend_Date $maxStopDate = null)
    {
        $this->dateValidator = new Zend_Validate_Date(
            array('format' => 'YYYY-MM-dd')
        );
        $this->maxStopDate = $maxStopDate;
    }

    /**
     * @param string $startDate
     * @return void
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @param string $value
     * @return boolean
     */
    public function isValid($value)
    {
        $this->stopDate = $value;
        if (!$this->dateValidator->isValid($this->startDate)) {
            $this->_error(self::INVALID_START_DATE);
            return false;
        }
        if (!$this->dateValidator->isValid($this->stopDate)) {
            $this->_error(self::INVALID_STOP_DATE);
            return false;
        }
        $startDate = new Zend_Date($this->startDate, 'YYYY-MM-dd');
        $stopDate = new Zend_Date($this->stopDate, 'YYYY-MM-dd');
        if ($this->maxStopDate != null
            && $stopDate->compare($this->maxStopDate) > 0) {
            $this->_error(self::STOP_DATE_OUT_OF_BOUNDS);
            return false;
        }
        if ($startDate->compare($stopDate) > 0) {
            $this->_error(self::INVALID_RANGE_DATE);
            return false;
        }
        return true;
    }
}
