<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use App\Form\Picture\Detail;
use App\Model\Picture;
use App\Service\Picture as PictureService;
use PHPUnit_Framework_TestCase as TestCase;
use Prophecy\Argument;
use Zend_Cache_Manager as CacheManager;

class PictureServiceTest extends TestCase
{
    /** @test */
    function it_can_get_the_form_prepared_for_adding_a_new_picture()
    {
        $action = '/save';

        $form = $this->pictureService->getFormForCreating($action);

        $this->cacheManager->getCache(Argument::any())->shouldNotHaveBeenCalled();
        $this->assertInstanceOf(Detail::class, $form);
        $this->assertEquals($action, $form->getAction());
    }

    /** @test */
    function it_can_get_the_form_prepared_for_editing()
    {
        $action = '/update';

        $form = $this->pictureService->getFormForEditing($action);

        $this->cacheManager->getCache(Argument::any())->shouldNotHaveBeenCalled();
        $this->assertInstanceOf(Detail::class, $form);
        $this->assertEquals($action, $form->getAction());
    }

    /** @test */
    function it_can_create_a_picture_model()
    {
        $this->assertInstanceOf(Picture::class, $this->pictureService->getModel());
    }

    /** @before */
    function createService()
    {
        $this->pictureService = new PictureService('Picture');
        $this->cacheManager = $this->prophesize(CacheManager::class);
        $this->pictureService->setCacheManager($this->cacheManager->reveal());
    }

    /** @var PictureService */
    private $pictureService;

    /** @var CacheManager */
    private $cacheManager;
}
