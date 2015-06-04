<?php
class App_Model_Excel extends Mandragora_Model_Abstract
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
        $startDate = new Mandragora_Model_Property_Date($startDate);
        $this->properties['startDate'] = $startDate;
    }

    /**
     * @param string $startDate
     * @return void
     */
    public function setStopDate($stopDate)
    {
        $stopDate = new Mandragora_Model_Property_Date($stopDate);
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
        $startDate, $stopDate, App_Model_Collection_Property $properties
    )
    {
        $excelWriter = new App_Model_PropertyExcelWriter();
                    Zend_Debug::dump($excelWriter);
        $excelWriter->saveFile($startDate, $stopDate, $properties);
    }

    /**
     * @return array
     */
    public function getListOfExcelFiles()
    {
        $excelWriter = new App_Model_PropertyExcelWriter();
        return $excelWriter->listExcelFiles();
    }

    /**
     * @param string $fileName
     * @return Mandragora_File
     * @throws Mandragora_File_Exception
     */
    public function getExcelFileInformation($fileName)
    {
        return new Mandragora_File($fileName);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return '';
    }

}