<?php
/**
 * Application's Project controller
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
 * Application's Project controller
 *
 * @category   Application
 * @package    Edeco
 * @subpackage Controller
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class Admin_ProjectController extends Mandragora_Controller_Action_Abstract
{
    /**
     * @var array
     */
    protected $validMethods = array(
        'save' => array('method' => 'post'),
        'update' =>array('method' => 'post'),
    );

    /**
     * Initialize the service object and build the breadcrumbs
     *
     * @return void
     */
    public function init()
    {
        $this->service = Mandragora_Service::factory('Project');
        $this->service->setCacheManager($this->getCacheManager());
        $doctrine = $this->getInvokeArg('bootstrap')->getResource('doctrine');
        $this->service->setDoctrineManager($doctrine);
        $actions = $this->_helper->actionsBuilder($this->getRequest());
        $this->view->actions = $actions;
    }

    /**
     * Redirect to list action
     */
    public function indexAction()
    {
        $this->redirect('list',  array($this->view->translate('page') => 1));
    }

    /**
     * Show all the available projects
     *
     * @return void
     */
    public function listAction()
    {
        $this->service->setPaginatorOptions($this->getAppSetting('paginator'));
        $page = (int)$this->param($this->view->translate('page'), 1);
        $projects =$this->service->retrieveAllInvestmentProjects($page);
        $this->view->projects = $projects;
        $this->view->paginator = $this->service->getPaginator($page);
        $this->view->role = Zend_Auth::getInstance()->getIdentity()->roleName;
    }

    /**
     * Show the form for creating a new project
     *
     * @return void
     */
    public function createAction()
    {
        $action = $this->view->url(array('action' => 'save'), 'controllers');
        $this->view->projectForm = $this->service->getFormForCreating($action);
    }

    /**
     * Save the project information to the data source or show the
     * corresponding error messages if needed
     *
     *  @retun void
     */
    public function saveAction()
    {
        $action = $this->view->url(array('action' => 'save'), 'controllers');
        $projectForm = $this->service->getFormForCreating($action);
        if ($projectForm->isValid($this->post())) {
            $this->service->createProject();
            $this->flash()->addMessage(array('success' => 'project.created'));
            $this->redirect(
                'show',  array('id' => $this->service->getModel()->id)
            );
        } else {
            $this->view->projectForm = $projectForm;
            $this->renderScript('project/create.phtml');
        }
    }

    /**
     * Show the project information
     *
     * @return void
     */
    public function showAction()
    {
        $id = (int)$this->param('id');
        $project = $this->service->retrieveProjectById($id);
        if (!$project) {
            $this->flash('error')->addMessage('project.not.found');
            $this->redirect('list', array('page' => 1));
        } else {
            $this->view->project = $project;
            $this->view->role = Zend_Auth::getInstance()->getIdentity()
                                                        ->roleName;
        }
    }

    /**
     * Show the form for editing the current project
     *
     * @return void
     */
    public function editAction()
    {
        $id = (int)$this->param('id');
        $project = $this->service->retrieveProjectById($id);
        if (!$project) {
            $this->flash('error')->addMessage('project.not.found');
            $this->redirect('list', array('page' => 1));
        } else {
            $action = $this->view->url(array('action' => 'update'));
            $projectForm = $this->service->getFormForEditing($action);
            $projectForm->populate($project->toArray());
            $this->view->project = $project;
            $this->view->projectForm = $projectForm;
        }
    }

    /**
     * Update a project in the database
     *
     * If the request is a get request it fills the form with the project
     * information. If the request is a post request it tries to save the data
     * in the database, if it does not contain errors. If the project is
     * succesfully saved it performs the index action (retrieves the list of
     * all the active projects)
     *
     * @return void
     */
    public function updateAction()
    {
        $action = $this->view->url(array('action' => 'update'), 'controllers');
        $projectForm = $this->service->getFormForEditing($action);
        $projectValues = $this->post();
        if ($projectForm->isValid($projectValues)) {
            $id = (int)$this->post('id');
            $project = $this->service->retrieveProjectById($id);
            if (!$project) {
                $this->flash('error')->addMessage('project.not.found');
                $this->redirect('list', array('page' => 1));
            } else {
                if ($project->version > $projectValues['version']) {
                    $this->flash('error')->addMessage(
                        'project.optimistic.locking.failure'
                    );
                    $projectForm->populate($project->toArray());
                    $this->view->projectForm = $projectForm;
                    $this->renderScript('project/edit.phtml');
                } else {
                    $this->service->updateProject();
                    $this->flash('success')->addMessage('project.updated');
                    $this->redirect('show', array('id' => $project->id));
                }
            }
        } else {
            $values = $projectForm->getValues();
            $this->view->project = new App_Model_Project($values);
            $this->view->projectForm = $projectForm;
            $this->renderScript('project/edit.phtml');
        }
    }

    /**
     * Changes the value of the field active to zero
     *
     * @return void
     */
    public function deleteAction()
    {
        $this->service->deleteProject((int)$this->param('id'));
        $this->flash('success')->addMessage('project.deleted');
        $params = array($this->view->translate('page') => 1);
        $this->redirect('list', $params);
    }

    /**
     * Download PowerPoint file to a local drive
     */
    public function downloadAction()
    {
        if ($this->getRequest()->isGet()) {
            $fileName = $this->param($this->view->translate('fileName'));
            $file = $this->service->getAttachmentFileHandler($fileName);
            $name = $file->getNameAndExtension();
            $this->getResponse()
                 ->setHeader('Content-type', 'application/octet-stream')
                 ->setHeader('Cache-Control', 'public', true)
                 ->setHeader('Pragma', '', true)
                 ->setHeader('Content-Disposition',
                    "attachment; filename=\"$name\"\n"
                 )
                 ->setBody($file->read())
                 ->sendResponse();
            die();
        }
    }

}