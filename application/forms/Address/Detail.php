<?php
/**
 * Address  form
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
 * @subpackage Form
 * @author     LNJ <lemuel.nonoal@mandragora-web-systems.com>
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Address form
 *
 * @category   Application
 * @package    Edeco
 * @subpackage Form
 * @author     LNJ <lemuel.nonoal@mandragora-web-systems.com>
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class App_Form_Address_Detail extends Mandragora_Form_Crud_Abstract
{
    /**
     * @param int $propertyId
     */
    public function setIdValue($propertyId)
    {
        $this->getElement('id')->setValue((int)$propertyId);
    }

    /**
     * @param array $options
     * @return void
     */
    public function setStates(array $states)
    {
        $state = $this->getElement('state');
        $state->getValidator('InArray')->setHaystack(array_keys($states));
        $options = array('' => 'form.emptyOption') + $states;
        $state->setMultioptions($options);
    }

    /**
     * @param array $options
     * @return void
     */
    public function setCities(array $cities)
    {
        $city = $this->getElement('cityId');
        $city->getValidator('InArray')->setHaystack(array_keys($cities));
        $options = array('' => 'form.emptyOption') + $cities;
        $city->setMultioptions($options);
    }

    /**
     * @return void
     */
    public function prepareForCreating()
    {
        $this->removeElement('version');
    }

    /**
     * @return void
     */
    public function prepareForEditing()
    {
    }

}