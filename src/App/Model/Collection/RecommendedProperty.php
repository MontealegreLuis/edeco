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
 * Collection class for RecommendedProperty model
 */
class RecommendedProperty extends AbstractCollection
{
    /**
     * @param array $values
     * @return Edeco_Model_Property
     */
    protected function createModel(array $values)
    {
        return Model::factory('RecommendedProperty', $values);
    }
}
