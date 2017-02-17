<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model;

use App\Enum\Directories;
use PHPUnit_Framework_TestCase as TestCase;

class PictureTest extends TestCase
{
    public function testCanConvertToString()
    {
        $values = [
            'id' => 1, 'shortDescription' => 'Picture test',
            'filename' => 'picture-test.jpg',
            'propertyId' => 1
        ];
        $picture = new Picture($values);
        $this->assertStringStartsWith(
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
