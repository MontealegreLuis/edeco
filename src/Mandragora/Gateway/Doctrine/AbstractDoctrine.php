<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Gateway\Doctrine;

use Mandragora\Gateway\GatewayInterface;
use Doctrine_Record;
use Mandragora\Model\AbstractModel;
use Mandragora\StringObject;

/**
 * Base class for Doctrine gateway objects
 */
abstract class AbstractDoctrine implements GatewayInterface
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
            $name = new StringObject(get_class($this));
            $daoName = $name->replace('Gateway', 'Dao') . 'Dao';
            if ($alias === null) {
                $name->setValue($daoName);
                $alias = $name->subString($name->indexOf('Dao\\') + 4, 1)->toLower();
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

    public function insert(AbstractModel $model)
    {
        $this->save($model);
    }

    public function update(AbstractModel $model)
    {
        $this->dao->assignIdentifier($model->getIdentifier());
        $this->save($model);
    }

    protected function save(AbstractModel $model)
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
     * @throws Doctrine_Connection_Exception
     */
    public function delete(AbstractModel $model)
    {
        $this->dao->assignIdentifier($model->getIdentifier());
        $this->dao->delete();
    }
}
