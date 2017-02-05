<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use Mandragora\Service\Crud\Doctrine\AbstractDoctrine;
use Mandragora\Service;

/**
 * Service class for Contact model
 */
class Contact extends AbstractDoctrine
{
    /**
     * @param string $action
     * @return Edeco_Form_Contact
     */
    public function getContactForm($action)
    {
        $this->getForm('Detail')->setAction($action);
        return $this->getForm();
    }

    /**
     * @return string $baseUrl
     * @return void
     */
    public function sendEmailMessage($baseUrl)
    {
        $this->getModel($this->getForm()->getValues());
        $propertyId = $this->getForm()->getElement('propertyId')->getValue();
        $propertyName = null;
        if ($propertyId) {
            $propertyService = Service::factory(
                'Property'
            );
            $propertyService->setCacheManager($this->cacheManager);
            $propertyService->init();
            $property = $propertyService->retrievePropertyById($propertyId);
            $propertyName = $property->name;
        }
        $this->getModel()->sendEmailMessage($baseUrl, $propertyName);
    }

    protected function createForm($formName) {}

    public function getFormForCreating($action) {}

    public function getFormForEditing($action) {}
}
