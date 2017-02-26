<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Collection;

use App\Model\State as StateModel;
use Mandragora\Collection\AbstractCollection;

/**
 * Collection class for State model
 */
class State extends AbstractCollection
{
    /**
     * @return StateModel
     */
    protected function createModel(array $data)
    {
        return new StateModel($data);
    }
}
