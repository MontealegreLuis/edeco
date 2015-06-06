<?php
/**
 * Service class for Premise Information model
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
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Service class for Premise Information model
 *
 * @category   Application
 * @package    Edeco
 * @subpackage Service
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class App_Service_PremiseInformation extends Mandragora_Service_Crud_Doctrine_Abstract
{
    /**
     * @param string $action
     * @return Edeco_Form_PremiseInformation
     */
    public function getPremiseForm($action)
    {
        $this->getForm('Detail')->setAction($action);
        return $this->getForm();
    }

    /**
     * @param string $baseUrl
     * @return void
     */
    public function sendEmailMessage($baseUrl)
    {
        $this->getModel()->fromArray($this->getForm()->getValues());
        $this->getModel()->sendEmailMessage($baseUrl);
    }

    protected function createForm($formName) {}

    public function getFormForCreating($action) {}

    public function getFormForEditing($action) {}

}