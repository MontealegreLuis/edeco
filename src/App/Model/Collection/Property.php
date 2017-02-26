<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Collection;

use App\Model\Property as PropertyModel;
use Mandragora\Collection\AbstractCollection;

/**
 * Collection class for Property model
 */
class Property extends AbstractCollection
{
    /**
     * @return PropertyModel
     */
    protected function createModel(array $data)
    {
        $property = new PropertyModel($data);
        if (isset($data['address']) && $data['address'] != null) {
            $property->address = $data['address'];
        }
        if (isset($data['Picture'])) {
            $property->Picture = new Picture($data['Picture']);
        }
        return $property;
    }
}
