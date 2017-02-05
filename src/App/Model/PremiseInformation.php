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
 * Premise Information model
 */
class PremiseInformation extends AbstractModel
{
    /**
     * @var array
     */
    protected $properties = array(
        'name' => null, 'telephone' => null, 'emailAddress' => null,
        'zone' => null, 'minPrice' => null, 'maxPrice' => null,
        'surface' => null, 'characteristics' => null
    );

    /**
     * @var array
     */
    protected $identifier = array();

    /**
     * Sends an email to Edeco's recipient
     *
     * @param string $baseUrl
     * @return void
     */
    public function sendEmailMessage($baseUrl)
    {
        $mail = new Zend_Mail('utf-8');
        $recipient = Zend_Mail::getDefaultReplyTo();
        $mail->addTo($recipient['email'], $recipient['name'])
             ->addTo('ventas@edeco.mx', 'Ventas')
             ->setFrom($this->emailAddress, $this->name)
             ->setSubject('Atención al cliente - Locales');
        $mailer = new HtmlEmailSender($mail);
        foreach ($this->properties as $property => $value) {
            $mailer->setViewParam($property, $value);
        }
        $dateHelper = new DateFormat();
        $date = $dateHelper->dateFormat()->full();
        $mailer->setViewParam('date', $date);
        $mailer->setViewParam('baseUrl', $baseUrl);
        $mailer->sendHtmlTemplate('form-premise-information-message.phtml');
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->properties['name'];
    }
}
