<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Model\Property;

class Telephone implements PropertyInterface
{
    protected $number;
    protected $pattern;
    protected $replacement;

    public function __construct(
        $number,
        $pattern = '/(\d{3})(\d{1})(\d{2})(\d{2})(\d{2})/i',
        $replacement = '($1) $2-$3-$4-$5'
    )
    {
        $this->number = $number;
        $this->pattern = $pattern;
        $this->replacement = $replacement;
    }

    public function render()
    {
        if (trim($this->number) != '') {
            return preg_replace(
                $this->pattern, $this->replacement, $this->number
            );
        }
        return '';
    }

    public function __toString()
    {
        return $this->number;
    }
}
