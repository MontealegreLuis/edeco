<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use Mandragora\Service\Crud\Doctrine\DoctrineCrud;
use App\Model\Collection\Project as AppModelCollectionProject;
use Mandragora\Gateway\NoResultsFoundException;
use App\Model\Project as AppModelProject;

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
     * @return Edeco_Model_Collection_Project
     */
    public function retrieveAllInvestmentProjects($pageNumber)
    {
    	$this->init();
        $query = $this->getGateway()->getQueryFindAll();
        $this->setPaginatorQuery($query);
        $items = (array)$this->getPaginator($pageNumber)->getCurrentItems();
        return new AppModelCollectionProject($items);
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
     * @return Mandragora_File
     */
    public function getAttachmentFileHandler($fileName)
    {
        $this->getModel()->attachment = $fileName;
        $this->getModel()->initAttachmentFileHandler();
        return $this->getModel()->getAttachmentFileHandler($fileName);
    }

    /**
     * @return Edeco_Model_Project
     * @throws Mandragora_Doctrine_Gateway_NoResultsFoundException
     */
    public function retrieveProjectById($id)
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
        $project = new AppModelProject($projectInformation);
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

    /**
     * @param int $id
     */
    public function deleteProject($id)
    {
    	$this->init();
        $projectInformation = $this->getGateway()->findOneById((int)$id);
        $this->getModel()->fromArray($projectInformation);
        $this->getModel()->initAttachmentFileHandler();
        $this->getModel()->deleteAttachmentFile();
        $this->getGateway()->delete($this->getModel());
    }

    /**
     * @param string $action
     * @return Mandragora_Zend_Form_Abstract
     */
    public function getFormForCreating($action)
    {
        $this->createForm('Detail');
        $this->getForm()->setAction($action);
        $this->getForm()->prepareForCreating();
        return $this->getForm();
    }

    /**
     * Get the form customized for updating a project
     *
     * @param string $action
     * @return Mandragora_Form_Abstract
     */
    public function getFormForEditing($action)
    {
        $this->createForm('Detail');
        $this->getForm()->setAction($action);
        $this->getForm()->prepareForEditing();
        return $this->getForm();
    }

    /**
     * @param Zend_Navigation $container
     * @return void
     */
    protected function createForm($formName)
    {
        $this->getForm($formName, true, true);
    }
}
