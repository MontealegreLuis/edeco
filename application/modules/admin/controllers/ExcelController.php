<?php
/**
 * Application's excel controller
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
 * @subpackage Controller
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems
 * @version    SVN $Id$
 */

/**
 * Excel controller
 *
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @version    SVN $Id$
 * @copyright  Mandrágora Web-Based Systems
 * @category   Application
 * @package    Edeco
 * @subpackage Controller
 */
class Admin_ExcelController extends Mandragora_Controller_Action_Abstract
{
    /**
     * @var array
     */
    protected $validMethods = array(
        'save' => array('method' => 'post'),
    );


    /**
     * Initialize the service object
     *
     * @return void
     */
    public function init()
    {
        $this->service = Mandragora_Service::factory('Excel');
        $this->service->setCacheManager($this->getCacheManager());
        $doctrine = $this->getInvokeArg('bootstrap')->getResource('doctrine');
        $this->service->setDoctrineManager($doctrine);
        $actions = $this->_helper->actionsBuilder($this->getRequest());
        $this->view->actions = $actions;
    }

    /**
     * List all the available Excel files
     *
     * @return void
     */
    public function listAction()
    {
        $this->view->excelFiles = $this->service->getListOfExcelFiles();
    }

    /**
     * Generate excel files of properties data
     *
     * @return void
     */
    public function createAction()
    {
        $action = $this->view->url(array('action' => 'save'), 'controllers');
        $excelForm = $this->service->getFormForCreating($action);
        $this->view->excelForm = $excelForm;
    }

    /**
     * Create the excel file if properties were found for the given range
     *
     * @return void
     */
    public function saveAction()
    {
    	$this->service->openConnection();
        $dateRange = $this->service->formatDateRange($this->post());
        $action = $this->view->url(array('action' => 'save'), 'controllers');
        $excelForm = $this->service->getFormForCreating($action);
        $excelForm->setDateRangeValidator();
        if ($this->service->isExcelFormValid($dateRange)) {
            $startDate = $dateRange['startDate'];
            $stopDate = $dateRange['stopDate'];
            $isfileCreated = $this->service
                                  ->createExcelFile($startDate, $stopDate);
            if (!$isfileCreated) {
                $this->flash('error')->addMessage('excel.noPropertiesFound');
                $params = array($this->view->translate('page') => 1);
                $this->redirectToRoute('list', $params);
            } else {
                $this->flash('success')->addMessage('excel.created');
                $fileName = sprintf('%s_%s.xls', $startDate, $stopDate);
                $params = array(
                    $this->view->translate('filename') => $fileName,
                );
                $this->redirectToRoute('show', $params);
            }
        } else {
            $this->view->excelForm = $excelForm;
            $this->renderScript('excel/create.phtml');
        }
    }

    /**
     * Show the information of an Excel file
     *
     *  @return void
     */
    public function showAction()
    {
        $fileName = $this->param($this->view->translate('filename'));
        $path = APPLICATION_PATH . '/files/excel/' . $fileName;
        if (Mandragora_File::exists($path)) {
            $excelFile = $this->service->getModel();
            $excelFile->fromFilename($fileName);
            $this->view->excelFile = $excelFile;
        } else {
            $this->flash('error')->addMessage('excel.fileNotFound');
            $params = array($this->view->translate('page') => 1);
            $this->redirectToRoute('list', $params);
        }
    }

    /**
     * Download excel file to local drive
     */
    public function downloadAction()
    {
        $fileName = $this->param($this->view->translate('filename'));
        $file = $this->service->getExcelFileInformation($fileName);
        $name = $file->getNameAndExtension();
        $this->getResponse()
             ->setHeader('Content-type', 'application/octet-stream')
             ->setHeader('Cache-Control', 'public', true)
             ->setHeader('Pragma', '', true)
             ->setHeader('Content-Disposition',
                "attachment; filename=\"$name\"\n"
             )
             ->setBody($file->read())
             ->sendResponse();
        die();
    }

    /**
     * Delete a given Excel File
     *
     * @return void
     */
    public function deleteAction()
    {
        $fileName = $this->param($this->view->translate('filename'));
        $file = $this->service->getExcelFileInformation($fileName);
        $file->delete();
        $this->flash('error')->addMessage('excel.deleted');
        $this->redirectToRoute('list', array());
    }

}