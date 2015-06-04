<?php
/**
 * Cache decorator for Category's Gateway
 *
 * PHP version 5
 *
 * LICENSE: Redistribution and use of this file in source and binary forms,
 * with or without modification, is not permitted under any circumstance
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @package    App
 * @subpackage Gateway_Cache
 * @author     MMS <meri.michimani@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2011
 * @version    SVN: $Id$
 */

/**
 * Cache decorator for Category's Gateway
 *
 * @package    App
 * @subpackage Gateway_Cache
 * @author     MMS <meri.michimani@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2011
 * @version    SVN: $Id$
 */
class   App_Model_Gateway_Cache_Category
extends Mandragora_Gateway_Decorator_CacheAbstract
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
    public function insert(Mandragora_Model_Abstract $category)
    {
        $this->gateway->insert($category);
        $cacheId = 'category' . $category->id;
        $this->getCache()->save($category->toArray(true), $cacheId);
    }

    /**
     * @param Mandragora_Model_Abstract $category
     * @return void
     */
    public function update(Mandragora_Model_Abstract $category)
    {
        $this->gateway->update($category);
        $cacheId = 'category' . $category->id;
        $this->getCache()->save($category->toArray(true), $cacheId);
    }

    /**
     * @param Mandragora_Model_Abstract $category
     * @return void
     */
    public function delete(Mandragora_Model_Abstract $category)
    {
        $this->gateway->delete($category);
        $cacheId = 'category' . $category->id;
        $this->getCache()->remove($cacheId);
    }

}
