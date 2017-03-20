<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora;

use PHPUnit_Framework_TestCase as TestCase;

class ImageTest extends TestCase
{
    /** @test */
    function it_gets_the_height_and_width_of_an_image()
    {
        $image = new Image(__DIR__ . '/../../fixtures/code.jpg');

        $this->assertEquals(275, $image->getWidth());
        $this->assertEquals(183, $image->getHeight());
    }
}
