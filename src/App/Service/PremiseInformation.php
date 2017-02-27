<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use App\Model\PremiseInformation as PremiseInformationModel;
use Mandragora\Model\AbstractModel;
use Mandragora\Service\Crud\Doctrine\DoctrineCrud;

/**
 * Service class for Premise Information model
 */
class PremiseInformation extends DoctrineCrud
{
    /**
     * @return \App\Form\PremiseInformation\Detail
     */
    public function getPremiseForm(string $action)
    {
        $this->getForm('Detail')->setAction($action);
        return $this->getForm();
    }

    /**
     * @return void
     */
    public function sendEmailMessage(string $baseUrl)
    {
        $this->getModel()->fromArray($this->getForm()->getValues());
        $this->getModel()->sendEmailMessage($baseUrl);
    }

    public function getModel(array $values = null): AbstractModel
    {
        if (!$this->model) {
            $this->model = new PremiseInformationModel($values);
        }

        return $this->model;
    }
}
