<?php
/**
 * PHP version 5
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
    /**
     * @param int $addressId
     */
    public function setAddressId($addressId)
    {
        $this->getElement('addressId')->setValue((int)$addressId);
    }

    /**
     * @return void
     */
    public function prepareForCreating()
    {
        $this->removeElement('version');
    }

    public function prepareForEditing() {}
}
