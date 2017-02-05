<?php
/**
 * Abstract class for service objects
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
 * @subpackage Service
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

namespace Mandragora\Service;

use Mandragora\Model;
use Mandragora\Model\AbstractModel;
use Mandragora\Form;
use Zend_Cache_Manager;

/**
 * Abstract class for service objects
 *
 * @category   Library
 * @package    Mandragora
 * @subpackage Service
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
abstract class AbstractService
{
    /** @var string */
    protected $modelName;

    /** @var AbstractModel */
    private $model;

    /**
     * @var Mandragora_Form_Abstract
     */
    private $form;

    /**
     * @var Zend_Cache_Manager
     */
    protected $cacheManager;

    /**
     * @param string $modelName
     */
    public function __construct($modelName)
    {
        $this->modelName = (string) $modelName;
    }

    /**
     * Method to be overriden by developer to customize the service object
     *
     * @return void
     */
    protected function init() {}

    /**
     * @param array $values = null
     * @return AbstractModel
     */
    public function getModel(array $values = null)
    {
        if (!$this->model) {
            $this->model = Model::factory($this->modelName, $values);
        }
        return $this->model;
    }

    /**
     * @param AbstractModel $model
     * @return void
     */
    public function setModel(AbstractModel $model)
    {
        $this->model = $model;
    }

    /**
     * @param string $formName = 'Detail'
     * @param boolean $fromConfig = true
     * @param boolean $disableCache = false
     * @return Mandragora_Form_Abstract
     */
    public function getForm(
        $formName = 'Detail', $fromConfig = true, $disableCache = false
    )
    {
        if (!$this->form) {
            $form = new Form($disableCache, $fromConfig);
            if (!$disableCache) {
                 $form->setCache($this->getCache('form'));
            }
            $this->form = $form->factory($formName, $this->modelName);
        }
        return $this->form;
    }

    /**
     * @param Zend_Cache_Manager $cacheManager
     * @return void
     */
    public function setCacheManager(Zend_Cache_Manager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    /**
     * @param string $cacheName
     * @return Zend_Cache_Core
     */
    protected function getCache($cacheName)
    {
        return $this->cacheManager->getCache($cacheName);
    }

}