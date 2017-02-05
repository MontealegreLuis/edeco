<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\View\Helper;

use Zend_Date;

/**
 * View helper for current copyright year
 */
class CopyrightYear
{
    /**
     * @return string
     */
    public function copyrightYear()
    {
        return (new Zend_Date())->toString('yyyy');
    }
}
