<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Form\RecommendedProperty;

use Mandragora\Form\Crud\AbstractCrud;

class Detail extends AbstractCrud
{
    /**
     * @return void
     */
    public function prepareForCreating() {}

    /**
     * @return void
     */
    public function prepareForEditing() {}

    /**
     * @param int $propertyId
     */
    public function setPropertyId($propertyId)
    {
        $this->getElement('id')->setValue((int)$propertyId);
    }
}
