<?php
/**
 * Breadcrumbs builder for Edeco's application.
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
 * @subpackage Breadcumbs
 * @author     LNJ <lemuel.nonoal@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Breadcrumbs builder for Edeco's application.
 *
 *
 * @author     LNJ <lemuel.nonoal@mandragora-web-systems.com>
 * @version    SVN: $Id$
 * @copyright  Mandrágora Web-Based Systems 2010
 * @category   Application
 * @package    Edeco
 * @subpackage BreadscrumbsBuilder
 * @history    20 may 2010
 *             LNJ
 *             - Class Creation
 */
class Mandragora_Controller_Action_Helper_BreadcrumbsBuilder
    extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * Sets the params for the routes of the current controller, in order to
     * build a breadcrumb
     *
     * @param  string $controller
     *      The current controller name
     * @param  array $params
     *      The request parameters
     * @return Zend_Navigation
     *      The breadcrumbs container
     */
    public function buildBreadcrumbs($topic)
    {
        $pathToConfig = APPLICATION_PATH . sprintf(
            '/configs/breadcrumbs/help/%s.xml', $topic
        );
        $config = new Zend_Config_Xml($pathToConfig, 'nav');
        $container = new Zend_Navigation($config);
        //$it = new RecursiveIteratorIterator(
        //    $container, RecursiveIteratorIterator::SELF_FIRST
        //);
        //foreach ($it as $page) {
        //    $page->setOptions(array('params' => $params));
        //}
        return $container;
    }

    /**
     * Strategy pattern: call helper as broker method
     *
     * @param Zend_Controller_Request_Abstract $request
     * @return Zend_Navigation
     */
    public function direct(Zend_Controller_Request_Abstract $request)
    {
        return $this->buildBreadcrumbs(
            $request->getParam('topic', 'index')
            //$request->getParam('operation', 'index')
        );
    }

}