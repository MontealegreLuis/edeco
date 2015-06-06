<?php
/**
 * Service class for Excel files
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
 * @subpackage Service
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Service class for Property model
 *
 * @category   Application
 * @package    Edeco
 * @subpackage Service
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class App_Service_Excel extends Mandragora_Service_Crud_Doctrine_Abstract
{
    /**
     * @return void
     */
    public function init()
    {
    	$this->openConnection();
        $gateway = Mandragora_Gateway::factory('Property');
        $this->setGateway(new App_Model_Gateway_Cache_Property($gateway));
    }

    /**
     * @param array $dateRange
     * @return boolean
     */
    public function isExcelFormValid(array $dateRange)
    {
        $this->getForm()->populate($dateRange);
        $this->getForm()->setStartDateForRangeValidator();
        return $this->getForm()->isValid($dateRange);
    }

    /**
     * @param array $dateRange
     * @return array
     */
    public function formatDateRange(array $dateRange)
    {
        $dateRange['startDate'] = str_replace('/', '-', $dateRange['startDate']);
        $dateRange['stopDate'] = str_replace('/', '-', $dateRange['stopDate']);
        return $dateRange;
    }

    /**
     * @param string $startDate
     * @param string $stopDate
     * @return boolean
     */
    public function createExcelFile($startDate, $stopDate)
    {
        $gateway = Mandragora_Gateway::factory('Property');
        $properties = $gateway->findPropertiesInDateRange($startDate, $stopDate);
        if (count($properties) > 0) {
            $properties = new App_Model_Collection_Property($properties);
            $this->getModel()
                 ->createExcelFile($startDate, $stopDate, $properties);
            return true;
        } else  {
            return false;
        }
    }

    /**
     * @return array
     */
    public function getListOfExcelFiles()
    {
        return $this->getModel()->getListOfExcelFiles();
    }

    /**
     * @param string $fileName
     * @return Mandragora_File
     * @throws Mandragora_File_Exception
     */
    public function getExcelFileInformation($fileName)
    {
        $pathToFile = sprintf(
            '%s/%s', App_Model_PropertyExcelWriter::getExcelFilesDirectory(),
            $fileName
        );
        return $this->getModel()->getExcelFileInformation($pathToFile);
    }

    /**
     * @param string $action
     * @return Mandragora_Form_Abstract
     */
    public function getFormForCreating($action)
    {
        $this->getForm('Detail');
        $this->getForm()->setAction($action);
        return $this->getForm();
    }

    /**
     * Not implemented
     *
     * @param string $action
     * @return void
     */
    public function getFormForEditing($action) {}

    /**
     * Not implemented
     *
     * @param string $formName
     * @return void
     */
    public function createForm($formName) {}

}