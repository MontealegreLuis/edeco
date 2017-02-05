<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora;

use Zend_Mail;
use Zend_View;

/**
 * Sends e-mails using Zend_View templates
 */
class HtmlEmailSender
{
    /**
     * @var Zend_View
     */
    protected static $defaultView;

    /**
     * @var Zend_View
     */
    protected $view;

    /**
     * @var Zend_Mail
     */
    protected $mailer;

    /**
     * @param Zend_Mail $mailer
     */
    public function __construct(Zend_Mail $mailer)
    {
        $this->mailer = $mailer;
        $this->view = self::getDefaultView();
    }

    /**
     * @return Zend_View
     */
    protected static function getDefaultView()
    {
        if(self::$defaultView === null) {
            self::$defaultView = new Zend_View();
            self::$defaultView->setScriptPath(
                APPLICATION_PATH . '/views/scripts/common/mail'
            );
            self::$defaultView->setHelperPath(
                'Mandragora/View/Helper', 'Mandragora_View_Helper'
            );
        }
        return self::$defaultView;
    }

    /**
     * @param string $template
     * @return void
     */
    public function sendHtmlTemplate($template)
    {
        $html = $this->view->render((string)$template);
        $this->mailer->setBodyHtml($html, $this->mailer->getCharset());
        $this->mailer->send();
    }

    /**
     * @param string $property
     * @param string $value
     * @return Mandragora_HtmlEmailSender
     */
    public function setViewParam($property, $value)
    {
        $this->view->__set($property, $value);
        return $this;
    }
}
