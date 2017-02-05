<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora;

use Exception;
use Mandragora\Mail\Transport\Debug;
use Zend_Controller_Front;
use Zend_Controller_Request_Abstract;
use Zend_Layout;
use Zend_Log_Formatter_Simple;
use Zend_Log_Writer_Mail;
use Zend_Log;
use Zend_Mail;

/**
 * Logger for applications
 */
class Log
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
            $mailTransport = new Debug();
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
        Exception $exception,
        Zend_Controller_Request_Abstract $request
    )
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
