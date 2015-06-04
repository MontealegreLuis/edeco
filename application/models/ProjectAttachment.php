<?php
class App_Model_ProjectAttachment
{
    /**
     * @var string
     */
    protected static $attachmentsDirectory;

    /**
     * @var Mandragora_Filter_FriendlyUrl
     */
    protected static $filter;

    /**
     * @var Mandragora_File
     */
    protected $fileHandler;

    /**
     * @var string
     */
    protected $fileName;

    /**
     * @var string
     */
    protected $fullName;

    /**
     * @param string $fileName
     * @param string $extension
     */
    protected function __construct($fileName, $extension = '')
    {
        if ($extension != '') {
            $this->fileName = self::getFilter()->filter($fileName) . '.'
                . $extension;
        } else {
            $this->fileName = $fileName;
        }
        $this->fullName = $this->getAttachmentsDirectory()
            . DIRECTORY_SEPARATOR . $this->fileName;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @return Mandragora_File
     */
    public function getFile($fileName = '')
    {

        return $fileName == ''
            ? new Mandragora_File($this->fullName)
            : new Mandragora_File(
                $this->getAttachmentsDirectory() . DIRECTORY_SEPARATOR
                . $fileName
            );
    }

    /**
     * @return void
     */
    public function deleteFile()
    {
        $this->getFile()->delete();
    }

    /**
     * @param string $newName
     */
    public function renameFile($newName)
    {
        $newName = $this->getAttachmentsDirectory() . DIRECTORY_SEPARATOR
                . $newName;
        $this->getFile()->rename($newName);
    }

    /**
     * @return Mandragora_Filter_FriendlyUrl
     */
    protected static function getFilter()
    {
        if (self::$filter == null) {
            self::$filter = new Mandragora_Filter_FriendlyUrl();
        }
        return self::$filter;
    }

    /**
     * @return string
     */
    public static function getAttachmentsDirectory()
    {
        if (self::$attachmentsDirectory == null) {
            self::$attachmentsDirectory =  APPLICATION_PATH
                . DIRECTORY_SEPARATOR . 'files'
                . DIRECTORY_SEPARATOR . 'power-point';
        }
        return self::$attachmentsDirectory;
    }

    /**
     * Use when updating the project
     *
     * @param string $fileName
     * @return Edeco_Model_ProjectAttachment
     */
    public static function retrieveAttachment($fileName)
    {
        return new App_Model_ProjectAttachment($fileName);
    }

    /**
     * Use when creating the project
     *
     * @param string $fileName
     * @param string $extension
     * @return Edeco_Model_ProjectAttachment
     */
    public static function createAttachment($fileName, $extension)
    {
        return new App_Model_ProjectAttachment($fileName, $extension);
    }

}