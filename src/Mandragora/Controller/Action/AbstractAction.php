<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Controller\Action;

use Zend_Controller_Action as Action;
use Zend_Controller_Action_Exception as ActionException;
use Zend_Filter_Input as InputFilter;

/**
 * Base class for Mandragora controllers
 */
abstract class AbstractAction extends Action
{
    /** @var \Mandragora\Service\Crud\CrudService */
    protected $service;

    /**
     * Container of the metadata for input filtering/validation and the allowed
     * method for each action in this controller
     *
     * @var array
     */
    protected $validMethods = [];

    public function getAppSetting(string $key): array
    {
        return $this->getInvokeArg('bootstrap')
                    ->getResource('settings')
                    ->getSetting($key);
    }

    /**
     * @return \Zend_Cache_Manager
     */
    public function getCacheManager()
    {
        return $this->getInvokeArg('bootstrap')->getResource('cachemanager');
    }

    /**
     * Verify if the the method of this request is the expected and
     * filter/validate the input if needed
     *
     * @return void
     * @throws UnknownValidationException
     * @throws ActionException
     */
    public function preDispatch()
    {
        $action = $this->getRequest()->getActionName();
        if (!array_key_exists($action, $this->validMethods)) {
            $this->validateMethod('get');
            return;
        }
        $method = array_key_exists('method', $this->validMethods[$action])
            ? $this->validMethods[$action]['method']
            : 'get';
        $this->validateMethod($method);
        if (array_key_exists('validations', $this->validMethods[$action])
            && is_array($this->validMethods[$action]['validations'])) {
            $this->validateInput($this->validMethods[$action]['validations']);
        }
    }

    /**
     * @throws \Zend_Controller_Action_Exception
     */
    protected function validateMethod(string $method)
    {
        $isMethod = 'is' . ucfirst(strtolower($method));
        if (!$this->getRequest()->$isMethod()) {
            throw new ActionException(
                'Invalid method ' . $this->getRequest()->getMethod()
                . ' found, ' . strtoupper($method) . ' was expected'
            );
        }
    }

    /**
     * Validates parameters of get and ajax requests
     *
     * @param array $params
     * @throws UnknownValidationException
     * @throws ActionException
     */
    public function validateInput(array $params)
    {
        $filters = [];
        $validators = [];
        foreach ($params as $key => $validationType) {
            $filters[$key] = ['HtmlEntities', 'StripTags', 'StringTrim'];
            switch ($validationType) {
                case 'int' :
                    $validators[$key] = ['NotEmpty', 'Int'];
                    break;
                case 'id' :
                    $validators[$key] = ['NotEmpty', 'Int', ['GreaterThan', 0]];
                    break;
                case 'key' :
                    $validators[$key] = [['NotEmpty', ['string']], 'Alnum'];
                    break;
                default:
                    throw new UnknownValidationException(
                       "Invalid validation type: $validationType"
                    );
            }
        }
        $input = new InputFilter($filters, $validators);
        $input->setData($this->getRequest()->getParams());
        if (!$input->isValid()) {
            throw new ActionException(
                'Error occured when validating the following input parameters: '
                . implode(', ', array_keys($input->getErrors()))
            );
        }

    }

    public function redirectToRoute(
        string $action,
        array $params = [],
        string $controller = '',
        string $module = '',
        string $route = null
    ) {
        $controller = $controller === ''
            ? $this->getRequest()->getControllerName()
            : $controller;
        $module = $module === ''
            ? $this->getRequest()->getModuleName()
            : $module;
        $params += [
            'module' => $module, 'controller' => $controller,
            'action' => $action,
        ];
        $this->redirect(
            $this->view->url($params, $route, true),
            ['prependBase' => false]
        );
    }

    /**
     * @return \Zend_Controller_Action_Helper_FlashMessenger
     */
    public function flash(string $namespace = null)
    {
        if ($namespace) {
            $this->_helper->flashMessenger->setNamespace($namespace);
        }
        return $this->_helper->flashMessenger;
    }

    public function params(): array
    {
        return $this->getRequest()->getParams();
    }

    /**
     * @param mixed $default = null
     * @return mixed
     */
    public function param(string $key, $default = null)
    {
        return $this->getRequest()->getParam($key, $default);
    }

    /**
     * @param mixed $default = null
     * @return array | string
     */
    public function post(string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->getRequest()->getPost();
        }
        return $this->getRequest()->getPost($key, $default);
    }
}
