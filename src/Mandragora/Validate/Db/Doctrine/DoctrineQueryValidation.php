<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Validate\Db\Doctrine;

use Zend_Validate_Abstract;
use Zend_Validate_Exception;
use Doctrine_Core;

/**
 * Base class to determine if a record exists or not in a given table filtering
 * by a given field
 */
abstract class DoctrineQueryValidation extends Zend_Validate_Abstract
{
    /**
     * @var string
     */
    protected $table;

    /**
     * @var string
     */
    protected $field;

    /**
     * Error thrown when the record already exists in the table
     *
     * @var string
     */
    const ERROR_RECORD_FOUND = 'errorRecordFound';

    /**
     * Error thrown when the record does not exist in the table
     *
     * @var string
     */
    const ERROR_NO_RECORD_FOUND = 'errorNoRecordFound';

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::ERROR_RECORD_FOUND =>
            "The field '%field%' already has a value '%value%'",
        self::ERROR_NO_RECORD_FOUND =>
            "The field '%field%' does not have a value '%value%'"
    );

    /**
     * Additional variables available for validation failure messages
     *
     * @var array
     */
    protected $_messageVariables = [
        'field' => 'field',
    ];

    /**
     * @param array $options
     *      - table: The Doctrine table name
     *      - field: The field name which will filter the query
     */
    public function __construct(array $options)
    {
        if (!array_key_exists('table', $options)) {
            throw new Zend_Validate_Exception('Table option missing!');
        }
        if (!array_key_exists('field', $options)) {
            throw new Zend_Validate_Exception('Field option missing!');
        }
        $this->table = (string)$options['table'];
        $this->setField($options['field']);
    }

    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param string $field
     */
    public function setField($field)
    {
        $this->field = (string)$field;
    }

    /**
     * @param string $value
     * @return Doctrine_Record | boolean
     */
    protected function query($value)
    {
        $table = Doctrine_Core::getTable($this->table);
        $query = 'findOneBy' . ucfirst($this->field);
        return $table->$query($value);
    }
}
