<?php
/**
 * Gateway for picture model objects
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
 * @category   Application
 * @package    Edeco
 * @subpackage Gateway
 * @author     LNJ <lemuel.nonoal@mandragora-web-systems.com>
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Gateway for picture model objects
 *
 * @author     LNJ <lemuel.nonoal@mandragora-web-systems.com>
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @version    SVN: $Id$
 * @copyright  Mandrágora Web-Based Systems 2010
 * @category   Application
 * @package    Edeco
 * @subpackage Gateway
 */
class App_Model_Gateway_Picture extends Mandragora_Gateway_Doctrine_Abstract
{
    /**
     * @param int $id
     * @param int $propertyId
     * @return array
     */
    public function findOneByIdAndPropertyId($id, $propertyId)
    {
        $query = $this->dao->getTable()->createQuery();
        $query->from($this->alias())
              ->where('p.propertyId = :propertyId')
              ->andWhere('p.id = :id');
        $picture = $query->fetchOne(
            array(':id' => (int)$id, ':propertyId' => (int)$propertyId),
            Doctrine_Core::HYDRATE_ARRAY
        );
        if (!$picture) {
            throw new Mandragora_Gateway_NoResultsFoundException(
                "The picture $id of property $propertyId cannot be found"
            );
        }
        return $picture;
    }

    /**
     * @param string $pictureName
     * @return array
     */
    public function findOneByName($pictureDescription)
    {
        $query = $this->dao->getTable()->createQuery();
        $query->from($this->alias())
              ->where('p.shortDescription = :shortDescription');
        $picture = $query->fetchOne(
            array(':shortDescription' => (string)$pictureDescription,),
            Doctrine_Core::HYDRATE_ARRAY
        );
        if (!$picture) {
            throw new Mandragora_Gateway_Doctrine_NoResultsFoundException(
                "Picture $pictureDescription not found"
            );
        }
        return $picture;
    }

    /**
     * @param int $propertyId
     * @return Doctrine_Query
     */
    public function getQueryFindAllByPropertyId($propertyId)
    {
        $query = $this->dao->getTable()->createQuery();
        $params = array(':propertyId' => $propertyId);
        $query->from($this->alias())
              ->where('p.propertyId = :propertyId', $params);
        return $query;
    }

}