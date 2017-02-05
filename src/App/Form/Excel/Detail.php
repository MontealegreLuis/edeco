<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Form\Excel;

use Mandragora\Form\AbstractForm;
use Zend_Date;
use Mandragora\Validate\DateRange;

/**
 * Form for creating excel files of properties information
 */
class Detail extends AbstractForm
{
    /**
     * @return void
     */
    public function setDateRangeValidator()
    {
        $maxStopDate = new Zend_Date();
        $rangeValidator = new DateRange($maxStopDate);
        $rangeValidator->setMessages(
            array(
                DateRange::STOP_DATE_OUT_OF_BOUNDS =>
                    "'%stopDate%' debe ser una fecha anterior a '"
                    . $maxStopDate->toString('YYYY-MM-dd') . "'"
            )
        );
        $this->getElement('stopDate')->addValidator($rangeValidator);
    }

    /**
     * @return void
     */
    public function setStartDateForRangeValidator()
    {
        $startDateElement = $this->getElement('startDate');
        $stopDateElement = $this->getElement('stopDate');
        $startDate = $startDateElement->getValue();
        $validator = 'Mandragora_Validate_DateRange';
        $dateRangeValidator = $stopDateElement->getValidator($validator);
        $dateRangeValidator->setStartDate($startDate);
    }
}
