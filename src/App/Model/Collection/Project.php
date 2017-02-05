<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Collection;

use Mandragora\Collection\AbstractCollection;
use Mandragora\Model;

/**
 * Collection class for Project model
 *
 * @category   Application
 * @package    Edeco
 * @subpackage Collection
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class Project extends AbstractCollection
{
    /**
     * @return Edeco_Model_Project
     */
    protected function createModel(array $data)
    {
        return Model::factory('Project', $data);
    }
}
