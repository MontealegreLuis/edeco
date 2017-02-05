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
 * Collection class for Address model
 */
class Address extends AbstractCollection
{
    /**
     * @return Edeco_Model_Address
     */
    protected function createModel(array $data)
    {
        return Model::factory('Address', $data);
    }
}
