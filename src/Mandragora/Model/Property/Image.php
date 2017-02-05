<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Model\Property;

use Mandragora\Filter\FriendlyUrl;
use Zend_Layout;

/**
 * Utility class for handling the displaying of model's image properties
 */
class Image implements PropertyInterface
{
    /**
     * @var FriendlyUrl
     */
    protected $urlFilter;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $extension;

    /**
     * @var string
     */
    protected $publicDirectory;

    /**
     * @param string $name
     * @param string $publicDirectory
     * @param string $extension = '.jpg'
     */
    public function __construct(
        $name, $publicDirectory, $extension = '.jpg'
    )
    {
        $this->name = $name;
        $this->extension = $extension;
        $this->publicDirectory = $publicDirectory;
        $this->urlFilter = new FriendlyUrl();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->urlFilter->filter($this->name) . $this->extension;
    }

    /**
     * Create <img /> element
     *
     * @return string
     */
    public function htmlify()
    {
        $view = Zend_Layout::getMvcInstance()->getView();
        $src = $this->publicDirectory . '/' . $this;
        return sprintf(
            '<img alt="%s" src="%s" />', $this->name, $view->baseUrl($src)
        );
    }

    public function render()
    {
        return $this->htmlify();
    }
}
