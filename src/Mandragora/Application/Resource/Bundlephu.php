<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Mandragora\Application\Resource;

use Zend_Application_Resource_ResourceAbstract;
use Zend_Layout;

class Bundlephu extends Zend_Application_Resource_ResourceAbstract
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
            escapeshellarg(APPLICATION_PATH . '/../bin/yuicompressor-2.4.2.jar')
        );
        $view
            ->getHelper('BundleScript')
            ->setCacheDir($options['scripts']['cacheDir'])
            ->setDocRoot($options['docRoot'])
            ->setMinifyCommand($command)
            ->setUrlPrefix($options['scripts']['urlPrefix'])
        ;
        $view
            ->getHelper('BundleLink')
            ->setCacheDir($options['styles']['cacheDir'])
            ->setDocRoot($options['docRoot'])
            ->setMinifyCommand($command)
            ->setUrlPrefix($options['styles']['urlPrefix'])
        ;
	}
}
