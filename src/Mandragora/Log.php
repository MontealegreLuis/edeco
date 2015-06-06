<?php
/**
 * Logger for applications
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
 * @copyright  Mandrágora Web-Based Systems
 * @version    SVN $Id$
 */

/**
 * Logger for applications
 *
 * @category   Library
 * @package    Mandragora
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems
 * @version    SVN $Id$
 */
class Mandragora_Log
{
    /**
     * @var Zend_Log
     */
    protected $fileLogger;

    /**
     * @var Zend_Log
     */
    protected $mailLogger;

    /**
     *
     * @var Mandragora_Log
     */
    static $logger = null;

    public static function getInstance()
    {
        if (self::$logger === null) {
            self::$logger = new self();
        }
        return self::$logger;
    }

    /**
     *
     * @return Zend_Log
     */
    protected function getFileLogger()
    {
        return $this->fileLogger;
    }

    /**
     *
     * @return Zend_Log
     */
    protected function getMailLogger()
    {
        return $this->mailLogger;
    }

    /**
     * @return void
     */
    protected function __construct()
    {
        $fc = Zend_Controller_Front::getInstance();
        $this->fileLogger = $fc->getParam('bootstrap')->getResource('log');
        $this->createMailLogger();
    }

    /**
     * @return void
     */
    protected function createMailLogger()
    {
        if ('production' === APPLICATION_ENV
            || 'staging' === APPLICATION_ENV) {
            $fc = Zend_Controller_Front::getInstance();
            $mailTransport = $fc->getParam('bootstrap')->getResource('mail');
        } else {
            $mailTransport = new Mandragora_Mail_Transport_Debug();
        }
        Zend_Mail::setDefaultTransport($mailTransport);
        $mail = new Zend_Mail('utf-8');
        $mail->setFrom('admin@edeco.mx')
             ->setReplyTo('admin@edeco.mx')
             ->addTo('bug.tracker@mandragora-web-systems.com');
        $layout = new Zend_Layout();
        $layout->setLayoutPath(APPLICATION_PATH . '/views/scripts/common/mail');
        $layout->setLayout('log');
        $layoutFormatter = new Zend_Log_Formatter_Simple(
            '<pre style="overflow: auto; font-size: 1em">'
            . Zend_Log_Formatter_Simple::DEFAULT_FORMAT
            . '</pre>'
        );
        $writer = new Zend_Log_Writer_Mail($mail, $layout);
        $writer->setLayoutFormatter($layoutFormatter);
        $writer->setSubjectPrependText('www.edeco.mx');
        $this->mailLogger = new Zend_Log();
        $this->mailLogger->addWriter($writer);
    }

    /**
     * Log an error message
     *
     * @param Exception $exception
     * @param Zend_Controller_Request_Abstract $request
     */
    public static function error(
        Exception $exception, Zend_Controller_Request_Abstract  $request
    )
    {
        $message = self::formatException($exception, $request);
        self::getInstance()->getFileLogger()->log($message, Zend_Log::ERR);
        self::getInstance()->getMailLogger()->log($message, Zend_Log::ERR);
    }

    /**
     * @param string $message
     */
    public static function info($message)
    {
        self::getInstance()->getFileLogger()->log($message, Zend_Log::INFO);
    }

    /**
     * @param Exception $exception
     * @param Zend_Controller_Request_Abstract $request
     */
    protected static function formatException(
        Exception $exception, Zend_Controller_Request_Abstract $request)
    {
        $exceptionInfo = sprintf(
            'Exception "%s" with message %s in %s line %d',
            get_class($exception), $exception->getMessage(),
            $exception->getFile(), $exception->getLine()
        );
        $parameters = $request->getParams();
        if (isset($parameters['password'])) {
            unset($parameters['password']);
        }
        $message = PHP_EOL . $exceptionInfo . PHP_EOL . 'Stack trace: '
            . PHP_EOL . $exception->getTraceAsString() . PHP_EOL
            . 'Request params: ' . PHP_EOL
            . var_export($parameters, true) . PHP_EOL
            . 'Client IP: ' . PHP_EOL
            . var_export($request->getClientIp(), true) . PHP_EOL
            . 'User agent: ' . PHP_EOL
            . var_export($request->getServer('HTTP_USER_AGENT'), true) . PHP_EOL
            . 'Requested URI: ' . PHP_EOL
            . var_export($request->getServer('REQUEST_URI'), true) . PHP_EOL;
        return $message;
    }

}