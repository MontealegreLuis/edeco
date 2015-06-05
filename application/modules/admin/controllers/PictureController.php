<?php
/**
 * Application's Picture controller
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
 * @category   Application
 * @package    Edeco
 * @subpackage Controller
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Picture Controller for Edeco's Panel
 *
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @version    SVN: $Id$
 * @copyright  Mandrágora Web-Based Systems 2010
 * @category   Application
 * @package    Edeco
 * @subpackage Controller
 * @history    24 may 2010
 *             LMV
 *             - Class Creation
 */
class Admin_PictureController extends Mandragora_Controller_Action_Abstract
{
    /**
     * @var array
     */
    protected $validMethods = array(
        'save' => array('method' => 'post'),
        'update' => array('method' => 'post'),
    );

    /**
     * Initialize the service object
     *
     * @return void
     */
    public function init()
    {
        $this->service = Mandragora_Service::factory('Picture');
        $this->service->setCacheManager($this->getCacheManager());
        $doctrine = $this->getInvokeArg('bootstrap')->getResource('doctrine');
        $this->service->setDoctrineManager($doctrine);
        $actions = $this->_helper->actionsBuilder($this->getRequest());
        $this->view->actions = $actions;
    }

    /**
     * Show the list with all the available properties' pictures
     *
     * @return void
     */
    public function listAction()
    {
        $this->service->setPaginatorOptions($this->getAppSetting('paginator'));
        $page = (int)$this->param($this->view->translate('page'), 1);
        $propertyId = (int)$this->param($this->view->translate('propertyId'));
        $properties =$this->service
                          ->retrieveAllPicturesByPropertyId($propertyId, $page);
        $this->view->pictures = $properties;
        $this->view->paginator = $this->service->getPaginator($page);
        $this->view->propertyId = $propertyId;
    }

    /**
     * Show the form to create a new picture for the current property
     *
     * @return void
     */
    public function createAction()
    {
        $action = $this->view->url(array('action' => 'save'), 'controllers');
        $pictureForm = $this->service->getFormForCreating($action);
        $propertyId = (int)$this->param($this->view->translate('propertyId'));
        $pictureForm->setPropertyId($propertyId);
        $this->view->pictureForm = $pictureForm;
        $this->view->propertyId = $propertyId;
    }

    /**
     * Upload the image's file and save it's associated info to the data source
     *
     * @return void
     */
    public function saveAction()
    {
        $action = $this->view->url(array('action' => 'save'), 'controllers');
        $pictureForm = $this->service->getFormForCreating($action);
        $this->service->openConnection();
        if ($pictureForm->isValid($this->post())) {
            $this->service->createPicture();
            $this->flash('success')->addMessage('picture.created');
            $propertyId = (int)$this->service->getModel()->propertyId;
            $params = array(
                $this->view->translate('propertyId') => $propertyId,
                'id' => (int)$this->service->getModel()->id
            );
            $this->redirectToRoute('show', $params);
        } else {
            $this->view->pictureForm = $pictureForm;
            $this->view->propertyId = (int)$this->post('propertyId');
            $this->renderScript('picture/create.phtml');
        }
    }

    /**
     * Show the information of the picture, to let the user edit it
     *
     * @return void
     */
    public function editAction()
    {
        $id = (int)$this->param('id');
        $propertyId = (int)$this->param($this->view->translate('propertyId'));
        $picture = $this->service
                        ->retrievePictureByIdAndPropertyId($id, $propertyId);
        if (!$picture) {
            $this->flash('error')->addMessage('picture.not.found');
            $params = array(
                $this->translate('page') => 1,
                $this->translate('propertyId') => $propertyId
            );
            $this->redirectToRoute('list', $params);
        } else {
            $action = $this->view->url(array('action' => 'update'));
            $pictureForm = $this->service->getFormForEditing($action);
            $pictureForm->populate($picture->toArray());
            $params = array(
                'controller' => 'image', 'action' => 'show',
                $this->view->translate('imageName') => $picture->filename
            );
            $filename = $this->view->url($params, 'controllers', true);
            $pictureForm->setSrcImage($filename);
            $this->view->picture = $picture;
            $this->view->propertyId = $propertyId;
            $this->view->pictureForm = $pictureForm;
        }
    }

    /**
     * Update a picture in the database
     *
     *
     * @return void
     */
    public function updateAction()
    {
        $action = $this->view->url(array('action' => 'update'), 'controllers');
        $pictureForm = $this->service->getFormForEditing($action);
        $pictureValues = $this->post();
        $propertyId = (int)$this->post('propertyId');
        if ($pictureForm->isValid($pictureValues)) {
            $id = (int)$this->param('id');
            $picture = $this->service
                            ->retrievePictureByIdAndPropertyId($id, $propertyId);
            if (!$picture) {
                $this->flash('error')->addMessage('property.not.found');
                $this->redirectToRoute('list', array('page' => 1));
            } else {
                if ($picture->version > $pictureValues['version']) {
                    $this->flash('error')->addMessage(
                        'picture.optimistic.locking.failure'
                    );
                    $pictureForm->populate($picture->toArray());
                    $this->view->pictureForm = $pictureForm;
                    $this->renderScript('picture/edit.phtml');
                } else {
                    $this->service->updatePicture();
                    $this->flash('success')->addMessage('picture.updated');
                    $this->redirectToRoute(
                        'show',
                        array(
                            $this->view->translate('propertyId') => $propertyId,
                            'id' => $picture->id,
                        )
                    );
                }
            }
        } else {
            $values = $pictureForm->getValues();
            $this->view->propertyId = $propertyId;
            $this->view->pictureForm = $pictureForm;
            $this->renderScript('picture/edit.phtml');
        }
    }

    /**
     * Show the property's picture information
     */
    public function showAction()
    {
        $id = (int)$this->param('id');
        $propertyId = (int)$this->param($this->view->translate('propertyId'));
        $picture = $this->service
                        ->retrievePictureByIdAndPropertyId($id, $propertyId);
        if (!$picture) {
            $this->flash('error')->addMessage('picture.not.found');
            $this->redirectToRoute(
                'list',
                array($this->view->translate('page') => 1)
            );
        } else {
            $this->view->picture = $picture;
            $this->view->propertyId = $propertyId;
        }
    }

    /**
     * Deletes a picture from both the database and the file system
     *
     * @return void
     */
    public function deleteAction()
    {
        $id = (int)$this->param('id');
        $propertyId = (int)$this->param($this->view->translate('propertyId'));
        $picture = $this->service
                        ->retrievePictureByIdAndPropertyId($id, $propertyId);
        if (!$picture) {
            $this->flash('error')->addMessage('picture.not.found');
            $this->redirectToRoute(
                'list', array($this->view->translate('page') => 1)
            );
        } else {
            $this->service->deletePicture($id, $propertyId);
            $this->flash('success')->addMessage('picture.deleted');
            $this->redirectToRoute(
                'list',
                array(
                    $this->view->translate('propertyId') => $propertyId,
                    $this->view->translate('page') => 1
                )
            );
        }
    }

}