<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use App\Model\Excel as ExcelModel;
use Mandragora\Model\AbstractModel;
use Mandragora\Service\Crud\Doctrine\DoctrineCrud;
use Mandragora\Gateway;
use App\Model\Gateway\Cache\Property as PropertyGateway;
use App\Model\Collection\Property as PropertyCollection;
use App\Model\PropertyExcelWriter;

/**
 * Service class for Property model
 */
class Excel extends DoctrineCrud
{
    /**
     * @return void
     */
    public function init()
    {
    	$this->openConnection();
        $this->setGateway(new PropertyGateway(Gateway::factory('Property')));
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

    public function createExcelFile(string $startDate, string $stopDate): bool
    {
        $gateway = Gateway::factory('Property');
        $properties = $gateway->findPropertiesInDateRange($startDate, $stopDate);
        if (count($properties) > 0) {
            $properties = new PropertyCollection($properties);
            $this->getModel()->createExcelFile($startDate, $stopDate, $properties);
            return true;
        }
        return false;
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
     * @return \Mandragora\File
     * @throws \Mandragora\File\FileException
     */
    public function getExcelFileInformation($fileName)
    {
        $pathToFile = sprintf(
            '%s/%s', PropertyExcelWriter::getExcelFilesDirectory(),
            $fileName
        );
        return $this->getModel()->getExcelFileInformation($pathToFile);
    }

    /**
     * @param string $action
     * @return \Mandragora\Form\SecureForm
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

    public function getModel(array $values = null): AbstractModel
    {
        if (!$this->model) {
            $this->model = new ExcelModel($values);
        }

        return $this->model;
    }
}
