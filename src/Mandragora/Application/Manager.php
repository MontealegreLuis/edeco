<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Application;

/**
 * Handles application's configuration options
 */
class Manager
{
    /**
     * @var array
     */
    protected $options = array();

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * @param string $key
     * @return mixed
     * @throws Mandragora_Application_Exception
     */
    public function getSetting($key)
    {
        if (isset($this->options[(string)$key])) {
            return $this->options[(string)$key];
        } else {
            $message = "Application setting '$key' cannot be found";
            throw new Exception($message);
        }
    }
}
