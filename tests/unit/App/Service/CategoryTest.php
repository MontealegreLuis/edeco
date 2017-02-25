<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use App\Model\Category;
use App\Service\Category as CategoryService;
use PHPUnit_Framework_TestCase as TestCase;

class CategoryTest extends TestCase
{
    /** @test */
    function it_can_create_a_category_model()
    {
        $this->assertInstanceOf(Category::class, $this->category->getModel());
    }

    /** @before */
    function createService()
    {
        $this->category = new CategoryService('Category');
    }

    /** @var CategoryService */
    private $category;
}
