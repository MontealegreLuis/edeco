<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Form\Crud;

use Mandragora\Form\SecureForm;

/**
 * Base class for forms which will be used in CRUD operations
 */
 abstract class CrudForm extends SecureForm
{
     /**
      * Method to be overridden by developer to prepare the form to create a new
      * model.
      *
      *  @return void
      */
     public function prepareForCreating() {}

     /**
      * Method to be overridden by developer to prepare the form to edit an
      * existing model.
      *
      *  @return void
      */
     public function prepareForEditing() {}
}
