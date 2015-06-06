<?php
/**
 * Propertys'form
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
 * @subpackage Form
 * @author     LNJ <lemuel.nonoal@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */

/**
 * Propertys' form
 *
 * @category   Application
 * @package    Edeco
 * @subpackage Form
 * @author     LNJ <lemuel.nonoal@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class App_Form_Property_Detail extends Mandragora_Form_Crud_Abstract
{
    /**
     * @return void
     */
    public function prepareForCreating()
    {
        $this->removeElement('id');
        $this->removeElement('version');
    }

    /**
     * @return void
     */
    public function prepareForEditing()
    {
        $this->getElement('name')
             ->removeValidator('Db_Doctrine_NoRecordExists');
    }

    /**
     * @param array $categories
     * @return void
     */
    public function setCategories(array $categories)
    {
        $haystack = array();
        $categoryCollection = array();
        foreach ($categories as $category) {
            $haystack[] = $category['id'];
            $categoryCollection[$category['id']] = $category['name'];
        }
        $category = $this->getElement('categoryId');
        $category->getValidator('InArray')->setHaystack($haystack);
        $options = array('' => 'form.emptyOption') + $categoryCollection;
        $category->setMultioptions($options);
    }

    /**
     * @param array $availabilities
     * @return void
     */
    public function setAvailabilities(array $availabilities)
    {
        $haystack = array_keys($availabilities);
        $availabilityFor = $this->getElement('availabilityFor');
        $availabilityFor->getValidator('InArray')->setHaystack($haystack);
        $availabilityFor->setMultioptions($availabilities);
    }

    /**
     * @param array $landUses
     * @return void
     */
    public function setLandUses(array $landUses)
    {
        $haystack = array_keys($landUses);
        $landUse = $this->getElement('landUse');
        $landUse->getValidator('InArray')->setHaystack($haystack);
        $options = array('' => 'form.emptyOption') + $landUses;
        $landUse->setMultioptions($options);
    }

}