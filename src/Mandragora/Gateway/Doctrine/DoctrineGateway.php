<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Gateway\Doctrine;

use Mandragora\Gateway\GatewayInterface;
use Doctrine_Query as Query;
use Doctrine_Record as Record;
use Mandragora\Model\AbstractModel;
use Mandragora\StringObject;

/**
 * Base class for Doctrine gateway objects
 */
abstract class DoctrineGateway implements GatewayInterface
{
    /** @var Record */
    protected $dao;

    /** @var string */
    protected $alias;

    /** @var boolean */
    protected $clearRelated = false;

    public function __construct(Record $dao)
    {
      $this->dao = $dao;
    }

    protected function createQuery(): Query
    {
        return $this->dao->getTable()->createQuery();
    }

    protected function alias(string $alias = null): string
    {
        if (!$this->alias) {
            $className = new StringObject(get_class($this));
            $daoName = $this->buildDaoName($className);
            if ($alias === null) {
                $alias = $this->buildAlias($className, $daoName);
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

    public function delete(AbstractModel $model)
    {
        $this->dao->assignIdentifier($model->getIdentifier());
        $this->dao->delete();
    }

    private function buildDaoName(StringObject $name): string
    {
        if ($name->endsWith('Gateway')) {
            return (string) $name->replace('Gateway', 'Dao');
        }
        return $name->replace('Gateway', 'Dao') . 'Dao';
    }

    private function buildAlias(StringObject $name, $daoName): string
    {
        $name->setValue($daoName);
        return (string) $name
            ->subString($name->indexOf('Dao\\') + 4, 1)
            ->toLower()
        ;
    }
}
