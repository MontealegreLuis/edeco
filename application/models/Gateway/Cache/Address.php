<?php
/**
 * Cache decorator for Address Gateway
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
 * Cache decorator for Address Gateway
 *
 * @package    App
 * @subpackage Gateway_Cache
 * @author     MMS <meri.michimani@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2011
 * @version    SVN: $Id$
 */
class   App_Model_Gateway_Cache_Address
extends Mandragora_Gateway_Decorator_CacheAbstract
{
    /**
     * @return array
     * @throws Mandragora_Gateway_NoResultsFoundException
     */
    public function findOneById($id)
    {
        $cacheId = 'address' . (int)$id;
        $address = $this->getCache()->load($cacheId);
        if (!$address) {
            $address = $this->gateway->findOneById((int)$id);
            $this->getCache()->save($address, $cacheId);
        }
        return $address;
    }

    /**
    * @param int $id
    * @param array $geoPostition
    * @return void
    */
    public function saveGeoPosition($id, array $geoPostition)
    {
        $this->gateway->saveGeoPosition($id, $geoPostition);
        $this->getCache()->remove('property' . $id);
        $this->getCache()->remove('address' . $id);
        $this->getCache()->clean(
            Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG,
            array(Edeco_Paginator_Property::PROPERTIES_TAG,)
        );
    }

    /**
     * @param Mandragora_Model_Abstract $address
     * @return void
     */
    public function insert(Mandragora_Model_Abstract $address)
    {
        $this->gateway->insert($address);
        //Do not save this object in cache it'll be saved with all the
        //needed relationships in findOneById
        $cacheId = 'property' . $address->id;
        $this->getCache()->remove($cacheId);
        $this->getCache()->clean(
            Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG,
            array(Edeco_Paginator_Property::PROPERTIES_TAG,)
        );
    }

    /**
     * @param Mandragora_Model_Abstract $address
     * @return void
     */
    public function update(Mandragora_Model_Abstract $address)
    {
        $this->gateway->clearRelated();
        $this->gateway->update($address);
        //Remove this object from cache it'll be saved with all the
        //needed relationships in findOneById
        $cacheId = 'address' . $address->id;
        $this->getCache()->remove($cacheId);
        $cacheId = 'property' . $address->id;
        $this->getCache()->remove($cacheId);
        $this->getCache()->clean(
            Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG,
            array(Edeco_Paginator_Property::PROPERTIES_TAG,)
        );
    }

    /**
     * @param Mandragora_Model_Abstract $address
     * @return void
     */
    public function delete(Mandragora_Model_Abstract $address)
    {
        $this->gateway->delete($address);
        $cacheId = 'address' . $address->id;
        $this->getCache()->remove($cacheId);
        $cacheId = 'property' . $address->id;
        $this->getCache()->remove($cacheId);
        $this->getCache()->clean(
            Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG,
            array(Edeco_Paginator_Property::PROPERTIES_TAG,)
        );
    }

}
