<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright  Mandrágora Web-Based Systems 2010-2015 (http://www.mandragora-web-systems.com)
 */

/**
 * Base class for Mandragora controllers
 *
 * @package    Mandragora
 * @subpackage Controller_Action
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 */
abstract class Mandragora_Controller_Action_Abstract extends Zend_Controller_Action
{
    /**
     * @var Mandragora_Service_Abstract
     */
    protected $service;

    /**
     * Container of the metadata for input filtering/validation and the allowed
     * method for each action in this controller
     *
     * @var array
     */
    protected $validMethods = array();

    /**
     * @param string key
     * @return array
     */
    public function getAppSetting($key)
    {
        return $this->getInvokeArg('bootstrap')
                    ->getResource('settings')
                    ->getSetting((string)$key);
    }

    /**
     * @return Zend_Cache_Manager
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
     */
    public function preDispatch()
    {
        $action = $this->getRequest()->getActionName();
        if (!array_key_exists($action, $this->validMethods)) {
            $this->validateMethod('get');
        } else {
            $method = array_key_exists('method', $this->validMethods[$action])
                ? $this->validMethods[$action]['method']
                : 'get';
            $this->validateMethod($method);
            if (array_key_exists('validations', $this->validMethods[$action])
                && is_array($this->validMethods[$action]['validations'])) {
                $this->validateInput(
                    $this->validMethods[$action]['validations']
                );
            }
        }
    }

    /**
     * @param string $method
     * @throws Zend_Controller_Action_Exception
     */
    protected function validateMethod($method)
    {
        $isMethod = 'is' . ucfirst(strtolower($method));
        if (!$this->getRequest()->$isMethod()) {
            throw new Zend_Controller_Action_Exception(
                'Invalid method ' . $this->getRequest()->getMethod()
                . ' found, ' . strtoupper($method) . ' was expected'
            );
        }
    }

    /**
     * Validates parameters of get and ajax requests
     *
     * @param array $params
     * @throws Mandragora_Controller_Action_UnknownValidationException
     * @throws Zend_Controller_Action_Exception
     */
    public function validateInput(array $params)
    {
        $filters = array();
        $validators = array();
        foreach ($params as $key => $validationType) {
            $filters[$key] = array(
               'HtmlEntities', 'StripTags', 'StringTrim'
            );
            switch ($validationType) {
                case 'int' :
                    $validators[$key] = array('NotEmpty', 'Int');
                    break;
                case 'id' :
                    $validators[$key] = array(
                        'NotEmpty', 'Int', array('GreaterThan', 0)
                    );
                    break;
                case 'key' :
                    $validators[$key] = array(
                        array('NotEmpty', array('string')), 'Alnum'
                    );
                    break;
                default:
                    throw new
                       Mandragora_Controller_Action_UnknownValidationException(
                           "Invalid validation type: $validationType"
                       );
                    break;
            }
        }
        $input = new Zend_Filter_Input($filters, $validators);
        $input->setData($this->getRequest()->getParams());
        if (!$input->isValid()) {
            throw new Zend_Controller_Action_Exception(
                'Error occured when validating the following input parameters: '
                . implode(', ', array_keys($input->getErrors()))
            );
        }

    }

    /**
     * @param string $action
     * @param array $params = array()
     * @param string $controller = ''
     * @param string $module = ''
     * @param string $route = null
     */
    public function redirectToRoute(
        $action, $params = array(), $controller = '', $module = '',
        $route = null
    )
    {
        $controller = $controller == ''
            ? $this->getRequest()->getControllerName()
            : $controller;
        $module = $module == ''
            ? $this->getRequest()->getModuleName()
            : $module;
        $params += array(
            'module' => $module, 'controller' => $controller,
            'action' => $action,
        );
        $this->_redirect(
            $this->view->url($params, $route, true),
            array('prependBase' => false)
        );
    }

    /**
     * @param $namespace = null
     * @return Zend_Controller_Action_Helper_FlashMessenger
     */
    public function flash($namespace = null)
    {
        if ($namespace) {
            $this->_helper->flashMessenger->setNamespace($namespace);
        }
        return $this->_helper->flashMessenger;
    }

    /**
     * @return array
     */
    public function params()
    {
        return $this->getRequest()->getParams();
    }

    /**
     * @param string $key
     * @param $default = null
     * @return mixed
     */
    public function param($key, $default = null)
    {
        return $this->getRequest()->getParam($key, $default);
    }

    /**
     * @param string $key = null
     * @param mixed $default = null
     * @return array | string
     */
    public function post($key = null, $default = null)
    {
        if ($key == null) {
            return $this->getRequest()->getPost();
        }
        return $this->getRequest()->getPost($key, $default);
    }

}