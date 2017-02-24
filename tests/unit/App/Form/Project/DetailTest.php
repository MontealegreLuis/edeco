<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Form\Project;

use Mandragora\FormFactory;
use PHPUnit_Framework_Assert as Assert;
use PHPUnit_Framework_TestCase as TestCase;
use Zend_File_Transfer_Adapter_Abstract as TransferAdapter;
use Zend_Validate_File_Upload as FileUpload;

class DetailTest extends TestCase
{
    /** @test */
    function it_can_be_created()
    {
        $this->assertInstanceOf(Detail::class, $this->projectForm);
        $this->assertCount(5, $this->projectForm->getElements());
    }

    /** @test */
    function it_configures_the_upload_validator_messages()
    {
        /** @var \Zend_Validate_File_Upload $validator */
        $validator = $this->projectForm->getElement('attachment')->getValidator('Upload');

        $this->assertArrayHasKey(FileUpload::FILE_NOT_FOUND, $validator->getMessageTemplates());
        $this->assertEquals(
            'project.attachment.fileUploadErrorFileNotFound',
            $validator->getMessageTemplates()[FileUpload::FILE_NOT_FOUND]
        );
    }

    /** @test */
    function it_gets_ready_to_add_a_new_project()
    {
        $this->projectForm->prepareForCreating();

        $elements = $this->projectForm->getElements();
        $this->assertCount(3, $elements);
        $this->assertArrayNotHasKey('id', $elements);
        $this->assertArrayNotHasKey('version', $elements);
    }

    /** @test */
    function it_saves_attached_file()
    {
        /** @var \Zend_Form_Element_File $fileElement */
        $fileElement = $this->projectForm->getElement('attachment');
        $fileElement->setTransferAdapter($this->adapter);

        $this->projectForm->saveAttachmentFile($this->filePath);
    }

    /** @test */
    function it_determines_if_no_file_has_been_uploaded()
    {
        $this->assertFalse($this->projectForm->hasNewPowerPoint());
    }

    /** @test */
    function it_knows_when_a_file_has_been_uploaded()
    {
        /** @var \Zend_Form_Element_File $fileElement */
        $fileElement = $this->projectForm->getElement('attachment');
        $fileElement->setTransferAdapter($this->adapter);

        $this->assertTrue($this->projectForm->hasNewPowerPoint());
    }

    /** @test */
    function it_gets_the_original_name_of_the_attached_file()
    {
        /** @var \Zend_Form_Element_File $fileElement */
        $fileElement = $this->projectForm->getElement('attachment');
        $fileElement->setTransferAdapter($this->adapter);

        $this->assertEquals($this->filePath, $this->projectForm->getAttachmentOriginalFileName());
    }

    /** @before */
    function createForm()
    {
        $this->projectForm = FormFactory::buildFromConfiguration()->create('Detail', 'Project');
        $this->adapter = new class($this->filePath) extends TransferAdapter {
            private $path;
            public function __construct($path) { $this->path = $path; }
            public function send($options = null) {}
            public function receive($options = null) { Assert::assertEquals('attachment', $options); }
            public function isSent($files = null) {}
            public function isReceived($files = null) { return true; }
            public function isUploaded($files = null) {}
            public function isFiltered($files = null) {}
            public function getFileName($file = null, $path = true) { return $this->path; }
            public function isValid($files = null) { return true; }
        };
    }

    /** @var Detail */
    private $projectForm;

    /** @var TransferAdapter */
    private $adapter;

    /** @var string */
    private $filePath = 'slides.pps';
}
