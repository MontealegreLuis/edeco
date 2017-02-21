<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Form\Picture;

use Mandragora\FormFactory;
use Mandragora\Validate\Db\Doctrine\NoRecordExists;
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

        $adapter = new class() extends TransferAdapter {
            public function send($options = null) {}
            public function receive($options = null) {}
            public function isSent($files = null) {}
            public function isReceived($files = null) {}
            public function isUploaded($files = null) {}
            public function isFiltered($files = null) {}
            public function getFileName($file = null, $path = true) {
                return 'image.png';
            }
            public function isValid($files = null) {
                return true;
            }
        };
        $file->setTransferAdapter($adapter);

        $this->assertEquals('image.png', $this->pictureForm->getPictureFileValue());
    }

    /** @before */
    function createForm()
    {
        $this->pictureForm = (new FormFactory(true))->create('Detail', 'Picture');
    }

    /** @var Detail */
    private $pictureForm;
}
