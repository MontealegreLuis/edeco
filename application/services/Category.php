<?php
/**
 * Service class for Category model
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
 * @package    App
 * @subpackage Service
 * @author     MMS <meri.michimani@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2011
 * @version    SVN: $Id$
 */

/**
 * Service class for Category model
 *
 * @package    App
 * @subpackage Service
 * @author     MMS <meri.michimani@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2011
 * @version    SVN: $Id$
 */
class   App_Service_Category
extends Mandragora_Service_Crud_Doctrine_Abstract
{
    /**
     * @return void
     */
    protected function init()
    {
        $this->openConnection();
        $this->decorateGateway();
    }

    /**
     * @param int $pageNumber
     * @return App_Model_Collection_Category
     */
    public function retrieveCategoryCollection($pageNumber)
    {
        $this->init();
        $this->query = $this->getGateway()->getQueryFindAll();
        $items = (array)$this->getPaginator($pageNumber)->getCurrentItems();
        return new App_Model_Collection_Category($items);
    }

    /**
     * @return array
     */
    public function retrieveAllCategories()
    {
        $this->init();
        $this->query = $this->getGateway()->getQueryFindAll();
        return $this->query->fetchArray();
    }

    /**
    * @return App_Model_Property | boolean
    */
    public function retrieveCategoryByUrl($url)
    {
        $this->init();
        try {
            $categoryValues = $this->getGateway()->findOneByUrl((string)$url);
            return $this->getModel($categoryValues);
        } catch (Mandragora_Gateway_NoResultsFoundException $nrfe) {
            return false;
        }
    }

    /**
     * @return void
     */
    public function createCategory()
    {
        $this->init();
        $this->getModel($this->getForm()->getValues());
        $this->getModel()->url = $this->getModel()->name;
        $this->getGateway()->insert($this->getModel());
    }

    /**
     * @param string $action
     * @return Mandragora_Form_Abstract
     */
    public function getFormForCreating($action)
    {
        $this->getForm('Detail')->setAction($action);
        $this->getForm()->prepareForCreating();
        return $this->getForm();
    }

    /**
     * @return App_Model_Category
     * @throws Mandragora_Gateway_NoResultsFoundException
     */
    public function retrieveCategoryById($id)
    {
        try {
            $this->init();
            $values = $this->getGateway()->findOneById((int)$id);
            return $this->getModel($values);
        } catch (Mandragora_Gateway_NoResultsFoundException $nrfe) {
            return false;
        }
    }

    /**
     * @param string $action
     * @return Mandragora_Form_Abstract
     */
    public function getFormForEditing($action)
    {
        $this->getForm('Detail')->setAction($action);
        $this->getForm()->prepareForEditing();
        return $this->getForm();
    }

    /**
     * @return void
     */
    public function updateCategory()
    {
        $this->init();
        $this->getModel()->fromArray($this->getForm()->getValues());
        $this->getModel()->url = $this->getModel()->name;
        $this->getGateway()->update($this->getModel());
    }

    /**
     * @param int $id
     * @return void
     */
    public function deleteCategory($id)
    {
        $this->init();
        $this->getGateway()->delete($this->getModel());
    }

    /**
    * @param Zend_Navigation $container
    * @return void
    */
    public function addCategoriesToSitemap(Zend_Navigation $container)
    {
        $this->init();
        $query = $this->getGateway()->getQueryFindAll();
        $categories = $query->fetchArray();
        $i = 0;
        foreach ($categories as $category) {
            $mvcPage = Zend_Navigation_Page::factory(
                array(
                    'controller' => 'property',
                    'action' => 'list', 'module' => 'default',
                    'route' => 'property', 'label' => $category['name'],
                    'params' => array('category' => $category['url'],)
                )
            );
            if ($i !== 0) {
                $container->findBy('label', $label)->addPage($mvcPage);
            } else {
                $container->addPage($mvcPage);
                $label = $category['name'];
            }
            $i++;
        }
    }

}
