<?php
class Mandragora_Controller_Plugin_Router
    extends Zend_Controller_Plugin_Abstract
{
    public function routeStartup(Zend_Controller_Request_Abstract $request)
    {
        $frontController = Zend_Controller_Front::getInstance();
        $router = $frontController->getRouter();
        $iniPath = sprintf('/configs/routes/%s.ini', ROUTES);
        $router->addConfig(
            new Zend_Config_Ini(APPLICATION_PATH . $iniPath, 'production'),
            'routes'
        );
        $iniPath = APPLICATION_PATH . '/configs/languages/routes/es/MX.csv';
        $translate = new Zend_Translate('csv', $iniPath, 'es_MX');
        Zend_Controller_Router_Route::setDefaultTranslator($translate);
    }
}