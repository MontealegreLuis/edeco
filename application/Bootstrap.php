<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright  Mandrágora Web-Based Systems 2010-2015 (http://www.mandragora-web-systems.com)
 */

/**
 * Bootstrap class for Mandrágora's application
 *
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * @return void
     */
    protected function _initCachePage()
    {
        $frontendOptions = array(
            'debug_header' => false,
            'lifetime' => null,
            'default_options' => array(
                'cache' => false,
                'cache_with_cookie_variables' => true,
                'cache_with_get_variables' => true,
                'cache_with_post_variables' => false,
                'cache_with_session_variables' => true,
                'cache_with_files_variables' => false,
                'make_id_with_cookie_variables' => false,
                'make_id_with_get_variables ' => true,
            ),
            'regexps' => array(
                '^/objetivos-estrategicos$' => array('cache' => true,),
                '^/quienes-somos$' => array('cache' => true,),
                '^/contacto$' => array('cache' => true,),
                '^/declaracion-de-accesibilidad$' => array('cache' => true,),
                '^/aviso-legal$' => array('cache' => true,),
                '^/ayuda' => array('cache' => true),
        		'^/constructora' => array('cache' => true),
            )
        );
        $backendOptions = array('cache_dir' => APPLICATION_PATH . '/../var/cache/page');
        $cache = Zend_Cache::factory('Page', 'File', $frontendOptions, $backendOptions);
        $cache->start();
    }

    /**
     * Initialize the path to this system resources (forms, services, and
     * models)
     *
     * @return Zend_Loader_Autoloader_Resource
     */
    protected function _initResources()
    {
        $resourceLoader = new Zend_Loader_Autoloader_Resource(
            array(
                'basePath' => APPLICATION_PATH,
                'namespace' => 'App_',
                'resourceTypes' =>
                 array(
                     'forms' =>
                         array('path' => 'forms', 'namespace' => 'Form_'),
                    'services' =>
                        array('path' => 'services', 'namespace' => 'Service_'),
                    'models' =>
                        array('path' => 'models', 'namespace' => 'Model_'),
                    'enums' =>
                        array('path' => 'enums', 'namespace' => 'Enum_'),
                ),
            )
        );
        return $resourceLoader;
    }

    /**
     * Initialize action helpers
     *
     * @return void
     */
    protected function _initActionHelpers()
    {
        Zend_Controller_Action_HelperBroker::addPrefix(
            'Mandragora_Controller_Action_Helper'
        );
        Zend_Controller_Action_HelperBroker::addPrefix(
            'Edeco_Controller_Action_Helper'
        );
    }
}
