<?php
/**
 * Class to mock the process of sending email messages
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
 * @subpackage Mail_Transport
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Class to mock the process of sending email messages
 *
 * @category   Application
 * @package    Edeco
 * @subpackage Mail_Transport
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class Mandragora_Mail_Transport_Debug extends Zend_Mail_Transport_Abstract
{
    /**
     * Write the content of an email message to an html file instead of sending
     * it
     *
     * @return void
     */
    public function _sendMail()
    {
        $filter = new Mandragora_Filter_FriendlyUrl();
        $subject = $filter->filter($this->_mail->getSubject());
        $directory = APPLICATION_PATH . '/data/debug-mail/';
        $fullPath = $directory . 'mail-' . $subject. '.html';
        $file = Mandragora_File::create($fullPath);
        $mimeDecode = new Zend_Mime_Decode();
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