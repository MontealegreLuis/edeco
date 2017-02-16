<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Form\RecommendedProperty;

use Mandragora\Form\Crud\CrudForm;

class Detail extends CrudForm
{
    public function setPropertyId(int $propertyId)
    {
        $this->getElement('id')->setValue($propertyId);
    }
}
