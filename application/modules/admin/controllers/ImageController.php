<?php
/**
 * Controller to display the propertie's images
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
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @version    SVN $Id$
 * @copyright  Mandrágora Web-Based Systems 2010
 * @category   Application
 * @package    Edeco
 * @subpackage Controllers
 */

/**
 * Controller to display the propertie's images
 *
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @version    SVN $Id$
 * @copyright  Mandrágora Web-Based Systems 2010
 * @category   Application
 * @package    Edeco
 * @subpackage Controllers
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