<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright  Mandrágora Web-Based Systems 2010-2015 (http://www.mandragora-web-systems.com)
 */

namespace Mandragora\Mail\Transport;

use Zend_Mail_Transport_Abstract;
use Mandragora\Filter\FriendlyUrl;
use Mandragora\File;




/**
 * Class to mock the process of sending email messages
 *
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 */
class Debug extends Zend_Mail_Transport_Abstract
{
    /**
     * Write the content of an email message to an html file instead of sending
     * it
     *
     * @return void
     */
    public function _sendMail()
    {
        $filter = new FriendlyUrl();
        $subject = $filter->filter($this->_mail->getSubject());
        $directory = APPLICATION_PATH . '/../var/debug-mail/';
        $fullPath = $directory . 'mail-' . $subject. '.html';
        $file = File::create($fullPath);
        if ($this->_mail->getBodyHtml()) {
            $content = quoted_printable_decode(
                $this->_mail->getBodyHtml()->getContent()
            );
        } else {
            $content = quoted_printable_decode(
                $this->_mail->getBodyText()->getContent()
            );
        }
        $file->write($content);
    }
}
