<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora;

use Mandragora\String as MandragoraString;
use Zend_Filter_Word_CamelCaseToDash;

/**
 * Utility class for common string operations
 */
class StringObject
{
    /**
     * @var string
     */
    protected $string;

    /**
     * @var string
     */
    protected $camelCaseToDashFilter;

    /**
     * @param string $string
     */
    public function __construct($string)
    {
        $this->string = (string)$string;
    }

    /**
     * @param string $newValue
     * @return void
     */
    public function setValue($newValue)
    {
        $this->string = $newValue;
    }

    /**
     * @param string $string
     * @return boolean
     */
    public function endsWith($string)
    {
        $compare = substr_compare(
            $this->string, $string, -strlen($string), strlen($string)
        );
        return $compare === 0;
    }

    /**
     * @return Mandragora_String
     */
    public function lowerCaseFirst()
    {
        $string = $this->string;
        $string{0} = strtolower($this->string{0});
        return new MandragoraString($string);
    }

    /**
     * @return Mandragora_String
     */
    public function camelCaseToDash()
    {
        if (!$this->camelCaseToDashFilter) {
            $this->camelCaseToDashFilter =
                new Zend_Filter_Word_CamelCaseToDash();
        }
        $string = $this->camelCaseToDashFilter->filter($this->string);
        return new MandragoraString($string);
    }

    /**
     * @param integer $beginIndex
     * @param integer $length
     * @return StringObject
     */
    public function subString($beginIndex, $length)
    {
        $string = substr($this->string, (int)$beginIndex, (int)$length);
        return new StringObject($string);
    }

    /**
     * @return StringObject
     */
    public function toLower()
    {
        $string = strtolower($this->string);
        return new StringObject($string);
    }

    /**
     * @param string $substring
     * @return int | boolean
     */
    public function indexOf($substring)
    {
        return stripos($this->string, $substring);
    }

    /**
     * @param string $search
     * @param string $replace
     * @return StringObject
     */
    public function replace($search, $replace)
    {
        $replaced = str_replace($search, $replace, $this->string);
        return new StringObject($replaced);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->string;
    }
}
