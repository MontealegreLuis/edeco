<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Model\Property;

/**
 * Utility class for handling model's password properties
 */
class Password implements PropertyInterface
{
    /**
     * @var string
     */
    protected $password;

    /**
     * @param string $password
     */
    public function __construct($password)
    {
        if (strlen($password) != 40) {
            $this->password = sha1($password);
        } else {
            $this->password = $password;
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function render()
    {
        return '*****'; //Do not show passwords in the view layer;
    }
}
