<?php
/**
 * Factory for forms
 *
 * PHP version 5
 *
 * LICENSE: Redistribution and use of this file in source and binary forms,
 * with or without modification, is not permitted under any circumstance
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   Library
 * @package    Mandragora
 * @subpackage Form
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Factory for forms
 *
 * @category   Library
 * @package    Mandragora
 * @subpackage Form
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class Mandragora_Form
{
    /**
     * Flag to determine if cache should be disabled
     *
     * @var boolean
     */
    protected $disableCache = false;

    /**
     * Flag to determine if form should be created from a config file
     *
     * @var boolean
     */
    protected $fromConfig;

    /**
     * @var Zend_Cache_Core
     */
    protected static $cache;

    /**
     * @param boolean $disableCache
     * @param boolean $fromConfig
     */
    public function __construct($disableCache, $fromConfig)
    {
        $this->disableCache = $disableCache;
        $this->fromConfig = $fromConfig;
    }

    /**
     * @param Zend_Cache_Core $cache
     * @return void
     */
    public function setCache(Zend_Cache_Core $cache)
    {
        self::$cache = $cache;
    }

    /**
     * @param string $name
     * @param string $model
     * @return Mandragora_Form_Abstract
     */
    public function factory($name, $model)
    {
        if ($this->fromConfig) {
            return $this->fromConfig($name, $model);
        } else {
            return $this->fromClass($name, $model);
        }
    }

    /**
     * @param string $name
     * @param string $model
     * @return Mandragora_Form_Abstract
     */
    protected function fromClass($name, $model)
    {
        $className = $this->getClassName($name, $model);
        return new $className();
    }

    /**
     * @param string $name
     * @param string $model
     * @return Mandragora_Form_Abstract
     */
    protected function fromConfig($name, $model)
    {
        return $this->createFormFromConfig($name, $model);
    }

    /**
     * @param string $name
     * @param string $model
     */
    protected function createFormFromConfig($name, $model)
    {
        $className = $this->getClassName($name, $model);
        $filter = new Zend_Filter_Word_CamelCaseToDash();
        $folder = strtolower($filter->filter($model));
        $file = strtolower($filter->filter($name));
        if ($this->disableCache) {
            $form = $this->_loadFormFromIniFile($folder, $file, $className);
        } else  {
            $form = $this->getCache()->load($className);
            if (!$form) {
                $form = $this->_loadFormFromIniFile($folder, $file, $className);
                $this->getCache()->save($form, $className);
            }
        }
        return $form;
    }

    /**
     * @return Zend_Cache_Core
     */
    protected function getCache()
    {
        return self::$cache;
    }

    /**
     * @param string $formName
     * @param string $model
     * @return string
     */
    protected function getClassName($formName, $model)
    {
        $className = sprintf('App_Form_%s_%s', $model, $formName);
        return $className;
    }

    /**
     * @param string $folder
     * @param string $fileName
     * @param string $className
     * @return Mandragora_Form_Abstract
     */
    private function _loadFormFromIniFile($folder, $fileName, $className)
    {
        $pathToIniFile = APPLICATION_PATH . '/configs/forms/%s/%s.ini';
        $configPath = sprintf($pathToIniFile, $folder, $fileName);
        $config = new Zend_Config_Ini($configPath, sprintf('%s', $folder));
        return new $className($config);
    }

}