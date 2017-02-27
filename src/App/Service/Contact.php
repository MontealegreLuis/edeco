<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use App\Model\Contact as ContactModel;
use Mandragora\Model\AbstractModel;
use Mandragora\Service\Crud\Doctrine\DoctrineCrud;
use Mandragora\Service;

/**
 * Service class for Contact model
 */
class Contact extends DoctrineCrud
{
    /**
     * @param string $action
     * @return \App\Form\Contact\Detail
     */
    public function getContactForm($action)
    {
        $this->getForm('Detail')->setAction($action);
        return $this->getForm();
    }

    /**
     * @return void
     */
    public function sendEmailMessage(string $baseUrl)
    {
        $this->getModel($this->getForm()->getValues());
        $propertyId = $this->getForm()->getElement('propertyId')->getValue();
        $propertyName = null;
        if ($propertyId) {
            $propertyService = Service::factory('Property');
            $propertyService->setCacheManager($this->cacheManager);
            $propertyService->init();
            $property = $propertyService->retrievePropertyById($propertyId);
            $propertyName = $property->name;
        }
        $this->getModel()->sendEmailMessage($baseUrl, $propertyName);
    }

    public function getModel(array $values = null): AbstractModel
    {
        if (!$this->model) {
            $this->model = new ContactModel($values);
        }

        return $this->model;
    }
}
