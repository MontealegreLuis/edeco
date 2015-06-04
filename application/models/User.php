<?php
/**
 * Model for users
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
 * Model for users
 *
 * @property string $username
 * @property string $password
 * @property string $state
 * @property string $roleName
 * @property string $confirmationKey
 * @property string $creationDate
 *
 * @package    Edeco
 * @subpackage Model
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class   App_Model_User
extends Mandragora_Model_Abstract
{
	/**
     * @var array
     */
    protected $properties = array(
        'username' => null, 'password' => null, 'state' => null,
        'roleName' => null, 'confirmationKey' => null, 'creationDate' => null,
    );

    /**
     * @var array
     */
    protected $identifier = array('username');

    /**
     * @param array $values = null
     */
    public function __construct(array $values = null)
    {
        parent::__construct($values);
        unset($this->properties['id']);
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        if (!is_null($password)) {
            $password = new Mandragora_Model_Property_Password($password);
        }
        $this->properties['password'] = $password;
    }

    /**
     * @param string $password
     */
    public function setConfirmationKey($key)
    {
        if (!is_null($key)) {
            $key = new Mandragora_Model_Property_Password($key);
        }
        $this->properties['confirmationKey'] = $key;
    }

    /**
     * @param string $creationDate
     * @return void
     */
    public function setCreationDate($creationDate)
    {
        $creationDate = new Mandragora_Model_Property_Date($creationDate);
        $this->properties['creationDate'] = $creationDate;
    }

    /**
     * @param string $username
     * @return void
     */
    public function createClientAccount($username)
    {
    	$this->properties['username'] = (string)$username;
    	$this->properties['state'] = App_Enum_UserState::Unconfirmed;
        $this->properties['roleName'] = 'client';
        $creationDate = new Zend_Date();
        $this->properties['creationDate'] = $creationDate->toString('YYYY-MM-dd');
        $this->generateConfirmationKey();
    }

    /**
     * Sends an email to the new user's recipient
     *
     * @param string $userEmail
     * @param string $confirmationKey
     * @param string $baseUrl
     * @return void
     */
    public function sendEmailConfirmationMessage(
        $userEmail, $confirmationKey, $baseUrl
    )
    {
        $mail = new Zend_Mail('utf-8');
        $recipient = Zend_Mail::getDefaultFrom();
        $mail->addTo((string)$userEmail, (string)$userEmail)
             ->setFrom($recipient['email'], $recipient['name'])
             ->setSubject('Proyectos de Inversion EDECO');
        $mailer = new Mandragora_HtmlEmailSender($mail);
        $mailer->setViewParam('confirmationKey', (string)$confirmationKey);
        $dateHelper = new Mandragora_View_Helper_DateFormat();
        $date = $dateHelper->dateFormat()->full();
        $mailer->setViewParam('date', $date);
        $mailer->setViewParam('baseUrl', $baseUrl);
        $mailer->sendHtmlTemplate('user-confirmation-message.phtml');
    }

    /**
     * @return string
     */
    public function confirmClientAccountCreation()
    {
        $this->properties['state'] = App_Enum_UserState::Active;
        return $this->generatePassword();
    }

    /**
     * @return string
     *      An auto-generated strong password in raw form
     */
    protected function generatePassword()
    {
        $passwordGenerator = new Text_Password();
        $password = $passwordGenerator->create(10, 'unpronounceable');
        $this->setPassword($password);
        return $password;
    }

    /**
     * @return void
     */
    protected function generateConfirmationKey()
    {
        $salt = srand((double)microtime() * 1000000);
        $siteSalt = 'm3s1_MvsgH-%';
        $confirmationKey = urlencode(
            sha1($salt . $this->properties['username'] . $siteSalt)
        );
        $this->setConfirmationKey($confirmationKey);
    }

    /**
     * @return void
     */
    public function prepareShowing()
    {
        unset($this->properties['roleName']);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $view = Zend_Layout::getMvcInstance()->getView();
        $user = sprintf(
            '%s (%s)',
            $this->properties['username'],
            $view->translate($this->properties['state'])
        );
        return $user;
    }

}