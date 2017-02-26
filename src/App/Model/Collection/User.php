<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Collection;

use App\Model\User as UserModel;
use Mandragora\Collection\AbstractCollection;

/**
 * Collection class for User's model
 */
class User extends AbstractCollection
{
    /**
     * @return UserModel
     */
    protected function createModel(array $data)
    {
        return new UserModel($data);
    }
}
