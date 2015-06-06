<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright  Mandrágora Web-Based Systems 2010-2015 (http://www.mandragora-web-systems.com)
 */
class App_Model_PropertyExcelWriter
{
    /**
     * @var string
     */
    protected static $excelFilesDirectory;

    /**
     * @var array
     */
    protected static $labelLandUse;

    /**
     * @var Zend_Filter_StripTags
     */
    protected static $tagsFilter;

    /**
     * @var Zend_View
     */
    protected static $view;

    /**
     * @var Spreadsheet_Excel_Writer
     */
    protected $workbook;

    public function __construct()
    {
        if (self::$labelLandUse == null) {
            self::$labelLandUse = App_Enum_PropertyLandUse::values();
            self::$tagsFilter = new Zend_Filter_StripTags();
            self::$view = Zend_Layout::getMvcInstance()->getView();
        }
    }

    /**
     * @param string $startDate
     * @param string $stopDate
     * @param App_Model_Collection_Property $properties
     */
    public function saveFile($startDate, $stopDate, App_Model_Collection_Property $properties)
    {
        $filename = self::getExcelFilesDirectory() . DIRECTORY_SEPARATOR
            . $startDate . '_' . $stopDate . '.xls';
        $this->workbook = new Spreadsheet_Excel_Writer($filename);
        $this->workbook->setVersion(8); // Add UTF-8 support
        $formatHeader = $this->workbook->addFormat();
        $formatHeader->setBold();
        $formatHeader->setAlign('center');
        $formatHeader->setFontFamily('Arial');
        $workSheet = $this->workbook->addWorksheet('Hoja de censo');
        $workSheet->setInputEncoding("UTF-8");
        $workSheet->setColumn(0, 0, 10); // Set creation date column width
        $workSheet->setColumn(1, 1, 15); // Set category column width
        $workSheet->setColumn(2, 2, 15); // Set land use column width
        $workSheet->setColumn(3, 3, 50); // Set address column width
        $workSheet->setColumn(4, 4, 15); // Set city column width
        $workSheet->setColumn(5, 5, 35); // Set address reference column width
        $workSheet->setColumn(6, 6, 17); // Set total surface column width
        $workSheet->setColumn(7, 7, 35); // Set price column width
        $workSheet->setColumn(8, 8, 20); // Set availability  column width
        $workSheet->setColumn(9, 9, 25); // Set name contat column width
        $workSheet->setColumn(10, 10, 30); // Set contact phone column width
        $workSheet->setColumn(11, 11, 35); // Set contact cell phone column width
        $workSheet->write(
            0, 0, 'EDIFICACIONES Y DESARROLLOS COMERCIALES DE MÉXICO',
            $formatHeader
        );
        $workSheet->setMerge(0, 0, 0, 7);
        $workSheet->write(1, 0, 'FECHA', $formatHeader);
        $workSheet->write(1, 1, 'PROPIEDAD', $formatHeader);
        $workSheet->write(1, 2, 'USO DE SUELO', $formatHeader);
        $workSheet->write(1, 3, 'UBICACIÓN', $formatHeader);
        $workSheet->write(1, 4, 'CIUDAD', $formatHeader);
        $workSheet->write(1, 5, 'ZONA', $formatHeader);
        $workSheet->write(1, 6, 'SUPERFICIE m2', $formatHeader);
        $workSheet->write(1, 7, 'PRECIO', $formatHeader);
        $workSheet->write(1, 8, 'DISPONIBLE PARA', $formatHeader);
        $workSheet->write(1, 9, 'NOMBRE DEL CONTACTO', $formatHeader);
        $workSheet->write(1, 10, 'TELEFONO DEL CONTACTO', $formatHeader);
        $workSheet->write(1, 11, 'TELEFONO CELULAR DEL CONTACTO', $formatHeader);
        $i = 2;
        foreach ($properties as $property) {
            if ($property->Address == null) {
                $address = '';
                $city = '';
            } else {
                $address = $property->Address;
                $city = $property->Address->City->name;
            }
            $workSheet->write($i, 0, $property->creationDate);
            $workSheet->write($i, 1, $property->Category->name);
            $workSheet->write($i, 2, self::$labelLandUse[$property->landUse]);
            $workSheet->write($i, 3, $address);
            $workSheet->write($i, 4, $city);
            $workSheet->write(
                $i, 5, self::$tagsFilter->filter($property->Address->addressReference)
            );
            $phone = $property->contactPhone
                ? $property->contactPhone->render()
                : '';
            $cell = $property->contactCellphone
                ? $property->contactCellphone->render()
                : '';
            $workSheet->write($i, 6, (string)$property->totalSurface);
            $workSheet->write($i, 7, self::$tagsFilter->filter($property->price));
            $workSheet->write($i, 8, self::$view->translate($property->availabilityFor));
            $workSheet->write($i, 9, $property->contactName);
            $workSheet->write($i, 10, $phone);
            $workSheet->write($i, 11, $cell);
            $i++;
        }
        $this->workbook->close(); // Write the file
    }

    /**
     * @return array
     */
    public function listExcelFiles()
    {
        $excelFiles = array();
        $directory = new DirectoryIterator(self::getExcelFilesDirectory());
        foreach($directory as $fileInfo) {
            if(!$fileInfo->isDot() && !$fileInfo->isDir()) {
                $file = new Mandragora_File($fileInfo->getRealPath());
                if ($file->getExtension() == 'xls') {
                    $excelFiles[] = $file;
                }
            }
        }
        return $excelFiles;
    }

    /**
     * @return string
     */
    public static function getExcelFilesDirectory()
    {
        if (self::$excelFilesDirectory == null) {
            self::$excelFilesDirectory = APPLICATION_PATH . '/files/excel';
        }
        return self::$excelFilesDirectory;
    }

}