<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use App\Model\PictureFileHandler;
use Mandragora\Controller\Action\AbstractAction;

/**
 * Controller to display the property's images
 */
class Admin_ImageController extends AbstractAction
{
    protected $validMethods = [
        'render' => ['validations' => ['imageName' => 'key']],
    ];

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
        $imageName = (string) $this->param($this->view->translate('imageName'));
        $handler = new PictureFileHandler($imageName);
        $this->getResponse()
             ->setBody($handler->readGalleryImage())
             ->sendResponse();
        die();
    }
}
