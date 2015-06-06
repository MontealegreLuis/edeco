<?php
/**
 * Handles read/write and delete/rename operations on files
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
 * @category   Library
 * @package    Mandragora
 * @subpackage File
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Handles read/write and delete/rename operations on files
 *
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems
 * @version    SVN: $Id$
 * @category   Library
 * @package    Mandragora
 * @subpackage File
 * @history    26 may 2010
 *             LMV
 *             - Class creation
 */
class Mandragora_File
{
    /**
     * @var string
     */
    private $_nameAndExtension;

    /**
     * @var string
     */
    private $_directoryName;

    /**
     * @var string
     */
    private $_name;

    /**
     * @var string
     */
    private $_extension;

    /**
     * @var string
     */
    private $_fullName;

    /**
     * @param string $filename
     *      The full path to the file to be handled
     * @return void
     * @throws Mandragora_File_Exception
     *      Thrown if the file cannot be found in the file system
     */
    public function __construct($filename)
    {
        if (!self::exists((string)$filename)) {
            throw new Mandragora_File_Exception(
                'The file ' . $filename . ' does not exist'
            );
        }
        $fileInfo = pathinfo((string)$filename);
        $this->_fullName = (string)$filename;
        $this->_nameAndExtension = $fileInfo['basename'];
        $this->_directoryName = $fileInfo['dirname'];
        $this->_name = $fileInfo['filename'];
        if (isset($fileInfo['extension'])) {
            $this->_extension = $fileInfo['extension'];
        } else {
            $parts = explode('.', $fileInfo['basename']);
            $this->_extension = $parts[count($parts) - 1];
        }
    }

    /**
     * @return string
     */
    public function getNameAndExtension()
    {
        return $this->_nameAndExtension;
    }

    /**
     * @return string
     */
    public function getDirectoryName()
    {
        return $this->_directoryName;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return $this->_extension;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->_fullName;
    }

    /**
     * Read the file contents
     *
     * @return string
     *      The content of the file
     * @throws Mandragora_File_Exception
     *      Thrown if the file cannot be read
     */
    public function read()
    {
        if (!is_readable($this->_fullName)) {
            throw new Mandragora_File_Exception(
                "The file $this->_fullName cannot be read"
            );
        }
        return file_get_contents($this->_fullName);
    }

    /**
     * Creates or modifies the content of the file
     *
     * @param string $content
     *      The content of the file
     * @return boolean
     *      True if the file is successfully modified
     * @throws Mandragora_File_Exception
     *      Thrown if the file cannot be written
     */
    public function write($content)
    {
        if (!is_writable($this->_fullName)) {

            throw new Mandragora_File_Exception(
                'The file ' . $this->_fullName . ' cannot be written'
            );
        }
        return file_put_contents($this->_fullName, $content);
    }

    /**
     * @return boolean
     *      True if the file is successfully deleted
     */
    public function delete()
    {
        return unlink($this->_fullName);
    }

    /**
     * @param string $newName
     * @return boolean
     *      True if the file can be renamed
     */
    public function rename($newName)
    {
        return rename($this->_fullName, (string)$newName);
    }

    /**
     * Get the time of the file's last modification
     *
     * @return int
     */
    public function getLastModifiedTime()
    {
        return filemtime($this->_fullName);
    }

    /**
     * @return Mandragora_File_Size_Abstract
     */
    public function getSize()
    {
        $sizeInBytes = filesize($this->_fullName);
        return Mandragora_File_Size_Factory::create($sizeInBytes);
    }

    /**
     * Verifies if a file exists or not
     *
     * @param string $filename
     * @return boolean
     */
    public static function exists($filename)
    {
        return file_exists((string)$filename);
    }

    /**
     * @param string $filename
     * @return Mandragora_File
     * @throws Mandragora_File_Exception
     *      If file cannot be created
     */
    public static function create($filename)
    {
        if (file_put_contents((string)$filename, '') === false) {
            throw new Mandragora_File_Exception(
                "The file $filename cannot be created"
            );
        } else {
            return new Mandragora_File((string)$filename);
        }
    }

}