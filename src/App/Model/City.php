<?php
/**
 * PHP version 7.1
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
 * @property integer $stateId
 */
class City extends AbstractModel
{
    /** @var array */
    protected $properties = [
        'id' => null, 'name' => null, 'url' => null, 'stateId' => null,
        'State' => null,
    ];

    /**
     * @var array
     */
    protected $identifier = ['id'];

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->properties['url'] = new Url($this->properties['name']);
    }

    /**
     * @param array $values
     */
    public function setState(array $values)
    {
        $state = new State($values);
        $this->properties['State'] = $state;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return implode(', ', array_filter([
            $this->properties['State'],
            $this->properties['name'],
            'México',
        ], function ($property) { return $property !== null; }));
    }
}
