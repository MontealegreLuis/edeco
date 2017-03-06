<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace App\Model;

use Mandragora\Model\AbstractModel;
use Mandragora\Model\Property\Url;

/**
 * Contains all the information related to the states
 *
 * @property integer $id
 * @property string $name
 * @property string $url
 */
class State extends AbstractModel
{
    /**
     * @var array
     */
    protected $properties = ['id' => null, 'name' => null, 'url' => null];

    /**
     * @var array
     */
    protected $identifier = ['id'];

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $url = new Url($this->properties['name']);
        $this->properties['url'] = $url;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->properties['name'];
    }
}
