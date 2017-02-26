<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Collection;

use App\Model\Address as AddressModel;
use Mandragora\Collection\AbstractCollection;

/**
 * Collection class for Address model
 */
class Address extends AbstractCollection
{
    /**
     * @return AddressModel
     */
    protected function createModel(array $data)
    {
        return new AddressModel($data);
    }
}
