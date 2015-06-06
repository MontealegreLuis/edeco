<?php
/**
 * Sends e-mails using Zend_View templates
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
 * @category   Library
 * @package    Mandragora
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Sends e-mails using Zend_View templates
 *
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems
 * @version    SVN: $Id$
 * @category   Library
 * @package    Mandragora
 */
class Mandragora_HtmlEmailSender
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