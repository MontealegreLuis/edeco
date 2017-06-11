<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace BundlePhu\View\Helper;

use Mandragora\File;
use PHPUnit_Framework_TestCase as TestCase;
use Zend_Controller_Front as FrontController;
use Zend_Controller_Request_HttpTestCase as TestRequest;
use Zend_View as View;

class BundleScriptTest extends TestCase
{
    /** @test */
    function it_bundles_a_single_javascript_file()
    {
        $this->bundleScript->addJavascriptFile('/edit.js');

        $script = $this->bundleScript->bundleScript(true);

        $this->assertRegExp(
            '/<script type="text\/javascript" src="\/min\/bundle\-admin\-address\-edit\.js\?\d{10}"><\/script>/',
            $script
        );
        $this->assertTrue(
            File::exists($this->bundleFile),
            "File $this->bundleFile was not generated"
        );
        $this->assertStringEqualsFile(
            $this->bundleFile,
            'var message="it works";console.log(message);'
        );
    }

    /** @before */
    function configureViewHelper(): void
    {
        $this->frontController = FrontController::getInstance();
        $request = new TestRequest();
        $request->setModuleName('admin')->setControllerName('address')->setActionName('edit');
        $this->frontController->setRequest($request);
        $command = sprintf(
            'java -Xmx128m -jar %s :sourceFile -o :filename --charset utf-8',
            escapeshellarg(__DIR__ . '/../../../../../bin/yuicompressor-2.4.2.jar')
        );
        $publicPath = __DIR__ . '/../../../../../tests/fixtures';
        $bundlePath = 'min';

        $this->bundleFile = "$publicPath/$bundlePath/bundle-admin-address-edit.js";

        // Cleanup test file
        file_exists($this->bundleFile) && unlink($this->bundleFile);

        $this->bundleScript = new BundleScript();
        $this
            ->bundleScript
            ->setMinifyCommand($command)
            ->setCacheDir(__DIR__ . '/../../../../../var/cache/js')
            ->setDocRoot($publicPath)
            ->setUrlPrefix($bundlePath)
            ->setView(new View(['basePath' => '/']))
        ;
    }

    /** @var BundleScript */
    private $bundleScript;

    /** @var string */
    private $bundleFile;

    /** @var FrontController */
    private $frontController;
}
