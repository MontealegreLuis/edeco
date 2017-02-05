<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Form\Category;

use Mandragora\Form\Crud\AbstractCrud;

/**
 * Category form
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
}
