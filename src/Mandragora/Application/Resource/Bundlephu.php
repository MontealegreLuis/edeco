<?php
class Mandragora_Application_Resource_Bundlephu
extends Zend_Application_Resource_ResourceAbstract
{
	/**
     * @return void
	 */
	public function init()
	{
        $this->setupBundlePhu();
	}

	/**
	 * @return void
	 */
	public function setupBundlePhu()
	{
		$view = Zend_Layout::getMvcInstance()->getView();
		$options = $this->getOptions();
		$command = sprintf(
            'java -Xmx128m -jar %s :sourceFile -o :filename --charset utf-8',
            escapeshellarg(APPLICATION_PATH . '/yui/yuicompressor-2.4.2.jar')
        );
        $view->getHelper('BundleScript')
             ->setCacheDir($options['scripts']['cacheDir'])
             ->setDocRoot($options['docRoot'])
             ->setMinifyCommand($command)
             ->setUrlPrefix($options['scripts']['urlPrefix']);
        $view->getHelper('BundleLink')
             ->setCacheDir($options['styles']['cacheDir'])
             ->setDocRoot($options['docRoot'])
             ->setMinifyCommand($command)
             ->setUrlPrefix($options['styles']['urlPrefix']);
	}

}