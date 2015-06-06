<?php
/**
 * Utility class for common string operations
 *
 * PHP version 5
 *
 * LICENSE: Redistribution and use of this file in source and binary forms,
 * with or without modification, is not permitted under any circumstance
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   Library
 * @package    Mandragora
 * @subpackage String
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Utility class for common string operations
 *
 * @category   Library
 * @package    Mandragora
 * @subpackage String
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class Mandragora_String
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
        return new Mandragora_String($string);
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
        return new Mandragora_String($string);
    }

    /**
     * @param integer $beginIndex
     * @param integer $length
     * @return Mandragora_String
     */
    public function subString($beginIndex, $length)
    {
        $string = substr($this->string, (int)$beginIndex, (int)$length);
        return new Mandragora_String($string);
    }

    /**
     * @return Mandragora_String
     */
    public function toLower()
    {
        $string = strtolower($this->string);
        return new Mandragora_String($string);
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
     * @return Mandragora_String
     */
    public function replace($search, $replace)
    {
        $replaced = str_replace($search, $replace, $this->string);
        return new Mandragora_String($replaced);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->string;
    }

}