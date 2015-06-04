<?php
/**
 * Contact model
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
 * @subpackage Model
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Contact model
 *
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @version    SVN: $Id$
 * @copyright  Mandrágora Web-Based Systems 2010
 * @category   Application
 * @package    Edeco
 * @subpackage Model
 * @history    18 may, 2010
 *             LMV
 *             - Class Creation
 * @history    22 may, 2010
 *             LMV
 *             - Class Creation
 */
class App_Model_Contact extends Mandragora_Model_Abstract
{
    /**
     * @var array
     */
    protected $properties = array(
        'name' => null, 'emailAddress' => null, 'message' => null
    );

    /**
     * @var array
     */
    protected $identifier = array();

    /**
     * Sends an email to Edeco's recipient
     *
     * @param string $baseUrl
     * @param string $propertyName
     * @return void
     */
    public function sendEmailMessage($baseUrl, $propertyName)
    {
        $mail = new Zend_Mail('utf-8');
        $recipient = Zend_Mail::getDefaultReplyTo();
        $mail->addTo($recipient['email'], $recipient['name'])
             ->addTo('ventas@edeco.mx', 'Ventas')
             ->setFrom($this->emailAddress, $this->name)
             ->setSubject('Atención al cliente - Contacto');
        $mailer = new Mandragora_HtmlEmailSender($mail);
        foreach ($this->properties as $property => $value) {
            $mailer->setViewParam($property, $value);
        }
        $dateHelper = new Mandragora_View_Helper_DateFormat();
        $date = $dateHelper->dateFormat()->full();
        $mailer->setViewParam('date', $date);
        $mailer->setViewParam('baseUrl', $baseUrl);
        if (is_string($propertyName)) {
            $mailer->setViewParam('propertyName', $propertyName);
        }
        $mailer->sendHtmlTemplate('form-contact-message.phtml');
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->properties['name'];
    }

}