<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model;

use Mandragora\Model\AbstractModel;
use Zend_Mail;
use Mandragora\HtmlEmailSender;
use Mandragora\View\Helper\DateFormat;

/**
 * Contact model
 */
class Contact extends AbstractModel
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
        $mailer = new HtmlEmailSender($mail);
        foreach ($this->properties as $property => $value) {
            $mailer->setViewParam($property, $value);
        }
        $dateHelper = new DateFormat();
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
