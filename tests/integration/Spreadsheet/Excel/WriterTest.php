<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Spreadsheet\Excel;

use Mandragora\File;
use PHPUnit_Framework_TestCase as TestCase;

class WriterTest extends TestCase
{
    private const EXCEL_FILE_NAME = 'test.xls';

    protected $filename;

    /**
     * Setup application to run test cases
     *
     * @see tests/application/ControllerTestCase#setUp()
     */
    public function setUp()
    {
        parent::setUp();
        $this->filename = __DIR__ . DIRECTORY_SEPARATOR . self::EXCEL_FILE_NAME;

        if (File::exists($this->filename)) {
            (new File($this->filename))->delete();
        }
    }

    public function testCanSaveExcelFile()
    {
        // We give the path to our file here
        $workbook = new Writer($this->filename);
        // This line is needed for encoding utf-8 strings
        $workbook->setVersion(8);

        $formatBold = $workbook->addFormat();
        $formatBold->setBold();
        $formatBold->setFontFamily('Arial');

        $formatCenter = $workbook->addFormat();
        $formatCenter->setAlign('center');
        $formatCenter->setFontFamily('Arial');

        $formatDate = $workbook->addFormat();
        $formatDate->setNumFormat('DD-MMM-YYYY');

        $formatPrice = $workbook->addFormat();
        $formatPrice->setNumFormat('#,##0.00_ ;-#,##0.00 ');

        $workSheet = $workbook->addWorksheet('My first worksheet');
        $workSheet->setInputEncoding("UTF-8");
        $workSheet->setColumn(0, 1, 16); //Set column width
        $workSheet->write(
            0, 0, 'EDIFICACIONES Y DESARROLLOS COMERCIALES DE MÉXICO',
            $formatBold
        );

        $workSheet->write(1, 0, 'Name', $formatBold);
        $workSheet->write(1, 1, 'Age', $formatBold);
        $workSheet->write(1, 2, 'Price', $formatBold);
        $workSheet->write(1, 3, 'Date', $formatBold);

        $workSheet->write(2, 0, 'John Smith', $formatCenter);
        $workSheet->writeNumber(2, 1, 30);
        $workSheet->write(2, 2, '1234.342', $formatPrice);
        $workSheet->write(2, 3, '12-03-2010', $formatDate);

        $workSheet->write(3, 0, 'Johann Schmidt', $formatCenter);
        $workSheet->writeNumber(3, 1, 31);
        $workSheet->write(3, 2, '8967.342', $formatPrice);
        $workSheet->write(3, 3, '12-08-2010', $formatDate);

        $workSheet->write(4, 0, 'Juan Herrera', $formatCenter);
        $workSheet->writeNumber(4, 1, 32);
        $workSheet->write(4, 2, '678901.342', $formatPrice);
        $workSheet->write(4, 3, '10-08-2010', $formatDate);

        // We still need to explicitly close the workbook
        $workbook->close();

        $this->assertTrue(
            File::exists($this->filename),
            "Excel file cannot be found in $this->filename"
        );
    }
}
