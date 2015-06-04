<?php
/**
 * Handles Doctrine connections and it's configurations options
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
 * @subpackage Application_Doctrine
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Handles Doctrine connections and it's configurations options
 *
 * @category   Library
 * @package    Mandragora
 * @subpackage Application_Doctrine
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class Mandragora_Application_Doctrine_Manager
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var Doctrine_Connection
     */
    protected static $connection;

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * @return void
     */
    public function setup()
    {
        if (!$this->isConnectionOpen()) {
            $loader = Zend_Loader_Autoloader::getInstance();
            $loader->pushAutoloader(array('Doctrine_Core', 'autoload'))
                   ->pushAutoloader(array('Doctrine_Core', 'modelsAutoload'));
            $manager = Doctrine_Manager::getInstance();
            $manager->setAttribute(
                Doctrine_Core::ATTR_AUTO_ACCESSOR_OVERRIDE, true
            );
            $manager->setAttribute(
                Doctrine_Core::ATTR_MODEL_LOADING,
                $this->options['model_autoloading']
            );
            Doctrine_Core::loadModels($this->options['models_path']);
            self::$connection = Doctrine_Manager::connection(
                $this->options['dsn'], 'doctrine'
            );
            self::$connection->setAttribute(
                Doctrine_Core::ATTR_USE_NATIVE_ENUM, true
            );
            self::$connection->setCharset('UTF8');
        }
    }

    /**
     * @return boolean
     */
    public function isConnectionOpen()
    {
        return !is_null(self::$connection);
    }

    /**
     * @return array
     */
    public function getConfiguration()
    {
        return $this->options;
    }

}