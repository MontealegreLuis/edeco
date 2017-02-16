<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Form\Excel;

use Mandragora\Form\AbstractForm;
use Mandragora\Validate\DateRange;
use Zend_Date as Date;

/**
 * Form for creating excel files of properties information
 */
class Detail extends AbstractForm
{
    /**
     * @return void
     * @throws \Zend_Form_Exception
     * @throws \Zend_Date_Exception
     */
    public function setDateRangeValidator()
    {
        $maxStopDate = new Date();
        $rangeValidator = new DateRange($maxStopDate);
        $rangeValidator->setMessages([
            DateRange::STOP_DATE_OUT_OF_BOUNDS => sprintf(
                '\'%%stopDate%\' debe ser una fecha anterior a \'%s\'',
                $maxStopDate->toString('YYYY-MM-dd')
            )
        ]);
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
        $dateRangeValidator = $stopDateElement->getValidator(DateRange::class);
        $dateRangeValidator->setStartDate($startDate);
    }
}
