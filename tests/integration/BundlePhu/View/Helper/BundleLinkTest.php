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

class BundleLinkTest extends TestCase
{
    /** @test */
    function it_bundles_a_single_css_file()
    {
        $this->bundleLink->appendStylesheet('/styles/breadcrumbs.css', 'screen, projection');

        $link = $this->bundleLink->toString();

        $this->assertRegExp('/<link href="\/styles\/min\/bundle\-admin\-address\-edit\-screen\.css\?\d{10}" media="screen, projection" rel="stylesheet" type="text\/css" \/>/', $link);
        $this->assertRegExp('/<link href="\/styles\/min\/bundle\-admin\-address\-edit\-print\.css\?\d{10}" media="print" rel="stylesheet" type="text\/css" \/>/', $link);
        $this->assertRegExp('/<!\-\-\[if IE\]><link rel="stylesheet" type="text\/css" media="screen, projection" href="\/styles\/min\/bundle\-admin\-address\-edit\-IE\.css\?\d{10}" \/><!\[endif\]\-\->/', $link);
        $this->assertTrue(File::exists($this->bundleFile)); // there should be three files...
    }

    /** @before */
    function configureViewHelper()
    {
        $this->frontController = FrontController::getInstance();

        $request = new TestRequest();
        $request->setModuleName('admin')->setControllerName('address')->setActionName('edit');
        $this->frontController->setRequest($request);

        $publicPath = __DIR__ . '/../../../../../admin.edeco.mx';
        $bundlePath = 'styles/min';
        $this->bundleFile = "$publicPath/$bundlePath/bundle-admin-address-edit-screen.css";

        $this->bundleLink = new BundleLink();
        $view = new View();
        $view->setBasePath('/');

        $command = sprintf(
            'java -Xmx128m -jar %s :sourceFile -o :filename --charset utf-8',
            escapeshellarg(__DIR__ . '/../../../../../bin/yuicompressor-2.4.2.jar')
        );
        $this
            ->bundleLink
            ->setMinifyCommand($command)
            ->setCacheDir(__DIR__ . '/../../../../../var/cache/js')
            ->setDocRoot($publicPath)
            ->setUrlPrefix($bundlePath)
            ->setView($view)
        ;
    }

    /** @var BundleLink */
    private $bundleLink;

    /** @var  FrontController */
    private $frontController;

    /** @var TestRequest */
    private $request;

    /** @var string */
    private $bundleFile;
}
