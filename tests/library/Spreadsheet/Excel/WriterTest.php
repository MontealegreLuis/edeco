<?php
/**
 * Unit tests for Spreadsheet_Excel_Writer class
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
 * @category   Tests
 * @package    Spreadsheet
 * @subpackage Test
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Spreadsheet_Excel_WriterTest::main');
}

require_once realpath(dirname(__FILE__) . '/../../bootstrap.php');

/**
 * Unit tests for Spreadsheet_Excel_Writer class
 *
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @version    SVN: $Id$
 * @copyright  Mandrágora Web-Based Systems 2010
 * @category   Tests
 * @package    Edeco
 * @subpackage Test
 */
class Spreadsheet_Excel_WriterTest extends ControllerTestCase
{
    const EXCEL_FILE_NAME = 'test.xls';

    protected $filename;

    /**
     * Executes all the available tests cases
     *
     * @return void
     */
    public static function main()
    {
        $suite = new PHPUnit_Framework_TestSuite(
            'Spreadsheet_Excel_WriterTest'
        );
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    /**
     * Setup application to run test cases
     *
     * @see tests/application/ControllerTestCase#setUp()
     */
    public function setUp()
    {
        parent::setUp();
        $this->filename =
            dirname(__FILE__) . DIRECTORY_SEPARATOR . self::EXCEL_FILE_NAME;

        if (Mandragora_File::exists($this->filename)) {

            $file = new Mandragora_File($this->filename);
            $file->delete();
        }
    }

    public function testCanSaveExcelFile()
    {
        // We give the path to our file here
        $workbook = new Spreadsheet_Excel_Writer($this->filename);
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
            Mandragora_File::exists($this->filename),
            'Excel file cannot be found in ' . $this->filename
        );
    }

}

if (PHPUnit_MAIN_METHOD == 'Spreadsheet_Excel_WriterTest::main') {
    Spreadsheet_Excel_WriterTest::main();
}