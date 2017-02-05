<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service\Router;

use Mandragora\Service\Router\RouterInterface;

/**
 * Service that holds all the default routes
 */
class Helper implements RouterInterface
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
