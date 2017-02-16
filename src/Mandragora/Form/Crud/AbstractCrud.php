<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Form\Crud;

use Mandragora\Form\SecureForm;

/**
 * Base class for forms which will be used in CRUD operations
 */
 abstract class AbstractCrud extends SecureForm
{
     /**
      * Method to be overrided by developer for preparing the form of the
      * model for creating
      *
      *  @return void
      */
     public abstract function prepareForCreating();

     /**
      * Method to be overrided by developer for preparing the form of the
      * model for editing
      *
      *  @return void
      */
     public abstract function prepareForEditing();
}
