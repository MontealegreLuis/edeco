<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Form\Excel;

use Mandragora\FormFactory;
use Mandragora\Validate\DateRange;
use PHPUnit_Framework_TestCase as TestCase;
use Zend_Date as Date;

class DetailTest extends TestCase
{
    /** @test */
    function it_can_be_created()
    {
        $this->assertInstanceOf(Detail::class, $this->excelForm);
        $this->assertCount(3, $this->excelForm->getElements());
    }

    /** @test */
    function it_rejects_an_out_of_bounds_stop_date()
    {
        $yesterday = new Date(['year' => 2017, 'month' => 2, 'day' => 18]);
        $today = new Date(['year' => 2017, 'month' => 2, 'day' => 19]);
        $tomorrow = new Date(['year' => 2017, 'month' => 2, 'day' => 20]);
        $this->excelForm->getElement('startDate')->setValue($yesterday->toString('YYYY-MM-dd'));

        $this->excelForm->setDateRangeValidator($today);
        $this->excelForm->setStartDateForRangeValidator();
        $validator = $this->excelForm->getElement('stopDate')->getValidator(DateRange::class);

        $this->assertFalse($validator->isValid($tomorrow->toString('YYYY-MM-dd')));
        $this->assertArrayHasKey(DateRange::STOP_DATE_OUT_OF_BOUNDS, $validator->getMessages());
    }

    /** @before */
    function createForm()
    {
        $this->excelForm = (new FormFactory(true))->create("Detail", "Excel");
    }

    /** @var Detail */
    private $excelForm;
}
