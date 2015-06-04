<?php
/**
 * Bootstrap class for Mandrágora's application
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
 * @subpackage Bootstrap
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Bootstrap class for Mandrágora's application
 *
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @version    SVN: $Id$
 * @copyright  Mandrágora Web-Based Systems 2010
 * @category   Application
 * @package    Edeco
 * @subpackage Bootstrap
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
        $backendOptions = array(
            'cache_dir' => APPLICATION_PATH . '/data/cache/page'
        );
        $cache = Zend_Cache::factory(
            'Page', 'File', $frontendOptions, $backendOptions
        );
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