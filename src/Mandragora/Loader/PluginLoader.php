<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Loader;

use Zend_Loader_PluginLoader as DefaultLoader;
use Zend_Loader_PluginLoader_Exception as PluginLoaderException;

class PluginLoader extends DefaultLoader
{
    public function addPrefixPath($prefix, $path)
    {
        if (!is_string($prefix) || !is_string($path)) {
            throw new PluginLoaderException(
                'PluginLoaderException::addPrefixPath() method only takes strings for prefix and path.'
            );
        }

        $prefix = $this->_formatPrefix($prefix);
        $path   = rtrim($path, '/\\') . '/';

        // Look for namespaces, replace '\' with the appropriate directory separator
        if (strpos($path, '\\') !== false) {
            $path = str_replace('\\', DIRECTORY_SEPARATOR, $path);
        }

        if ($this->_useStaticRegistry) {
            self::$_staticPrefixToPaths[$this->_useStaticRegistry][$prefix][] = $path;
        } else {
            if (!isset($this->_prefixToPaths[$prefix])) {
                $this->_prefixToPaths[$prefix] = array();
            }
            if (!in_array($path, $this->_prefixToPaths[$prefix])) {
                $this->_prefixToPaths[$prefix][] = $path;
            }
        }
        return $this;
    }
}
