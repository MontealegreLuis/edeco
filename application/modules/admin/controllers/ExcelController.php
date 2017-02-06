<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Mandragora\Controller\Action\AbstractAction;
use Mandragora\Service;

/**
 * Excel controller
 */
class Admin_ExcelController extends AbstractAction
{
    /**
     * @var array
     */
    protected $validMethods = [
        'save' => ['method' => 'post'],
    ];


    /**
     * Initialize the service object
     *
     * @return void
     */
    public function init()
    {
        $this->service = Service::factory('Excel');
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