<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Form\Picture;

use Mandragora\FormFactory;
use Mandragora\Validate\Db\Doctrine\NoRecordExists;
use PHPUnit_Framework_Assert as Assert;
use PHPUnit_Framework_TestCase as TestCase;
use Zend_File_Transfer_Adapter_Abstract as TransferAdapter;

class DetailTest extends TestCase
{
    /** @test */
    function it_can_be_created()
    {
        $this->assertInstanceOf(Detail::class, $this->pictureForm);
        $this->assertCount(7, $this->pictureForm->getElements());
    }

    /** @test */
    function it_gets_ready_to_add_a_new_picture()
    {
        $this->pictureForm->prepareForCreating();

        $elements = $this->pictureForm->getElements();
        $this->assertCount(4, $elements);
        $this->assertArrayNotHasKey('id', $elements);
        $this->assertArrayNotHasKey('version', $elements);
        $this->assertArrayNotHasKey('image', $elements);
    }

    /** @test */
    function it_gets_ready_to_edit_a_picture_information()
    {
        $this->pictureForm->prepareForEditing();

        $filename = $this->pictureForm->getElement('filename');

        $this->assertCount(0, $filename->getValidators());
        $this->assertFalse($filename->isRequired());
        $this->assertFalse(
            $this->pictureForm->getElement('shortDescription')->getValidator(NoRecordExists::class)
        );
    }

    /** @test */
    function it_sets_the_property_id()
    {
        $propertyId = 3;

        $this->pictureForm->setPropertyId($propertyId);

        $this->assertEquals($propertyId, $this->pictureForm->getElement('propertyId')->getValue());
    }

    /** @test */
    function it_sets_the_picture_id()
    {
        $pictureId = 5;

        $this->pictureForm->setPictureIdValue($pictureId);

        $this->assertEquals($pictureId, $this->pictureForm->getElement('id')->getValue());
    }

    /** @test */
    function it_sets_the_path_to_the_image()
    {
        $filename = '/uploads/image.png';

        $this->pictureForm->setSrcImage($filename);

        /** @var \Zend_Form_Element_Image $image */
        $image = $this->pictureForm->getElement('image');
        $this->assertEquals($filename, $image->getImage());
    }

    /** @test */
    function it_gets_the_images_filename()
    {
        /** @var \Zend_Form_Element_File $file */
        $file = $this->pictureForm->getElement('filename');
        $file->setTransferAdapter($this->adapter);

        $this->assertEquals($this->imagePath, $this->pictureForm->getPictureFileValue());
    }

    /** @test */
    function it_saves_the_image_file()
    {
        /** @var \Zend_Form_Element_File $file */
        $file = $this->pictureForm->getElement('filename');

        $file->setTransferAdapter($this->adapter);

        $this->pictureForm->savePictureFile($this->imagePath);
    }

    /** @before */
    function createForm()
    {
        $this->pictureForm = FormFactory::buildFromConfiguration()->create('Detail', 'Picture');
        $this->adapter = new class($this->imagePath) extends TransferAdapter {
            private $path;
            public function __construct($path) { $this->path = $path; }
            public function send($options = null) {}
            public function receive($options = null) { Assert::assertEquals('filename', $options); }
            public function isSent($files = null) {}
            public function isReceived($files = null) { return true; }
            public function isUploaded($files = null) {}
            public function isFiltered($files = null) {}
            public function getFileName($file = null, $path = true) { return $this->path; }
            public function isValid($files = null) { return true; }
        };
    }

    /** @var Detail */
    private $pictureForm;

    /** @var TransferAdapter */
    private $adapter;

    /** @var string */
    private $imagePath = 'image.png';
}
