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
    function it_bundles_no_files()
    {
        $links = $this->bundleLink->toString();

        $this->assertLinkElements($links);
        $this->assertMinifiedFilesExist();
        $this->assertBundleFileContents('');
    }

    /** @test */
    function it_bundles_a_single_css_file()
    {
        $this->bundleLink->appendStylesheet('/breadcrumbs.css', 'screen, projection');

        $links = $this->bundleLink->toString();

        $this->assertLinkElements($links);
        $this->assertMinifiedFilesExist();
        $this->assertBundleFileContents('.breadcrumb-message{width:150px;padding-top:2em;}');
    }

    /** @test */
    function it_bundles_several_css_files()
    {
        $this->bundleLink->appendStylesheet('/global.css', 'screen, projection');
        $this->bundleLink->appendStylesheet('/navbar.css', 'screen, projection');
        $this->bundleLink->appendStylesheet('/breadcrumbs.css', 'screen, projection');

        $links = $this->bundleLink->toString();

        $this->assertLinkElements($links);
        $this->assertMinifiedFilesExist();
        $css = file_get_contents(sprintf($this->bundleFile, 'screen'));
        $this->assertRegExp('/body\{font\-size:1\.3em;\}/', $css);
        $this->assertRegExp('/ul\{list\-style\-type:none;\}/', $css);
        $this->assertRegExp('/breadcrumb\-message\{width:150px;padding\-top:2em;\}/', $css);
    }

    private function assertLinkElements(string $links): void
    {
        $this->assertRegExp('/<link href="\/min\/bundle\-admin\-address\-edit\-screen\.css\?\d{10}" media="screen, projection" rel="stylesheet" type="text\/css" \/>/', $links);
        $this->assertRegExp('/<link href="\/min\/bundle\-admin\-address\-edit\-print\.css\?\d{10}" media="print" rel="stylesheet" type="text\/css" \/>/', $links);
        $this->assertRegExp('/<!\-\-\[if IE\]><link rel="stylesheet" type="text\/css" media="screen, projection" href="\/min\/bundle\-admin\-address\-edit\-IE\.css\?\d{10}" \/><!\[endif\]\-\->/', $links);
    }

    private function assertMinifiedFilesExist(): void
    {
        $this->assertTrue(File::exists(sprintf($this->bundleFile, 'screen')));
        $this->assertTrue(File::exists(sprintf($this->bundleFile, 'print')));
        $this->assertTrue(File::exists(sprintf($this->bundleFile, 'IE')));
    }

    private function assertBundleFileContents(string $minifiedCss): void
    {
        $this->assertStringEqualsFile(sprintf($this->bundleFile, 'screen'), $minifiedCss);
    }

    /** @before */
    function configureViewHelper(): void
    {
        $this->frontController = FrontController::getInstance();

        $request = new TestRequest();
        $request->setModuleName('admin')->setControllerName('address')->setActionName('edit');
        $this->frontController->setRequest($request);

        $publicPath = __DIR__ . '/../../../../../tests/fixtures';
        $bundlePath = 'min';
        $this->bundleFile = "$publicPath/$bundlePath/bundle-admin-address-edit-%s.css";

        // Cleanup test files
        file_exists(sprintf($this->bundleFile, 'screen')) && unlink(sprintf($this->bundleFile, 'screen'));
        file_exists(sprintf($this->bundleFile, 'print')) && unlink(sprintf($this->bundleFile, 'print'));
        file_exists(sprintf($this->bundleFile, 'IE')) && unlink(sprintf($this->bundleFile, 'IE'));

        $this->bundleLink = new BundleLink();
        $command = sprintf(
            'java -Xmx128m -jar %s :sourceFile -o :filename --charset utf-8',
            escapeshellarg(__DIR__ . '/../../../../../bin/yuicompressor-2.4.2.jar')
        );
        $this
            ->bundleLink
            ->setMinifyCommand($command)
            ->setCacheDir(__DIR__ . '/../../../../../var/cache/css')
            ->setDocRoot($publicPath)
            ->setUrlPrefix($bundlePath)
            ->setView(new View(['basePath' => '/']))
        ;
    }

    /** @var BundleLink */
    private $bundleLink;

    /** @var  FrontController */
    private $frontController;

    /** @var string */
    private $bundleFile;
}
