<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Collection;

use App\Model\Picture as PictureModel;
use Mandragora\Collection\AbstractCollection;

/**
 * Collection class for Picture model
 */
class Picture extends AbstractCollection
{
    /**
     * @return PictureModel
     */
    protected function createModel(array $data)
    {
        return new PictureModel($data);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->count();
    }
}
