<?php
class   App_Form_RecommendedProperty_Detail
extends Mandragora_Form_Crud_Abstract
{
    /**
     * @return void
     */
    public function prepareForCreating() {}

    /**
     * @return void
     */
    public function prepareForEditing() {}

    /**
     * @param int $propertyId
     */
    public function setPropertyId($propertyId)
    {
        $this->getElement('id')->setValue((int)$propertyId);
    }

}