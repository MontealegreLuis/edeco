<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Collection;

use App\Model\RecommendedProperty as RecommendedPropertyModel;
use Mandragora\Collection\AbstractCollection;

/**
 * Collection class for RecommendedProperty model
 */
class RecommendedProperty extends AbstractCollection
{
    /**
     * @param array $values
     * @return RecommendedPropertyModel
     */
    protected function createModel(array $values)
    {
        return new RecommendedPropertyModel($values);
    }
}
