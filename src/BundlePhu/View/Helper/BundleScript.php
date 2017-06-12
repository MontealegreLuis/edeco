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

use ZendX_JQuery_View_Helper_JQuery as jQuery;
use Zend_View_Interface as View;
use Zend_Controller_Front as FrontController;
use Mandragora\File;
use BadMethodCallException;

/**
 * Helper for bundling of all included javascript files into a single file
 *
 * @author      David Abdemoulaie <dave@hobodave.com>
 * @copyright   Copyright (c) 2010 David Abdemoulaie (http://hobodave.com/)
 * @license     http://hobodave.com/license.txt New BSD License
 */
class BundleScript extends JQuery
{
    /**
     * local Zend_View reference
     *
     * @var Zend_View_Interface
     */
    public $view;

    /**
     * Registry key for placeholder
     * @var string
     */
    protected $_regKey = 'BundlePhu_View_Helper_BundleScript';

    /**
     * Local reference to $view->baseUrl()
     *
     * @var string
     */
    protected $_baseUrl;

    /**
     * Directory in which to write bundled javascript
     *
     * @var string
     */
    protected $_cacheDir;

    /**
     * Directory in which to look for js files
     *
     * @var string
     */
    protected $_docRoot;

    /**
     * Path the generated bundle is publicly accessible under
     *
     * @var string
     */
    protected $_urlPrefix = "/javascripts";

    /**
     * External command used to minify javascript
     *
     * This command must write the bundled file to disk, STDOUT will be ignored.
     * The token ':filename'  must be present in command, this will be replaced
     * with the generated bundle name.
     *
     * @var string
     */
    protected $_minifyCommand;

    /**
     * @var string
     */
    protected $contents;

    public function setView(View $view)
    {
        $this->view = $view;
        $this->_baseUrl = $this->view->baseUrl();
    }

    /**
     * Proxies to Zend_View_Helper_HeadScript::headScript()
     *
     * @return string | \ZendX_JQuery_View_Helper_JQuery_Container
     */
    public function bundleScript($render = false)
    {
        return $render ? (string) $this : parent::jQuery();
    }

    /**
     * This is where the bundled files are written.
     */
    public function setCacheDir(string $dir): BundleScript
    {
        $this->_cacheDir = $dir;
        return $this;
    }

    /**
     * DocRoot is the base directory on disk where the relative js files can be found.
     *
     * e.g.
     *
     * if $docRoot == '/var/www/foo' then '/js/foo.js' will be found in '/var/www/foo/js/foo.js'
     */
    public function setDocRoot(string $docRoot): BundleScript
    {
        $this->_docRoot = $docRoot;
        return $this;
    }

    /**
     * Sets the URL prefix used for the generated script tag
     *
     * e.g. if $urlPrefix == '/javascripts' then '/javascripts/bundle_123fdfc3fe8ba8.js'
     * will be the src for the script tag.
     */
    public function setUrlPrefix(string $prefix): BundleScript
    {
        $this->_urlPrefix = $prefix;
        return $this;
    }

    /**
     * Command used to generate the minified output file
     *
     * The output of this command is not returned, it must write the output to
     * the generated filename for the bundle. The ':filename' token will be
     * replaced with the generated filename.
     */
    public function setMinifyCommand(string $command): BundleScript
    {
        $this->_minifyCommand = $command;
        return $this;
    }


    /**
     * Iterates over scripts, concatenating, optionally minifying,
     * optionally compressiong, and caching them.
     *
     * This detects updates to the source javascripts using filemtime.
     * A file with an mtime more recent than the mtime of the cached bundle will
     * invalidate the cached bundle.
     *
     * Modifications of captured scripts cannot be detected by this.
     * DONT USE DYNAMICALLY GENERATED CAPTURED SCRIPTS.
     *
     * @return string
     * @throws UnexpectedValueException if item has no src attribute or contains no captured source
     */
    public function __toString()
    {
        $fc = FrontController::getInstance();
        $module = $fc->getRequest()->getModuleName();
        $controller = $fc->getRequest()->getControllerName();
        $action = $fc->getRequest()->getActionName();
        $hash = sprintf('%s-%s-%s', $module, $controller, $action);
        $cacheFile = "{$this->_docRoot}/{$this->_urlPrefix}/bundle-{$hash}.js";
        if (!File::exists($cacheFile)) {
            $this->_getJsData();
            $this->_writeUncompressed($cacheFile, $this->contents);
        }
        $cacheTime = @filemtime($cacheFile);
        $urlPath = "{$this->_baseUrl}/{$this->_urlPrefix}/bundle-{$hash}.js?{$cacheTime}";
        $ret = PHP_EOL . '<script type="text/javascript" src="' . $urlPath . '"></script>';
        $onLoad = $this->formatJqueryOnload();
        if (!empty($onLoad)) {
            $ret .= PHP_EOL . "<script type=\"text/javascript\">\n//<![CDATA[\n";
            $ret .= "{$this->formatJqueryOnload()}\n//]]>\n</script>" . PHP_EOL;
        }
        return $ret;
    }

    /**
     * Iterates through the scripts and returning a concatenated result.
     *
     * Assumes the container is sorted prior to entry.
     */
    private function _getJsData(): void
    {
        $jsFiles = [];
        if ($this->_container->isEnabled()) {
            $jsFiles[] = $this->_container->getLocalPath();
            if ($this->_container->uiIsEnabled()) {
                $jsFiles[] = $this->_container->getUiLocalPath();
            }
        }
        $jsFiles = array_merge($jsFiles, $this->_container->getJavascriptFiles());
        foreach ($jsFiles as $item) {
            $this->contents .= file_get_contents($this->_docRoot . $item)
                            .  PHP_EOL;
        }
    }

    /**
     * Add document ready statement to the current registered onLoad actions
     */
    private function formatJqueryOnload(): string
    {
        $actions = $this->_container->getOnLoadActions();
        $onLoad = '';
        if (count($actions) > 0) {
            $onLoad = implode(PHP_EOL, $this->_container->getOnLoadActions());
            $onLoad = sprintf("$(document).ready(function() {\n    %s\n});", $onLoad);
        }
        return $onLoad;
    }

    /**
     * Writes uncompressed bundle to disk
     *
     * @throws BadMethodCallException When _minifyCommand is not defined
     */
    protected function _writeUncompressed(string $cacheFile, ?string $data)
    {
        if (!empty($this->_minifyCommand)) {
            $parts = explode('/', $cacheFile);
            $filename = $parts[count($parts) - 1];
            $temp = File::create("{$this->_cacheDir}/$filename");
            $temp->write($data);
            $command = str_replace(
                ':filename', escapeshellarg($cacheFile), $this->_minifyCommand
            );
            $command = str_replace(
                ':sourceFile', escapeshellarg($temp->getFullName()), $command
            );
            trim(`$command`);
            $temp->delete();
        } else {
            throw new BadMethodCallException("_minifyCommand is not defined.");
        }
    }
}
