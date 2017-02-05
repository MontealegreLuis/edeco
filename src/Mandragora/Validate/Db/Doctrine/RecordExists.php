<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Validate\Db\Doctrine;

/**
 * Determine if a record exists in a given table filtering by a given field
 */
class RecordExists extends DoctrineQueryValidation
{
    /**
     * @param string
     * @return boolean
     */
    public function isValid($value)
    {
        $this->_setValue($value);
        $valid = true;
        $record = $this->query($value);
        $recordFound = $record !== false;
        if (!$recordFound) {
            $valid = false;
            $this->_error(self::ERROR_NO_RECORD_FOUND);
        }
        return $valid;
    }
}
