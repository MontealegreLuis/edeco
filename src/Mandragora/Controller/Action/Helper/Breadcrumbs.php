<?php
/**
 * Breadcrumbs builder
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
 * @package    Controller
 * @subpackage Action_Helper
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2011
 * @version    SVN: $Id$
 */
use  \Zend_Controller_Action_Helper_Abstract as HelperAbstract
    ,\Zend_Controller_Request_Abstract as Request;

/**
 * Breadcrumbs builder
 *
 *
 * @category   Library
 * @package    Controller
 * @subpackage Action_Helper
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2011
 * @version    SVN: $Id$
 */
class   Mandragora_Controller_Action_Helper_Breadcrumbs
extends HelperAbstract
{
    /**
     * Sets the params for the routes of the current controller, in order to
     * build a breadcrumb
     *
     * @param  string $module
     * @param  array $controller
     * @return Zend_Navigation
     */
    public function buildBreadcrumbs($module, $controller, $params)
    {
        $pathToConfig = APPLICATION_PATH . sprintf(
            '/configs/breadcrumbs/%s/%s.ini', $module, $controller
        );
        $config = new Zend_Config_Ini($pathToConfig, APPLICATION_ENV);
        $container = new Zend_Navigation($config);
        $it = new RecursiveIteratorIterator(
            $container, RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($it as $page) {
            foreach ($page->getParams() as $key => $value) {
                if (isset($params[$key])) {
                    $pageParams = $page->getParams();
                    $pageParams[$key] = $params[$key];
                    $page->setParams($pageParams);
                }
            }
        }
        return $container;
    }

    /**
     * Strategy pattern: call helper as broker method
     *
     * @param Zend_Controller_Request_Abstract $request
     * @return Zend_Navigation
     */
    public function direct(Request $request)
    {
        return $this->buildBreadcrumbs(
            $request->getModulename(), $request->getControllerName(),
            $request->getParams()
        );
    }

}