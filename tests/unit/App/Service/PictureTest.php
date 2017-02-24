<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use App\Form\Picture\Detail;
use PHPUnit_Framework_TestCase as TestCase;
use Zend_Cache_Manager as CacheManager;

class PictureTest extends TestCase
{
    /** @test */
    function it_can_get_the_form_prepared_for_adding_a_new_picture()
    {
        $action = '/save';

        $form = $this->pictureService->getFormForCreating($action);

        $this->cacheManager->getCache('form')->shouldNotHaveBeenCalled();
        $this->assertInstanceOf(Detail::class, $form);
        $this->assertEquals($action, $form->getAction());
    }

    /** @test */
    function it_can_get_the_form_prepared_for_editing()
    {
        $action = '/update';

        $form = $this->pictureService->getFormForEditing($action);

        $this->cacheManager->getCache('form')->shouldNotHaveBeenCalled();
        $this->assertInstanceOf(Detail::class, $form);
        $this->assertEquals($action, $form->getAction());
    }

    /** @before */
    function createService()
    {
        $this->pictureService = new Picture('Picture');
        $this->cacheManager = $this->prophesize(CacheManager::class);
        $this->pictureService->setCacheManager($this->cacheManager->reveal());
    }

    /** @var Picture */
    private $pictureService;

    /** @var CacheManager */
    private $cacheManager;
}
