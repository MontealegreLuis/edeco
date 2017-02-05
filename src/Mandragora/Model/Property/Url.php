<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Model\Property;

use Mandragora\Filter\FriendlyUrl;

/**
 * Utility class for handling the displaying of model's URL properties
 */
class Url
implements PropertyInterface
{
    /**
     * @var FriendlyUrl
     */
    protected static $urlFilter;

    /**
     * @return string
     */
    protected $url;

    /**
     * @param string $url
     */
    public function __construct($url)
    {
        if (!self::$urlFilter) {
            self::$urlFilter = new FriendlyUrl();
        }
        $this->url = $this->filter($url);
    }

    /**
     * @see Mandragora_String_Interface::__toString()
     */
    public function __toString()
    {
        return $this->url;
    }

    /**
     * @see Mandragora_Model_Property_Interface::render()
     */
    public function render()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function filter($url)
    {
        return self::$urlFilter->filter($url);
    }
}
