<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use App\Model\Contact;

/**
 * Unit tests for Edeco_Model_Address class
 */
class Edeco_Model_ContactTest extends ControllerTestCase
{
    public function testCanConvertToString()
    {
        $values = [
            'name' => 'Luis', 'emailAddress' => 'luis@mandragora.com',
            'message' => 'test message'
        ];
        $contact = new Contact($values);
        $this->assertEquals('Luis', (string)$contact);
    }

    public function testCanAccessProperties()
    {
        $values = [
            'name' => 'Luis', 'emailAddress' => 'luis@gmail.com',
            'message' => 'test message'
        ];
        $contact = new Contact($values);
        $this->assertEquals('Luis', $contact->name);
        $this->assertEquals('luis@gmail.com', $contact->emailAddress);
        $this->assertEquals('test message', $contact->message);
    }
}
