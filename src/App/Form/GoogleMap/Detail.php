<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Form\GoogleMap;

use Mandragora\Form\Crud\AbstractCrud;

/**
 * Google map form
 */
class Detail extends AbstractCrud
{
    public function setAddressId(int $addressId)
    {
        $this->getElement('addressId')->setValue($addressId);
    }

    /**
     * @return void
     */
    public function prepareForCreating()
    {
        $this->removeElement('version');
    }

    /**
     * @return void
     */
    public function prepareForEditing() {}
}
