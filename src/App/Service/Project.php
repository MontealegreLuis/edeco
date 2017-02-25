<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use Mandragora\Model\AbstractModel;
use Mandragora\Service\Crud\Doctrine\DoctrineCrud;
use App\Model\Collection\Project as ProjectCollection;
use Mandragora\Gateway\NoResultsFoundException;
use App\Model\Project as ProjectModel;

/**
 * Service class for Project model
 */
class Project extends DoctrineCrud
{
    /**
     * @return void
     */
    public function init()
    {
        $this->openConnection();
        $this->decorateGateway();
    }

    /**
     * @param int $pageNumber
     * @return \App\Model\Collection\Project
     */
    public function retrieveAllInvestmentProjects(int $pageNumber)
    {
    	$this->init();
        $this->setPaginatorQuery($this->getGateway()->getQueryFindAll());
        $items = (array) $this->getPaginator($pageNumber)->getCurrentItems();
        return new ProjectCollection($items);
    }

    /**
     * @return void
     */
    public function createProject()
    {
    	$this->init();
        $this->saveAttachmentInForm();
        $this->getGateway()->insert($this->getModel());
    }

    /**
     * @param string $fileName
     * @return \Mandragora\File
     */
    public function getAttachmentFileHandler(string $fileName)
    {
        $this->getModel()->attachment = $fileName;
        $this->getModel()->initAttachmentFileHandler();
        return $this->getModel()->getAttachmentFileHandler($fileName);
    }

    /**
     * @return \App\Model\Project|false
     * @throws NoResultsFoundException
     */
    public function retrieveProjectById(int $id)
    {
    	$this->init();
        try {
            $this->getModel($this->getGateway()->findOneById((int)$id));
            return $this->getModel();
        } catch (NoResultsFoundException $nrfe) {
            return false;
        }
    }

    /**
     * @return void
     */
    public function updateProject()
    {
    	$this->init();
        $projectInformation = $this->getGateway()->findOneById(
            (int)$this->getForm()->getValue('id')
        );
        $project = new ProjectModel($projectInformation);
        $this->getModel()->id = (int)$project->id;
        if ($project->name != $this->getForm()->getValue('name')) {
            if ($this->getForm()->getValue('attachment') != null) {
                $project->initAttachmentFileHandler();
                $project->deleteAttachmentFile();
                $this->saveAttachmentInForm();
            } else {
                $this->getModel()->name = $this->getForm()->getValue('name');
                $extension = $this->getModel()->getFileExtension(
                    $project->attachment
                );
                $this->getModel()->createAttachmentFileHandler(
                    $this->getModel()->name, $extension
                );
                $project->initAttachmentFileHandler();
                $project->renameAttachmentFile($this->getModel()->attachment);
            }
        } else {
            if ($this->getForm()->getValue('attachment') != null) {
                $project->initAttachmentFileHandler();
                $project->deleteAttachmentFile();
                $this->saveAttachmentInForm();
            } else {
                return; //Nothing changed
            }
        }
        $this->getGateway()->update($this->getModel());
    }

    /**
     * @return void
     */
    protected function saveAttachmentInForm()
    {
        $this->getModel()->name = $this->getForm()->getValue('name');
        $extension = $this->getModel()->getFileExtension(
            $this->getForm()->getAttachmentOriginalFileName()
        );
        $this->getModel()->createAttachmentFileHandler(
            $this->getForm()->getValue('name'), $extension
        );
        $fileName = $this->getModel()->getAttachmentFullName();
        $this->getForm()->saveAttachmentFile($fileName);
    }

    public function deleteProject(int $id)
    {
    	$this->init();
        $projectInformation = $this->getGateway()->findOneById($id);
        $this->getModel()->fromArray($projectInformation);
        $this->getModel()->initAttachmentFileHandler();
        $this->getModel()->deleteAttachmentFile();
        $this->getGateway()->delete($this->getModel());
    }

    /**
     * @param string $action
     * @return \App\Form\Project\Detail
     */
    public function getFormForCreating($action)
    {
        $this->createForm();
        $this->getForm()->setAction($action);
        $this->getForm()->prepareForCreating();
        return $this->getForm();
    }

    /**
     * Get the form customized for updating a project
     *
     * @param string $action
     * @return \App\Form\Project\Detail
     */
    public function getFormForEditing($action)
    {
        $this->createForm();
        $this->getForm()->setAction($action);
        $this->getForm()->prepareForEditing();
        return $this->getForm();
    }

    /**
     * @return \App\Form\Project\Detail
     */
    protected function createForm()
    {
        return $this->getForm('Detail', true);
    }

    public function getModel(array $values = null): AbstractModel
    {
        if (!$this->model) {
            $this->model = new ProjectModel($values);
        }

        return $this->model;
    }
}
