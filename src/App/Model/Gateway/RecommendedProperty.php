<?php
/**
 * Gateway for RecommendedProperty model objects
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
 * @subpackage Gateway
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2011
 * @version    SVN: $Id$
 */

/**
 * Gateway for RecommendedProperty model objects
 *
 * @package    App
 * @subpackage Gateway
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2011
 * @version    SVN: $Id$
 */
class   App_Model_Gateway_RecommendedProperty
extends Mandragora_Gateway_Doctrine_Abstract
{
    /**
     * @return Doctrine_Query
     */
    public function getQueryFindAll($id)
    {
        $query = $this->dao->getTable()->createQuery();
        $params = array(':propertyId' => (int)$id);
        $query->from('App_Model_Dao_RecommendedProperty r')
              ->where('r.propertyId = :propertyId', $params)
              ->innerJoin('r.RecommendedProperty p')
              ->innerJoin('p.Address a')
              ->innerJoin('a.City c')
              ->innerJoin('c.State s');
        return $query;
    }

    /**
     * @param int $id
     * @param int $propertyId
     * @return array
     * @throws Mandragora_Doctrine_Gateway_NoResultsFoundException
     */
    public function findOneBy($id, $propertyId)
    {
        $query = $this->dao->getTable()->createQuery();
        $query->from('App_Model_Dao_RecommendedProperty r')
              ->where('r.propertyId = :id')
              ->andWhere('r.recommendedPropertyId = :propertyId');
        $params = array(':id' => (int)$id, ':propertyId' => (int)$propertyId);
        $recommendedProperty = $query->fetchOne(
            $params, Doctrine_Core::HYDRATE_ARRAY
        );
        if (!$recommendedProperty) {
            throw new Mandragora_Gateway_NoResultsFoundException(
                "RecommendedProperty with id '$id' cannot be found"
            );
        }
        return $recommendedProperty;
    }

    /**
     * @param array $properties
     * @param int $propertyId
     */
    public function insertProperties(array $properties, $propertyId)
    {
        $collection = new Doctrine_Collection('App_Model_Dao_RecommendedProperty');
        foreach ($properties as $recommendedPropertyId) {
            $property = new App_Model_Dao_RecommendedProperty();
            $property->propertyId = $propertyId;
            $property->recommendedPropertyId = $recommendedPropertyId;
            $collection->add($property);
        }
        $collection->save();
    }

}