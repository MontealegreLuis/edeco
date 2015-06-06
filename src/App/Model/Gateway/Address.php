<?php
/**
 * Gateway for Address model objects
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
 * @author     MMS <meri.michimani@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2011
 * @version    SVN: $Id$
 */

/**
 * Gateway for Address model objects
 *
 * @package    App
 * @subpackage Gateway
 * @author     MMS <meri.michimani@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2011
 * @version    SVN: $Id$
 */
class   App_Model_Gateway_Address
extends Mandragora_Gateway_Doctrine_Abstract
{
    /**
     * @return array
     * @throws Mandragora_Doctrine_Gateway_NoResultsFoundException
     */
    public function findOneById($id)
    {
        $query = $this->dao->getTable()->createQuery();
        $query->from($this->alias())
              ->innerJoin('a.City c')
              ->innerJoin('c.State s')
              ->where('a.id = :id');
        $query->getSqlQuery();
        $address = $query->fetchOne(
            array(':id' => (int)$id), Doctrine_Core::HYDRATE_ARRAY
        );
        if (!$address) {
            throw new Mandragora_Gateway_NoResultsFoundException(
                "Address with id '$id' cannot be found"
            );
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
        $query = $this->dao->getTable()->createQuery();
        $query->update($this->alias())
              ->set('latitude', ':latitude')
              ->set('longitude', ':longitude')
              ->where('id = :id');
        $query->execute(
            array(
                ':latitude' => $geoPostition['latitude'],
                ':longitude' => $geoPostition['longitude'],
                ':id' => $id
            )
        );
    }

}
