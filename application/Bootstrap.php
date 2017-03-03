<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Mandragora\Loader\PluginLoader;
use Zend_Cache as Cache;
use Zend_Locale as Locale;
use Zend_Controller_Action_HelperBroker as HelperBroker;

/**
 * Configures:
 * - Cache for static pages
 * - Locale (including its cache)
 * - Custom plugin loader for plugins that use namespaces
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * @return void
     */
    protected function _initCachePage()
    {
        $frontendOptions = [
            'debug_header' => false,
            'lifetime' => null,
            'default_options' => [
                'cache' => false,
                'cache_with_cookie_variables' => true,
                'cache_with_get_variables' => true,
                'cache_with_post_variables' => false,
                'cache_with_session_variables' => true,
                'cache_with_files_variables' => false,
                'make_id_with_cookie_variables' => false,
                'make_id_with_get_variables ' => true,
            ],
            'regexps' => [
                '^/objetivos-estrategicos$' => ['cache' => true,],
                '^/quienes-somos$' => ['cache' => true,],
                '^/contacto$' => ['cache' => true,],
                '^/declaracion-de-accesibilidad$' => ['cache' => true,],
                '^/aviso-legal$' => ['cache' => true,],
                '^/ayuda' => ['cache' => true],
        		'^/constructora' => ['cache' => true],
            ]
        ];
        $backendOptions = ['cache_dir' => APPLICATION_PATH . '/../var/cache/page'];
        $cache = Cache::factory('Page', 'File', $frontendOptions, $backendOptions);
        $cache->start();
    }

    /**
     * @return Locale
     * @throws \Zend_Cache_Exception
     * @throws \Zend_Locale_Exception
     */
    protected function _initLocale()
    {
        $frontendOptions = [
            'debug_header' => false,
            'lifetime' => null,
        ];
        $backendOptions = ['cache_dir' => APPLICATION_PATH . '/../var/cache/locale'];
        $locale = new Locale('es_MX');
        $cache = Cache::factory('CORE', 'FILE', $frontendOptions, $backendOptions);
        $locale->setCache($cache);

        return $locale;
    }

    /**
     * Initialize action helpers
     *
     * @return void
     */
    protected function _initActionHelpers()
    {
        HelperBroker::setPluginLoader(new PluginLoader());
        HelperBroker::addPrefix('Zend_Controller_Action_Helper');
        HelperBroker::addPrefix('Mandragora\\Controller\\Action\\Helper');
        HelperBroker::addPrefix('Edeco\\Controller\\Action\\Helper');
    }
}
