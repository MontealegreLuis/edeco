<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Form\Property;

use Mandragora\Form\Crud\AbstractCrud;

/**
 * Property's form
 */
class Detail extends AbstractCrud
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
        $this->getElement('name')
             ->removeValidator('Db_Doctrine_NoRecordExists');
    }

    /**
     * @param array $categories
     * @return void
     */
    public function setCategories(array $categories)
    {
        $haystack = array();
        $categoryCollection = array();
        foreach ($categories as $category) {
            $haystack[] = $category['id'];
            $categoryCollection[$category['id']] = $category['name'];
        }
        $category = $this->getElement('categoryId');
        $category->getValidator('InArray')->setHaystack($haystack);
        $options = array('' => 'form.emptyOption') + $categoryCollection;
        $category->setMultioptions($options);
    }

    /**
     * @param array $availabilities
     * @return void
     */
    public function setAvailabilities(array $availabilities)
    {
        $haystack = array_keys($availabilities);
        $availabilityFor = $this->getElement('availabilityFor');
        $availabilityFor->getValidator('InArray')->setHaystack($haystack);
        $availabilityFor->setMultioptions($availabilities);
    }

    /**
     * @param array $landUses
     * @return void
     */
    public function setLandUses(array $landUses)
    {
        $haystack = array_keys($landUses);
        $landUse = $this->getElement('landUse');
        $landUse->getValidator('InArray')->setHaystack($haystack);
        $options = array('' => 'form.emptyOption') + $landUses;
        $landUse->setMultioptions($options);
    }
}
