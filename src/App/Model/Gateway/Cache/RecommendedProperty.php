<?php
/**
 * Cache decorator for RecommendedProperty's Gateway
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
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2011
 * @version    SVN: $Id$
 */

/**
 * Cache decorator for RecommendedProperty's Gateway
 *
 * @package    App
 * @subpackage Gateway_Cache
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2011
 * @version    SVN: $Id$
 */
class   App_Model_Gateway_Cache_RecommendedProperty
extends Mandragora_Gateway_Decorator_CacheAbstract
{
    /**
     * @param int $id
     * @param int $propertyId
     * @return array
     * @throws Mandragora_Gateway_NoResultsFoundException
     */
    public function findOneBy($id, $propertyId)
    {
        $cacheId = 'recommendedProperty' . (int)$id . '_' . (int)$propertyId;
        $recommendedProperty = $this->getCache()->load($cacheId);
        if (!$recommendedProperty) {
            $recommendedProperty = $this->gateway
                                        ->findOneBy((int)$id, (int)$propertyId);
            $this->getCache()->save($recommendedProperty, $cacheId);
        }
        return $recommendedProperty;
    }

    /**
     * @param Mandragora_Model_Abstract $recommendedProperty
     * @return void
     */
    public function delete(Mandragora_Model_Abstract $recommendedProperty)
    {
        $cacheId = 'recommendedProperty'
            . (int)$recommendedProperty->propertyId . '_'
            . (int)$recommendedProperty->recommendedPropertyId;
        $this->gateway->delete($recommendedProperty);
        $this->getCache()->remove($cacheId);
        $this->getCache()->clean(
            Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG,
            array(
                'property' . $picture->propertyId,
                // Also clean data in default module
                Edeco_Paginator_Property::PROPERTIES_TAG,
            )
        );
    }

    /**
    * @param array $properties
    * @param int $propertyId
    */
    public function insertProperties(array $properties, $propertyId)
    {
        $this->gateway->insertProperties($properties, $propertyId);
        $this->getCache()->clean(
            Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG,
            array(
                'property' . $propertyId,
                // Also clean data in default module
                Edeco_Paginator_Property::PROPERTIES_TAG,
            )
        );
    }

}