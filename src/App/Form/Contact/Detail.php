<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Form\Contact;

use Mandragora\Form\AbstractForm;
use Zend_Validate_NotEmpty;
use Zend_Validate_StringLength;
use Zend_Filter_StripTags;
use Zend_Validate_EmailAddress;
use Zend_Validate_Hostname;
use Zend_Filter_StringToLower;

/**
 * Contact form
 */
class Detail extends AbstractForm
{
    /**
     * Creation and initialization of the elements in the form
     *
     * @return void
     */
    public function init()
    {
/*
        $this->addElement($this->createContactName(0));
        $this->addElement($this->createContactEmailAddress(1));
        $this->addElement($this->createContactMessage(2));
        $this->addElement($this->createSendButton(3));
        $this->addDisplayGroup(
            array(
                $this->contactNameId, $this->contactEmailAddressId,
                $this->contactMessageId, $this->sendButtonId
            ),
            'grpContact',
            array('Legend' => 'Datos de contacto')
        );
  */
    }

    /**
     * Create a text control for the name of the contact and configure it with
     * the corresponding validators (NotEmpty and StringLength)
     *
     * @param int $order
     * @return Zend_Form_Element_Text
     */
    protected function createContactName($order)
    {
        $name = $this->createElement('text', $this->contactNameId);

        $requiredValidator = new Zend_Validate_NotEmpty(
            array('type' => Zend_Validate_NotEmpty::STRING)
        );
        $requiredValidator->setMessages(
            array(
                Zend_Validate_NotEmpty::IS_EMPTY =>
                    'Por favor ingrese su nombre'
            )
        );

        $sizeValidator = new Zend_Validate_StringLength(
            array('min' => 4, 'max' => 100)
        );
        $sizeValidator->setMessages(
            array(
                Zend_Validate_StringLength::TOO_SHORT =>
                    "Su nombre debería tener al menos %min% "
                    . "caracteres",
                Zend_Validate_StringLength::TOO_LONG =>
                    "Su nombre debe tener máximo %max% caracteres"
            )
        );

        $name->setLabel('Ingrese su nombre:')
             ->setOrder((int)$order)
             ->setRequired(true)
             ->addValidator($requiredValidator)
             ->addValidator($sizeValidator)
             ->addFilter(new Zend_Filter_StripTags())
             ->setDecorators($this->getElementDecoratorOptions())
             ->setAttribs(
                  array(
                      'size' => 40,
                      'maxlength' => 100
                  )
              );

        return $name;
    }

    /**
     * Create a text control for the email of the  contact and configure it with
     * the corresponding validators (NotEmpty, StringLength and EmailAddress)
     *
     * @param int $order
     * @return Zend_Form_Element_Text
     */
    protected function createContactEmailAddress($order)
    {
        $email = $this->createElement('text', $this->contactEmailAddressId);

        $requiredValidator = new Zend_Validate_NotEmpty(
            array('type' => Zend_Validate_NotEmpty::STRING)
        );
        $requiredValidator->setMessages(
            array(
                Zend_Validate_NotEmpty::IS_EMPTY =>
                    'Por favor ingrese su dirección de correo electónico'
            )
        );

        $emailValidator = new Zend_Validate_EmailAddress();
        $emailValidator->setMessages(
            array(
                Zend_Validate_EmailAddress::INVALID_FORMAT =>
                "'%value%' no es una dirección de correo electrónico válida",
                Zend_Validate_EmailAddress::INVALID_HOSTNAME =>
                "'%hostname%' no es un dominio válido para el correo '%value%'",
                Zend_Validate_Hostname::INVALID_HOSTNAME =>
                    "'%value%' no es un nombre de dominio válido",
                Zend_Validate_Hostname::LOCAL_NAME_NOT_ALLOWED =>
                "No se permiten direcciones con dominios locales como '%value%'",
                Zend_Validate_Hostname::INVALID_LOCAL_NAME =>
                    "'%value%' no es un nombre de dominio local válido",
                Zend_Validate_EmailAddress::DOT_ATOM =>
                "'%localPart%' no corresponde al formato de correo con punto",
                Zend_Validate_EmailAddress::QUOTED_STRING =>
                "'%localPart%' no corresponde al formato de correo con comillas",
                Zend_Validate_EmailAddress::INVALID_LOCAL_PART =>
                "'%localPart%' no es un correo válido para el dominio '%value%'",
				Zend_Validate_Hostname::UNKNOWN_TLD =>
				"'%value%' parece ser un nombre de dominio pero no puede "
				. "encontrarse en la lista de dominios conocidos",
            )
        );
        $email->setLabel(
            'Ingrese el correo electrónico en el que desea recibir la '
            . 'respuesta:')
             ->setOrder((int)$order)
             ->setRequired(true)
             ->addValidator($requiredValidator)
             ->addValidator($emailValidator)
             ->addFilter(new Zend_Filter_StringToLower())
             ->addFilter(new Zend_Filter_StripTags())
             ->setDecorators($this->getElementDecoratorOptions())
             ->setAttribs(
                  array(
                      'size' => 40,
                      'maxlength' => 120
                  )
              );

        return $email;
    }

    /**
     * Create a textarea control for the message that the user wants to send by
     * email (NotEmpty y StringLength)
     *
     * @param int $order
     * @return Zend_Form_Element_Textarea
     */
    protected function createContactMessage($order)
    {
        $message = $this->createElement('textarea', $this->contactMessageId);
        $requiredValidator = new Zend_Validate_NotEmpty(
            array('type' => Zend_Validate_NotEmpty::STRING)
        );
        $requiredValidator->setMessages(
            array(
                Zend_Validate_NotEmpty::IS_EMPTY =>
                    'Por favor ingrese su mensaje'
            )
        );
        $sizeValidator = new Zend_Validate_StringLength(
            array('min' => 6, 'max' => 1500)
        );
        $sizeValidator->setMessages(
            array(
                Zend_Validate_StringLength::TOO_SHORT =>
                    "Su mensaje debe tener al menos %min% caracteres",
                Zend_Validate_StringLength::TOO_LONG =>
                    "Su mensaje debe tener máximo %max% caracteres"
            )
        );
        $message->setLabel('Ingrese su duda, queja o sugerencia:')
                ->setOrder((int)$order)
                ->setRequired(true)
                ->addValidator($requiredValidator)
                ->addValidator($sizeValidator)
                ->addFilter(new Zend_Filter_StripTags())
                ->setDecorators($this->getElementDecoratorOptions());

        return $message;
    }

    /**
     * Creates the submit button for this form
     *
     * @param int $order
     * @return Zend_Form_Element_Submit
     */
    protected function createSendButton($order)
    {
        $submit = $this->createElement('submit', $this->sendButtonId);
        $submit->setValue('Enviar')
               ->setDecorators($this->getButtonDecoratorOptions())
               ->setRequired(false)
               ->setIgnore(true)
               ->setOrder((int)$order);

        return $submit;
    }
}
