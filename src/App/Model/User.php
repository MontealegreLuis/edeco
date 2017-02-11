<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace App\Model;

use Mandragora\Model\AbstractModel;
use Mandragora\Model\Property\Password;
use Mandragora\Model\Property\Date;
use App\Enum\UserState;
use Zend_Date;
use Zend_Mail;
use Mandragora\HtmlEmailSender;
use Mandragora\View\Helper\DateFormat;
use Text\Password as TextPassword;
use Zend_Layout;

/**
 * Model for users
 *
 * @property string $username
 * @property string $password
 * @property string $state
 * @property string $roleName
 * @property string $confirmationKey
 * @property string $creationDate
 */
class User extends AbstractModel
{
	/** @var array */
    protected $properties = [
        'username' => null, 'password' => null, 'state' => null,
        'roleName' => null, 'confirmationKey' => null, 'creationDate' => null,
    ];

    /** @var array */
    protected $identifier = ['username'];

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
            $password = new Password($password);
        }
        $this->properties['password'] = $password;
    }

    /**
     * @param string $password
     */
    public function setConfirmationKey($key)
    {
        if (!is_null($key)) {
            $key = new Password($key);
        }
        $this->properties['confirmationKey'] = $key;
    }

    /**
     * @param string $creationDate
     */
    public function setCreationDate($creationDate)
    {
        $this->properties['creationDate'] = new Date($creationDate);
    }

    /**
     * @param string $username
     * @return void
     */
    public function createClientAccount($username)
    {
    	$this->properties['username'] = (string) $username;
    	$this->properties['state'] = UserState::Unconfirmed;
        $this->properties['roleName'] = 'client';
        $this->properties['creationDate'] = (new Zend_Date())->toString('YYYY-MM-dd');
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
        $mailer = new HtmlEmailSender($mail);
        $mailer->setViewParam('confirmationKey', (string) $confirmationKey);
        $dateHelper = new DateFormat();
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
        $this->properties['state'] = UserState::Active;
        return $this->generatePassword();
    }

    /**
     * @return string
     *      An auto-generated strong password in raw form
     */
    protected function generatePassword()
    {
        $passwordGenerator = new TextPassword();
        $password = $passwordGenerator->create(10, 'unpronounceable');
        $this->setPassword($password);
        return $password;
    }

    /**
     * @return void
     */
    protected function generateConfirmationKey()
    {
        $salt = mt_srand((double) microtime() * 1000000);
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
