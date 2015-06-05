<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright  Mandrágora Web-Based Systems 2010-2015 (http://www.mandragora-web-systems.com)
 */

/**
 * Controller to display the propertie's images
 *
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 */
class Admin_ImageController extends Mandragora_Controller_Action_Abstract
{
    protected $validMethods = array(
        'render' => array('validations' => array('imageName' => 'key')),
    );

    /**
     * Disable layout and set content-type to image/jpeg
     *
     * @return void
     */
    public function init()
    {
        $this->_helper->getHelper('layout')->disableLayout();
        $this->getResponse()
             ->setHeader('Content-type', 'image/jpeg');
    }

    /**
     * Render the image
     */
    public function showAction()
    {
        $imageName = (string)$this->param($this->view->translate('imageName'));
        $handler = new App_Model_PictureFileHandler($imageName);
        $this->getResponse()
             ->setBody($handler->readGalleryImage())
             ->sendResponse();
        die();
    }

}