<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Gateway;

use Mandragora\Model\AbstractModel;

/**
 * Interface for gateway objects
 */
interface GatewayInterface
{
    /**
     * @param Mandragora_Model_Abstract $model
     */
    public function insert(AbstractModel $model);

    /**
     * @param Mandragora_Model_Abstract $model
     */
    public function update(AbstractModel $model);

    /**
     * @param Mandragora_Model_Abstract $model
     */
    public function delete(AbstractModel $model);
}
