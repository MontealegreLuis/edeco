<?php
/**
 * Service that holds all the default routes
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
 * @package    Axis
 * @subpackage Service_Router
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Service that holds all the default routes
 *
 * @category   Library
 * @package    Axis
 * @subpackage Service_Router
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class      App_Service_Router_Helper
implements Mandragora_Service_Router_Interface
{
    /**
     * @var array
     */
    protected $routes;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->routes = array(
            'login' => array(
                'controller' => 'index', 'action' => 'login',
                'module' => 'admin', 'route' => 'index',
            ),
            'unauthorized' => array(
                'controller' => 'error', 'action' => 'unauthorized',
                'module' => 'admin', 'route' => 'controllers',
            ),
            'admin' => array(
                'module' => 'admin', 'controller' => 'property',
                'action' => 'list', 'route' => 'controllers',
            ),
            'client' => array(
                'module' => 'admin', 'controller' => 'project',
                'action' => 'list', 'route' => 'controllers',
            )
        );
    }

    /**
     * @param string $key
     * @return array
     */
    public function getDefaultRoute($key)
    {
        return $this->routes[$key];
    }

}