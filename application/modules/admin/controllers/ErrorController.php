<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Mandragora\Log;

/**
 * Error controller
 */
class Admin_ErrorController extends Zend_Controller_Action
{
    /**
     * Default action performed whenever an error occurs
     *
     * @return void
     */
    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'No se encontró la página';
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->message = 'Página temporalmente fuera de servicio';
                Log::getInstance()->error($errors->exception, $errors->request);
                break;
        }
        $this->view->exception = $errors->exception;
        $this->view->request = $errors->request;
    }

    /**
     * Action performed when a user has no access to a given action
     *
     * @return void
     */
    public function unauthorizedAction() { }
}
