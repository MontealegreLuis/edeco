<?php
/**
 * Validate a date range
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
 * @package    Mandragora
 * @subpackage Validate
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Validate a date range
 *
 * @category   Application
 * @package    Mandragora
 * @subpackage Validate
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class Mandragora_Validate_DateRange extends Zend_Validate_Abstract
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
    protected $_messageVariables = array(
        'startDate' => 'startDate',
        'stopDate' => 'stopDate',
        'maxStopDate' => 'maxStopDate',
    );

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