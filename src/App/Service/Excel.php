<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use Mandragora\Service\Crud\Doctrine\AbstractDoctrine;
use Mandragora\Gateway;
use App\Model\Gateway\Cache\Property as AppModelGatewayCacheProperty;
use App\Model\Collection\Property as AppModelCollectionProperty;
use App\Model\PropertyExcelWriter;

/**
 * Service class for Property model
 */
class Excel extends AbstractDoctrine
{
    /**
     * @return void
     */
    public function init()
    {
    	$this->openConnection();
        $gateway = Gateway::factory('Property');
        $this->setGateway(new AppModelGatewayCacheProperty($gateway));
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
        $gateway = Gateway::factory('Property');
        $properties = $gateway->findPropertiesInDateRange($startDate, $stopDate);
        if (count($properties) > 0) {
            $properties = new AppModelCollectionProperty($properties);
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
            '%s/%s', PropertyExcelWriter::getExcelFilesDirectory(),
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
