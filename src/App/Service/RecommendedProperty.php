<?php
/**
 * Service class for RecommendedProperty model
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
 * @subpackage Service
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2011
 * @version    SVN: $Id$
 */

/**
 * Service class for RecommendedProperty model
 *
 * @package    App
 * @subpackage Service
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2011
 * @version    SVN: $Id$
 */
class   App_Service_RecommendedProperty
extends Mandragora_Service_Crud_Doctrine_Abstract
{
    /**
     * @return void
     */
    protected function init()
    {
        $this->openConnection();
        $this->decorateGateway();
    }

    /**
     * @param int $pageNumber
     * @param int $id
     * @return App_Model_Collection_RecommendedProperty
     */
    public function retrieveRecommendedPropertyCollection($pageNumber, $id)
    {
        $this->init();
        $this->query = $this->getGateway()->getQueryFindAll($id);
        $items = (array)$this->getPaginator($pageNumber)->getCurrentItems();
        return new App_Model_Collection_RecommendedProperty($items);
    }

    /**
     * @return void
     */
    public function createRecommendedProperty()
    {
        $this->init();
        $properties = explode(',', $this->getForm()->getValue('properties'));
        $propertyId = $this->getForm()->getValue('id');
        $this->getGateway()->insertProperties($properties, $propertyId);
    }

    /**
     * @param string $action
     * @return Mandragora_Form_Abstract
     */
    public function getFormForCreating($action)
    {
        $this->getForm('Detail')->setAction($action);
        $this->getForm()->prepareForCreating();
        return $this->getForm();
    }

    /**
     * @param int $id
     * @param int $propertyId
     * @return App_Model_RecommendedProperty
     * @throws Mandragora_Gateway_NoResultsFoundException
     */
    public function retrieveRecommendedPropertyBy($id, $propertyId)
    {
        try {
            $this->init();
            $values = $this->getGateway()
                           ->findOneBy((int)$id, (int)$propertyId);
            return $this->getModel($values);
        } catch (Mandragora_Gateway_NoResultsFoundException $nrfe) {
            return false;
        }
    }

    /**
     * @param string $action
     * @return Mandragora_Form_Abstract
     */
    public function getFormForEditing($action)
    {
        $this->getForm('Detail')->setAction($action);
        $this->getForm()->prepareForEditing();
        return $this->getForm();
    }

    /**
     * @param int $id
     * @return void
     */
    public function deleteRecommendedProperty()
    {
        $this->init();
        $this->getGateway()->delete($this->getModel());
    }

}