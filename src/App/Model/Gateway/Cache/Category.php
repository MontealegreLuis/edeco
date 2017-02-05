<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Gateway\Cache;

use Mandragora\Gateway\Decorator\CacheAbstract;
use Mandragora\Model\AbstractModel;

/**
 * Cache decorator for Category's Gateway
 */
class Category extends CacheAbstract
{
    /**
     * @return array
     * @throws Mandragora_Gateway_NoResultsFoundException
     */
    public function findOneById($id)
    {
        $cacheId = 'category' . (int)$id;
        $category = $this->getCache()->load($cacheId);
        if (!$category) {
            $category = $this->gateway->findOneById((int)$id);
            $this->getCache()->save($category, $cacheId);
        }
        return $category;
    }

    /**
    * @return array
    * @throws Mandragora_Gateway_NoResultsFoundException
    */
    public function findOneByUrl($url)
    {
        $cacheId = 'category' . md5($url);
        $category = $this->getCache()->load($cacheId);
        if (!$category) {
            $category = $this->gateway->findOneByUrl((string)$url);
            $this->getCache()->save($category, $cacheId);
        }
        return $category;
    }

    /**
     * @param Mandragora_Model_Abstract $category
     * @return void
     */
    public function insert(AbstractModel $category)
    {
        $this->gateway->insert($category);
        $cacheId = 'category' . $category->id;
        $this->getCache()->save($category->toArray(true), $cacheId);
    }

    /**
     * @param Mandragora_Model_Abstract $category
     * @return void
     */
    public function update(AbstractModel $category)
    {
        $this->gateway->update($category);
        $cacheId = 'category' . $category->id;
        $this->getCache()->save($category->toArray(true), $cacheId);
    }

    /**
     * @param Mandragora_Model_Abstract $category
     * @return void
     */
    public function delete(AbstractModel $category)
    {
        $this->gateway->delete($category);
        $cacheId = 'category' . $category->id;
        $this->getCache()->remove($cacheId);
    }
}
