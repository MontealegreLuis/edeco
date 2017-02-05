<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Model\Property;

use Mandragora\String\StringInterface;

/**
 * Interface for custom properties in a model
 */
interface PropertyInterface extends StringInterface
{
    /**
     * Return this property formated as is expected in the view layer
     *
     * @return string
     */
    public function render();
}
