<?php
/**
 * Base class to determine if a record exists or not in a given table filtering
 * by a given field
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
 * @subpackage Validate_Db_Doctrine
 * @author     LNJ <lemuel.nonoal@mandragora-web-systems.com>
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Base class to determine if a record exists or not in a given table filtering
 * by a given field
 *
 * @category   Library
 * @package    Edeco
 * @subpackage Validate
 * @author     LNJ <lemuel.nonoal@mandragora-web-systems.com>
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
abstract class Mandragora_Validate_Db_Doctrine_Abstract
    extends Zend_Validate_Abstract
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
    protected $_messageVariables = array(
        'field' => 'field',
    );

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
        $record = $table->$query($value);
        return $record;
    }

}