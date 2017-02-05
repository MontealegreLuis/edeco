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
 * Category model
 *
 * @property integer $id
 * @property string $name
 * @property integer $version
 */
class Category
extends AbstractModel
{
    /**
     * @var array
     */
    protected $properties = array('name' => null, 'url' => null);

    /**
     * @var array
     */
    protected $identifier = array('id');

    /**
     * @param string $url
     * @return void
     */
    public function setUrl($url)
    {
        $url = new Url($this->properties['name']);
        $this->properties['url'] = $url;
    }

    /**
     * @see Mandragora_String_Interface::__toString()
     */
    public function __toString()
    {
        return $this->properties['name'];
    }

}