<?php
/**
 * Base class for Doctrine gateway objects
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
 * @subpackage Gateway_Doctrine
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Base class for Doctrine gateway objects
 *
 * @category   Library
 * @package    Mandragora
 * @subpackage Gateway_Doctrine
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
abstract class Mandragora_Gateway_Doctrine_Abstract
implements     Mandragora_Gateway_Interface
{
    /**
     * @var Doctrine_Record
     */
    protected $dao;

    /**
     * @var string
     */
    protected $alias;

    /**
     * @var boolean
     */
    protected $clearRelated = false;

    /**
     * @param Doctrine_Record $dao
     */
    public function __construct(Doctrine_Record $dao)
    {
      $this->dao = $dao;
    }

    /**
     * @param string $alias = null
     * @return string
     */
    protected function alias($alias = null)
    {
        if (!$this->alias) {
            $name = new Mandragora_String(get_class($this));
            $daoName = $name->replace('Gateway', 'Dao');
            if ($alias === null) {
                $name->setValue($daoName);
                $alias = $name->subString($name->indexOf('Dao_') + 4, 1)
                              ->toLower();
            }
            $this->alias = $daoName . ' ' . $alias;
        }
        return $this->alias;
    }

    /**
     * @return void
     */
    public function clearRelated()
    {
        $this->clearRelated = true;
    }

    /**
     * @param Mandragora_Model_Abstract $model
     */
    public function insert(Mandragora_Model_Abstract $model)
    {
        $this->save($model);
    }

    /**
     * @param Mandragora_Model_Abstract $model
     */
    public function update(Mandragora_Model_Abstract $model)
    {
        $this->dao->assignIdentifier($model->getIdentifier());
        $this->save($model);
    }

    /**
     * @param Mandragora_Model_Abstract $model
     */
    protected function save(Mandragora_Model_Abstract $model)
    {
        $model->version += 1;
        $this->dao->fromArray($model->toArray(true));
        if ($this->clearRelated) {
            $this->dao->clearRelated();
        }
        $this->dao->save();
        $model->fromArray($this->dao->toArray());
    }

    /**
     * @param Mandragora_Model_Abstract $model
     * @throws Doctrine_Connection_Exception
     */
    public function delete(Mandragora_Model_Abstract $model)
    {
        $this->dao->assignIdentifier($model->getIdentifier());
        $this->dao->delete();
    }

}