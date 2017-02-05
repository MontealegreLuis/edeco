<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use Mandragora\Service\Crud\Doctrine\DoctrineCrud;

/**
 * Service class for Premise Information model
 */
class PremiseInformation extends DoctrineCrud
{
    /**
     * @param string $action
     * @return Edeco_Form_PremiseInformation
     */
    public function getPremiseForm($action)
    {
        $this->getForm('Detail')->setAction($action);
        return $this->getForm();
    }

    /**
     * @param string $baseUrl
     * @return void
     */
    public function sendEmailMessage($baseUrl)
    {
        $this->getModel()->fromArray($this->getForm()->getValues());
        $this->getModel()->sendEmailMessage($baseUrl);
    }

    protected function createForm($formName) {}

    public function getFormForCreating($action) {}

    public function getFormForEditing($action) {}
}
