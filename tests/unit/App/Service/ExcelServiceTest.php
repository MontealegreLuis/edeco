<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use App\Model\Excel;
use App\Service\Excel as ExcelService;
use PHPUnit_Framework_TestCase as TestCase;

class ExcelServiceTest extends TestCase
{
    /** @test */
    function it_creates_an_excel_model()
    {
        $this->assertInstanceOf(Excel::class, $this->excelService->getModel());
    }

    /** @before */
    function createService()
    {
        $this->excelService = new ExcelService('Excel');
    }

    /** @var ExcelService */
    private $excelService;
}
