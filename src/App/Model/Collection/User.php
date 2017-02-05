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
 * Collection class for User's model
 */
class User extends AbstractCollection
{
    /**
     * @return Edeco_Model_User
     */
    protected function createModel(array $data)
    {
        return Model::factory('User', $data);
    }
}
