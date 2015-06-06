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
 * @category    BundlePhu
 * @package     BundlePhu_View
 * @subpackage  Helper
 * @author      David Abdemoulaie <dave@hobodave.com>
 * @copyright   Copyright (c) 2010 David Abdemoulaie (http://hobodave.com/)
 * @license     http://hobodave.com/license.txt New BSD License
 */

/**
 * Helper for bundling of all included stylesheets into a single file
 *
 * @category    BundlePhu
 * @package     BundlePhu_View
 * @subpackage  Helper
 * @author      David Abdemoulaie <dave@hobodave.com>
 * @copyright   Copyright (c) 2010 David Abdemoulaie (http://hobodave.com/)
 * @license     http://hobodave.com/license.txt New BSD License
 **/
class BundlePhu_View_Helper_BundleLink extends Zend_View_Helper_HeadLink
{
    /**
     * Local Zend_View reference
     *
     * @var Zend_View_Interface
     */
    public $view;

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
    protected $_baseUrl;

    /**
     * Directory in which to write bundled css
     *
     * @var string
     */
    protected $_cacheDir;

    /**
     * Directory in which to look for css files
     *
     * @var string
     */
    protected $_docRoot;

    /**
     * Path the generated bundle is publicly accessible under
     *
     * @var string
     */
    protected $_urlPrefix = "/stylesheets";

    /**
     * External command used to minify css
     *
     * This command must write the bundled file to disk, STDOUT will be ignored.
     * The token ':filename'  must be present in command, this will be replaced
     * with the generated bundle name.
     *
     * @var string
     */
    protected $_minifyCommand;

    /**
     * @var array
     */
    protected $contents = array(
        'screen, projection' => '', 'print' => '', 'IE' => ''
    );

    /**
     * Inject the local copy of the current Zend_View object
     *
     * @param Zend_View_Interface $view
     * @return void
     */
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
        $this->_baseUrl = $this->view->baseUrl();
    }

    /**
     * Proxies to Zend_View_Helper_HeadLink::headLink()
     *
     * @return BundlePhu_View_Helper_BundleLink
     */
    public function bundleLink()
    {
        return parent::headLink();
    }

    /**
     * Sets the cache dir
     *
     * This is where the bundled files are written.
     *
     * @param string $dir
     * @return BundlePhu_View_Helper_BundleLink
     */
    public function setCacheDir($dir)
    {
        $this->_cacheDir = $dir;
        return $this;
    }

    /**
     * DocRoot is the base directory on disk where the relative css files can be found.
     *
     * e.g.
     *
     * if $docRoot == '/var/www/foo' then '/css/foo.css' will be found in '/var/www/foo/css/foo.css'
     *
     * @param string $docRoot
     * @return BundlePhu_View_Helper_BundleLink
     */
    public function setDocRoot($docRoot)
    {
        $this->_docRoot = $docRoot;
        return $this;
    }

    /**
     * Sets the URL prefix used for the generated link tag
     *
     * e.g. if $urlPrefix == '/stylesheets' then '/stylesheets/bundle_123fdfc3fe8ba8.css'
     * will be the src for the link tag.
     *
     * @param string $prefix
     * @return BundlePhu_View_Helper_BundleLink
     */
    public function setUrlPrefix($prefix)
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
     *
     * @param string $command Must contain :filename token
     * @return BundlePhu_View_Helper_BundleLink
     */
    public function setMinifyCommand($command)
    {
        $this->_minifyCommand = $command;
        return $this;
    }

    /**
     * Iterates over stylesheets, concatenating, optionally minifying,
     * optionally compressiong, and caching them.
     *
     * This detects updates to the source stylesheets using filemtime.
     * A file with an mtime more recent than the mtime of the cached bundle will
     * invalidate the cached bundle.
     *
     * Modifications of captured css cannot be detected by this.
     *
     * @param string $indent
     * @return void
     * @throws UnexpectedValueException if item has no src attribute or contains no captured source
     */
    public function toString($indent = null)
    {
        $fc = Zend_Controller_Front::getInstance();
        $module = $fc->getRequest()->getModuleName();
        $controller = $fc->getRequest()->getControllerName();
        $action = $fc->getRequest()->getActionName();
        $ret = '';
        $isCssBundled = false;
        foreach ($this->contents as $key => $headLink) {
            $fileKey = $key == 'screen, projection' ? 'screen' : $key;
        	$hash = sprintf('%s-%s-%s-%s', $module, $controller, $action, $fileKey);
            $cacheFile = "{$this->_docRoot}/{$this->_urlPrefix}/bundle-{$hash}.css";
            if (!Mandragora_File::exists($cacheFile)) {
                if (!$isCssBundled) {
                    $data = $this->_setCssData();
                    $isCssBundled =  true;
                }
                $this->_writeUncompressed($cacheFile, $this->contents[$key]);
            }
            $cacheTime = @filemtime($cacheFile);
            $urlPath = "{$this->_baseUrl}/{$this->_urlPrefix}/bundle-{$hash}.css?{$cacheTime}";
            if (strpos($key, 'IE') === false) {
                $ret .= PHP_EOL . '<link href="' . $urlPath . '" media="' . $key . '" rel="stylesheet" type="text/css" />';
            } else {
                $ret .= PHP_EOL . '<!--[if ' . $key .']><link rel="stylesheet" type="text/css" media="screen, projection" href="' . $urlPath . '" /><![endif]-->';
            }
        }
        return $ret;
    }

    /**
     * undocumented function
     *
     * @return void
     */
    protected function _setCssData()
    {
        foreach ($this as $item) {
            $href = $item->href;
            if ($this->_baseUrl && strpos($href, $this->_baseUrl) !== false) {
                $href =  substr($href, strlen($this->_baseUrl));
            }
            if ($item->conditionalStylesheet) {
                $this->contents[$item->conditionalStylesheet] .=
                    file_get_contents($this->_docRoot . $href) . PHP_EOL;
            } else {
                $this->contents[$item->media] .=
                    file_get_contents($this->_docRoot . $href) . PHP_EOL;
            }
        }
    }

    /**
     * Writes uncompressed bundle to disk
     *
     * @param string $cacheFile name of bundle file to write
     * @param string $data bundled data
     * @throws BadMethodCallException When neither _minifyCommand or _minifyCallback are defined
     * @return void
     */
    protected function _writeUncompressed($cacheFile, $data)
    {
        if (!empty($this->_minifyCommand)) {
            $parts = explode('/', $cacheFile);
            $filename = $parts[count($parts) - 1];
            $temp = Mandragora_File::create("{$this->_cacheDir}/$filename");
            $temp->write($data);
            $command = str_replace(
                ':filename', escapeshellarg($cacheFile), $this->_minifyCommand
            );
            $command = str_replace(
                ':sourceFile', escapeshellarg($temp->getFullName()), $command
            );
            $output = trim(`$command`);
            $temp->delete();
        } else {
            throw new BadMethodCallException("Neither _minifyCommand or _minifyCallback are defined.");
        }
    }

}