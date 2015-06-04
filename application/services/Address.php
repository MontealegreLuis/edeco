<?php
/**
 * Service class for Address model
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
 * @subpackage Service
 * @author     LNJ <lemuel.nonoal@mandragora-web-systems.com>
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Service class for Address model
 *
 * @category   Application
 * @package    Edeco
 * @subpackage Service
 * @author     LNJ <lemuel.nonoal@mandragora-web-systems.com>
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class   App_Service_Address
extends Mandragora_Service_Crud_Doctrine_Abstract
{
    /**
     * @var App_Form_Address_GoogleMap
     */
    protected $googleForm;

	/**
     * @return void
     */
    protected function init()
    {
        $this->openConnection();
        $this->decorateGateway();
    }

    /**
    * @return void
    */
    public function createAddress()
    {
        $this->init();
        $this->getModel($this->getForm()->getValues());
        $this->getGateway()->insert($this->getModel());
    }

    /**
     * @param string $action
     * @return Edeco_Form_Address_Detail
     */
    public function getFormForCreating($action)
    {
        $this->getForm()->prepareForCreating();
        $this->setStates();
        $this->getForm()->setAction($action);
        return $this->getForm();
    }

    /**
     * @param string $action
     * @return Edeco_Form_Address_Detail
     */
    public function getFormForEditing($action)
    {
        $this->getForm()->prepareForEditing();
        $this->setStates();
        $this->getForm()->setAction($action);
        return $this->getForm();
    }

    /**
     * @param string $formName
     * @return void
     */
    protected function setStates()
    {
        $stateService = Mandragora_Service::factory('State');
        $stateService->setCacheManager($this->cacheManager);
        $stateService->setDoctrineManager($this->doctrineManager);
        $states = $stateService->retrieveAllStates();
        $this->getForm()->setStates($states);
        //Add default option to cityId select element
        $options = array('' => 'form.emptyOption');
        $this->getForm()->getElement('cityId')->setMultioptions($options);
    }

    /**
     * @param int $stateId
     * @return boolean
     */
    public function setCities($stateId)
    {
        if (is_numeric($stateId)) {
            $cityService = Mandragora_Service::factory('City');
            $cityService->setCacheManager($this->cacheManager);
            $cityService->setDoctrineManager($this->doctrineManager);
            $cities = $cityService->retrieveAllByStateId((int)$stateId);
            $options = array();
            foreach ($cities as $city) {
                $options[$city['id']] = $city['name'];
            }
            $this->getForm()->setCities($options);
        } else {
            $this->getForm()->getElement('cityId')->removeValidator('InArray');
        }
    }

    /**
     * @param int $propertyId
     * @return void
     */
    public function retrieveAddressById($id)
    {
        try {
            $this->init();
            $values = $this->getGateway()->findOneById((int)$id);
            return $this->getModel($values);
        } catch (Mandragora_Gateway_NoResultsFoundException $nrfe) {
            return false;
        }
    }

    /**
     * @return void
     */
    public function updateAddress()
    {
        $this->init();
        $this->getModel()->fromArray($this->getForm()->getValues());
        $this->getGateway()->update($this->getModel());
    }

    /**
    * @param int $id
    * @return void
    */
    public function deleteAddress($id)
    {
        $this->init();
        $this->getGateway()->delete($this->getModel());
    }

}