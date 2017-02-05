<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Collection;

use Mandragora\Collection\AbstractCollection;
use Mandragora\Model;
use App\Model\Collection\Picture;

/**
 * Collection class for Property model
 */
class Property extends AbstractCollection
{
    /**
     * @return Edeco_Model_Property
     */
    protected function createModel(array $data)
    {
        $property = Model::factory('Property', $data);
        if (isset($data['address']) && $data['address'] != null) {
            $property->address = $data['address'];
        }
        if (isset($data['Picture'])) {
            $property->Picture = new Picture(
                $data['Picture']
            );
        }
        return $property;
    }
}
