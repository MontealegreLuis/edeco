<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Collection;

use Mandragora\Collection\AbstractCollection;
use Mandragora\Model;

/**
 * Collection class for Picture model
 */
class Picture extends AbstractCollection
{
    /**
     * @return Edeco_Model_Picture
     */
    protected function createModel(array $data)
    {
        return Model::factory('Picture', $data);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->count();
    }
}
