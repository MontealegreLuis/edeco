<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Collection;

use App\Model\Category as CategoryModel;
use Mandragora\Collection\AbstractCollection;

/**
 * Collection class for Category model
 */
class Category extends AbstractCollection
{
    /**
     * @param array $values
     * @return CategoryModel
     */
    protected function createModel(array $values)
    {
        return new CategoryModel($values);
    }
}
