<?php
/**
 * BundlePhu
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE. This license can also be viewed
 * at http://hobodave.com/license.txt
 *
 * @author      David Abdemoulaie <dave@hobodave.com>
 * @copyright   Copyright (c) 2010 David Abdemoulaie (http://hobodave.com/)
 * @license     http://hobodave.com/license.txt New BSD License
 */
namespace BundlePhu\View\Helper;

use Zend_View_Helper_HeadLink as HeadLink;
use Zend_View_Interface as View;
use Zend_Controller_Front as FrontController;
use Mandragora\File;
use BadMethodCallException;

/**
 * Helper for bundling of all included stylesheets into a single file
 */
class BundleLink extends HeadLink
{
    /**
     * Registry key for placeholder
     *
     * @var string
     */
    protected $_regKey = 'BundlePhu_View_Helper_BundleLink';

    /**
     * Local reference to $view->baseUrl()
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * Directory in which to write bundled css
     *
     * @var string
     */
    protected $cacheDir;

    /**
     * Directory in which to look for css files
     *
     * @var string
     */
    protected $docRoot;

    /**
     * Path the generated bundle is publicly accessible under
     *
     * @var string
     */
    protected $urlPrefix = '/stylesheets';

    /**
     * External command used to minify css
     *
     * This command must write the bundled file to disk, STDOUT will be ignored.
     * The token ':filename'  must be present in command, this will be replaced
     * with the generated bundle name.
     *
     * @var string
     */
    protected $minifyCommand;

    /** @var array */
    protected $contents = ['screen, projection' => '', 'print' => '', 'IE' => ''];

    public function bundleLink(): HeadLink
    {
        return parent::headLink();
    }

    public function setView(View $view): void
    {
        $this->view = $view;
        $this->baseUrl = $this->view->baseUrl();
    }

    public function setCacheDir(string $dir): BundleLink
    {
        $this->cacheDir = $dir;
        return $this;
    }

    /**
     * DocRoot is the base directory on disk where the relative css files can be found.
     *
     * e.g.
     *
     * if $docRoot == '/var/www/foo' then '/css/foo.css' will be found in '/var/www/foo/css/foo.css'
     */
    public function setDocRoot(string $docRoot): BundleLink
    {
        $this->docRoot = $docRoot;
        return $this;
    }

    /**
     * Sets the URL prefix used for the generated link tag
     *
     * e.g. if $urlPrefix == '/stylesheets' then '/stylesheets/bundle_123fdfc3fe8ba8.css'
     * will be the src for the link tag.
     */
    public function setUrlPrefix(string $prefix): BundleLink
    {
        $this->urlPrefix = $prefix;
        return $this;
    }

    /**
     * Command used to generate the minified output file
     *
     * The output of this command is not returned, it must write the output to
     * the generated filename for the bundle. The ':filename' token will be
     * replaced with the generated filename.
     */
    public function setMinifyCommand(string $command): BundleLink
    {
        $this->minifyCommand = $command;
        return $this;
    }

    /**
     * Iterates over stylesheets, concatenating, optionally minifying,
     * optionally compressing, and caching them.
     *
     * This detects updates to the source stylesheets using `filemtime`.
     * A file with an `mtime` more recent than the `mtime` of the cached bundle will
     * invalidate the cached bundle.
     *
     * Modifications of captured css cannot be detected by this.
     *
     * @param string $indent
     * @return string
     * @throws \Mandragora\File\FileException
     * @throws \BadMethodCallException
     */
    public function toString($indent = null)
    {
        $fc = FrontController::getInstance();
        $module = $fc->getRequest()->getModuleName();
        $controller = $fc->getRequest()->getControllerName();
        $action = $fc->getRequest()->getActionName();
        $link = '';
        $isCssBundled = false;
        foreach ($this->contents as $key => $headLink) {
            $fileKey = $key === 'screen, projection' ? 'screen' : $key;
        	$hash = sprintf('%s-%s-%s-%s', $module, $controller, $action, $fileKey);
            $cacheFile = "{$this->docRoot}/{$this->urlPrefix}/bundle-{$hash}.css";
            if (!File::exists($cacheFile)) {
                if (!$isCssBundled) {
                    $this->_setCssData();
                    $isCssBundled =  true;
                }
                $this->_writeUncompressed($cacheFile, $this->contents[$key]);
            }
            $cacheTime = @filemtime($cacheFile);
            $urlPath = "{$this->baseUrl}/{$this->urlPrefix}/bundle-{$hash}.css?{$cacheTime}";
            if (strpos($key, 'IE') === false) {
                $link .= PHP_EOL . '<link href="' . $urlPath . '" media="' . $key . '" rel="stylesheet" type="text/css" />';
            } else {
                $link .= PHP_EOL . '<!--[if ' . $key .']><link rel="stylesheet" type="text/css" media="screen, projection" href="' . $urlPath . '" /><![endif]-->';
            }
        }
        return $link;
    }

    protected function _setCssData(): void
    {
        foreach ($this as $item) {
            $href = $item->href;
            if ($this->baseUrl && strpos($href, $this->baseUrl) !== false) {
                $href =  substr($href, strlen($this->baseUrl));
            }
            if ($item->conditionalStylesheet) {
                $this->contents[$item->conditionalStylesheet] .=
                    file_get_contents($this->docRoot . $href) . PHP_EOL;
            } else {
                $this->contents[$item->media] .=
                    file_get_contents($this->docRoot . $href) . PHP_EOL;
            }
        }
    }

    /**
     * Writes uncompressed bundle to disk
     *
     * @throws BadMethodCallException When neither _minifyCommand or _minifyCallback are defined
     * @throws \Mandragora\File\FileException If temporary file cannot be created
     */
    protected function _writeUncompressed(string $cacheFile, string $bundledData): void
    {
        if (empty($this->minifyCommand)) {
            throw new BadMethodCallException('Neither _minifyCommand or _minifyCallback are defined.');
        }
        $parts = explode('/', $cacheFile);
        $filename = $parts[count($parts) - 1];
        $temp = File::create("{$this->cacheDir}/$filename");
        $temp->write($bundledData);
        $command = str_replace(
            [':filename', ':sourceFile'],
            [escapeshellarg($cacheFile), escapeshellarg($temp->getFullName())],
            $this->minifyCommand
        );
        trim(`$command`);
        $temp->delete();
    }
}
