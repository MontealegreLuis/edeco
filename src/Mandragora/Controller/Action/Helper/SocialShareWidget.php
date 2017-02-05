<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Controller\Action\Helper;

use Zend_Controller_Action_Helper_Abstract;
use Zend_Layout;

class SocialShareWidget extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * @var string
     */
    protected $url;

    /**
     * @var Zend_View
     */
    protected $view;

    /**
     * @var string
     */
    protected $template;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->view = Zend_Layout::getMvcInstance()->getView();
        $this->url = sprintf(
        	'%s://%s%s',
            $this->getRequest()->getScheme(),
            $this->getRequest()->getHttpHost(),
            $this->view->url()
        );
    }

    /**
     * @return string
     */
    public function share(array $networks)
    {
        $links = array();
        foreach ($networks as $networkName) {
            $method = sprintf('get%sLink', $networkName);
            $links[$networkName] = $this->$method();
        }
        $this->view->socialWidgetLinks = $links;
        return $this->view->render($this->template);
    }

    /**
     * @return string
     */
    protected function getTwitterLink()
    {
        return sprintf(
        	'http://twitter.com/home?status=%s',
            rawurlencode($this->url)
        );
    }

    /**
     * @return string
     */
    protected function getFacebookLink()
    {
        return sprintf(
        	'http://www.facebook.com/share.php?u=%s',
            rawurlencode($this->url)
        );
    }

    /**
     * @param string $template
     * @param array $networks = array('Facebook', 'Twitter')
     */
    public function direct($template, $networks = array('Facebook', 'Twitter'))
    {
        $this->template = (string)$template;
        return $this->share($networks);
    }
}
