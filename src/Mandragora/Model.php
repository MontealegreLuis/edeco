<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora;

/**
 * Factory for models
 */
class Model
{
    /**
     * @return void
     */
    private function __construct() {}

    /**
     * @param string $model
     * @param string $namespace = ''
     * @param array $values = null
     * @return Mandragora_Model_Abstract
     */
    public static function factory($model, array $values = null)
    {
        $modelName = sprintf('App\Model\%s', $model);
        return new $modelName($values);
    }
}
