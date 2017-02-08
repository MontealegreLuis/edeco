<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use App\Enum\Directories;
use App\Model\Picture;

/**
 * Unit tests for Edeco_Model_Address class
 */
class Edeco_Model_PictureTest extends ControllerTestCase
{
    public function testCanConvertToString()
    {
        $values = [
            'id' => 1, 'shortDescription' => 'Picture test',
            'filename' => 'picture-test.jpg',
            'propertyId' => 1
        ];
        $picture = new Picture($values);
        $this->assertEquals(
            Directories::Properties . 'picture-test.jpg',
            (string) $picture
        );
    }

    public function testCanAccessProperties()
    {
        $values = [
            'id' => 1, 'shortDescription' => 'Picture test',
            'filename' => 'picture-test.jpg',
            'propertyId' => 1
        ];
        $picture = new Picture($values);
        $this->assertEquals(1, $picture->id);
        $this->assertEquals('Picture test', $picture->shortDescription);
        $this->assertEquals('picture-test.jpg', $picture->filename);
        $this->assertEquals(1, $picture->propertyId);
    }
}
