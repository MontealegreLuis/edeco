<?php
/**
 * Form for adding/updating projects
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
 * Form for adding/updating projects
 *
 * @category   Application
 * @package    Edeco
 * @subpackage Form
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class App_Form_Project_Detail extends Mandragora_Form_Crud_Abstract
{
    public function init()
    {
        $validator = $this->getElement('attachment')->getValidator('Upload');
        $validator->setMessages(
            array(
                Zend_Validate_File_Upload::FILE_NOT_FOUND =>
                    'project.attachment.fileUploadErrorFileNotFound'
            )
        );
    }

    /**
     * @param string $newName
     */
    public function saveAttachmentFile($newName)
    {
        $powerPointFile = $this->getElement('attachment');
        $powerPointFile->addFilter('Rename',
            array(
                'source' => '*',
                'target' => $newName,
                'overwrite' => true
            )
        );
        $powerPointFile->receive();
    }

    /**
     * @return boolean
     */
    public function hasNewPowerPoint()
    {
        return $this->getElement('attachment')->getValue() != null;
    }

    /**
     * @return string
     */
    public function getAttachmentOriginalFileName()
    {
        return $this->getElement('attachment')->getValue();
    }

    /**
     * @return void
     */
    public function prepareForCreating()
    {
        $this->removeElement('id');
        $this->removeElement('version');
    }

    /**
     * @return void
     */
    public function prepareForEditing() {}

}