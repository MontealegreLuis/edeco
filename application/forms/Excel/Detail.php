<?php
/**
 * Form for creating excel files of properties information
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
 * @subpackage Form
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Form for creating excel files of properties information
 *
 * @category   Application
 * @package    Edeco
 * @subpackage Form
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class App_Form_Excel_Detail extends Mandragora_Form_Abstract
{
    /**
     * @return void
     */
    public function setDateRangeValidator()
    {
        $maxStopDate = new Zend_Date();
        $rangeValidator = new Mandragora_Validate_DateRange($maxStopDate);
        $rangeValidator->setMessages(
            array(
                Mandragora_Validate_DateRange::STOP_DATE_OUT_OF_BOUNDS =>
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