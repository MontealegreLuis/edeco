<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Form\Property;

use Mandragora\Form\Crud\CrudForm;
use Mandragora\Validate\Db\Doctrine\NoRecordExists;

/**
 * Property's form
 */
class Detail extends CrudForm
{
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
    public function prepareForEditing()
    {
        $this->getElement('name')->removeValidator(NoRecordExists::class);
    }

    /**
     * @return void
     */
    public function setCategories(array $categories)
    {
        $haystack = [];
        $options = ['' => 'form.emptyOption'];
        foreach ($categories as $category) {
            $haystack[] = $category['id'];
            $options[$category['id']] = $category['name'];
        }
        /** @var \Zend_Form_Element_Select $category */
        $category = $this->getElement('categoryId');
        $category->setMultiOptions($options);

        /** @var \Zend_Validate_InArray $validator */
        $validator = $category->getValidator('InArray');
        $validator->setHaystack($haystack);
    }

    /**
     * @return void
     */
    public function setAvailabilities(array $availabilities)
    {
        /** @var \Zend_Form_Element_Select $availabilityFor */
        $availabilityFor = $this->getElement('availabilityFor');
        $availabilityFor->setMultiOptions($availabilities);

        /** @var \Zend_Validate_InArray $validator */
        $validator = $availabilityFor->getValidator('InArray');
        $validator->setHaystack(array_keys($availabilities));
    }

    /**
     * @return void
     */
    public function setLandUses(array $landUses)
    {
        /** @var \Zend_Form_Element_Select $landUse */
        $landUse = $this->getElement('landUse');
        $landUse->setMultiOptions(['' => 'form.emptyOption'] + $landUses);

        /** @var \Zend_Validate_InArray $validator */
        $validator = $landUse->getValidator('InArray');
        $validator->setHaystack(array_keys($landUses));
    }
}
