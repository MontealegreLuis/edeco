<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Collection;

use App\Model\City as CityModel;
use Mandragora\Collection\AbstractCollection;

/**
 * Collection class for City model
 */
class City extends AbstractCollection
{
    /**
     * @return CityModel
     */
    protected function createModel(array $values)
    {
        return new CityModel($values);
    }
}
