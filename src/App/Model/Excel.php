<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model;

use Mandragora\Model\AbstractModel;
use Mandragora\Model\Property\Date;
use App\Model\Collection\Property;
use App\Model\PropertyExcelWriter;
use Zend_Debug;
use Mandragora\File;

class Excel extends AbstractModel
{
    /**
     * @var array
     */
    protected $properties = array(
        'startDate' => null, 'stopDate' => null, 'filename' => null,
    );

    /**
     * @param string $filename
     */
    public function fromFilename($filename)
    {
        $parts = explode('_', $filename);
        $startDate = $parts[0];
        $subParts = explode('.', $parts[1]);
        $stopDate = $subParts[0];
        $this->setStartDate($startDate);
        $this->setStopDate($stopDate);
        $this->filename = $filename;
    }

    /**
     * @param string $startDate
     * @return void
     */
    public function setStartDate($startDate)
    {
        $startDate = new Date($startDate);
        $this->properties['startDate'] = $startDate;
    }

    /**
     * @param string $startDate
     * @return void
     */
    public function setStopDate($stopDate)
    {
        $stopDate = new Date($stopDate);
        $this->properties['stopDate'] = $stopDate;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->properties['filename'];
    }

    /**
     * @param string $startDate
     * @param string $stopDate
     * @param Edeco_Model_Collection_Property $properties
     */
    public function createExcelFile(
        $startDate, $stopDate, Property $properties
    )
    {
        $excelWriter = new PropertyExcelWriter();
                    Zend_Debug::dump($excelWriter);
        $excelWriter->saveFile($startDate, $stopDate, $properties);
    }

    /**
     * @return array
     */
    public function getListOfExcelFiles()
    {
        $excelWriter = new PropertyExcelWriter();
        return $excelWriter->listExcelFiles();
    }

    /**
     * @param string $fileName
     * @return Mandragora_File
     * @throws Mandragora_File_Exception
     */
    public function getExcelFileInformation($fileName)
    {
        return new File($fileName);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return '';
    }
}
