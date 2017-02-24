<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use App\Form\Project\Detail;
use PHPUnit_Framework_TestCase as TestCase;
use Prophecy\Argument;
use Zend_Cache_Manager as CacheManager;

class ProjectTest extends TestCase
{
    /** @test */
    function it_can_get_the_form_prepared_for_adding_a_new_picture()
    {
        $action = '/save';

        $form = $this->projectService->getFormForCreating($action);

        $this->cacheManager->getCache(Argument::any())->shouldNotHaveBeenCalled();
        $this->assertInstanceOf(Detail::class, $form);
        $this->assertEquals($action, $form->getAction());
    }

    /** @test */
    function it_can_get_the_form_prepared_for_editing()
    {
        $action = '/update';

        $form = $this->projectService->getFormForEditing($action);

        $this->cacheManager->getCache(Argument::any())->shouldNotHaveBeenCalled();
        $this->assertInstanceOf(Detail::class, $form);
        $this->assertEquals($action, $form->getAction());
    }

    /** @before */
    function createService()
    {
        $this->projectService = new Project('Project');
        $this->cacheManager = $this->prophesize(CacheManager::class);
        $this->projectService->setCacheManager($this->cacheManager->reveal());
    }

    /** @var Picture */
    private $projectService;

    /** @var CacheManager */
    private $cacheManager;
}
