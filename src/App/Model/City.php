<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model;

use Mandragora\Model\AbstractModel;
use Mandragora\Model\Property\Url;
use Mandragora\Model;

/**
 * Contains all the information related to the states
 *
 * @property integer $id
 * @property string $name
 * @property integer $stateId
 */
class City extends AbstractModel
{
    /**
     * @var array
     */
    protected $properties = array(
        'id' => null, 'name' => null, 'url' => null, 'stateId' => null,
        'State' => null,
    );

    /**
     * @var array
     */
    protected $identifier = array('id');

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $url = new Url($this->properties['name']);
        $this->properties['url'] = $url;
    }

    /**
     * @param array $values
     */
    public function setState(array $values)
    {
        $state = Model::factory('State', $values);
        $this->properties['State'] = $state;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->properties['State'] . ', ' . $this->properties['name']
            . ', México';
    }
}
