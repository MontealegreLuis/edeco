<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Form\Property;

use Mandragora\Form\Crud\AbstractCrud;
use Mandragora\Validate\Db\Doctrine\NoRecordExists;

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
             ->removeValidator(NoRecordExists::class);
    }

    /**
     * @return void
     */
    public function setCategories(array $categories)
    {
        $haystack = [];
        $categoryCollection = [];
        foreach ($categories as $category) {
            $haystack[] = $category['id'];
            $categoryCollection[$category['id']] = $category['name'];
        }
        $category = $this->getElement('categoryId');
        $category->getValidator('InArray')->setHaystack($haystack);
        $category->setMultioptions(['' => 'form.emptyOption'] + $categoryCollection);
    }

    /**
     * @return void
     */
    public function setAvailabilities(array $availabilities)
    {
        $availabilityFor = $this->getElement('availabilityFor');
        $availabilityFor->getValidator('InArray')->setHaystack(array_keys($availabilities));
        $availabilityFor->setMultioptions($availabilities);
    }

    /**
     * @return void
     */
    public function setLandUses(array $landUses)
    {
        $landUse = $this->getElement('landUse');
        $landUse->getValidator('InArray')->setHaystack(array_keys($landUses));
        $landUse->setMultioptions(['' => 'form.emptyOption'] + $landUses);
    }
}
