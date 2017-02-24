<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Form\Category;

use Mandragora\FormFactory;
use Mandragora\Validate\Db\Doctrine\NoRecordExists;
use PHPUnit_Framework_TestCase as TestCase;

class DetailTest extends TestCase
{
    /** @test */
    function it_can_be_created()
    {
        $this->assertInstanceOf(Detail::class, $this->categoryForm);
        $this->assertCount(4, $this->categoryForm->getElements());
    }

    /** @test */
    function it_gets_prepared_for_creating_a_category()
    {
        $this->categoryForm->prepareForCreating();

        $elements = $this->categoryForm->getElements();
        $this->assertCount(2, $elements);
        $this->assertArrayNotHasKey('id', $elements);
        $this->assertArrayNotHasKey('version', $elements);
    }

    /** @test */
    function it_gets_prepared_for_editing()
    {
        $this->categoryForm->prepareForEditing();

        $this->assertCount(4, $this->categoryForm->getElements());
        $this->assertFalse(
            $this->categoryForm->getElement('name')->getValidator(NoRecordExists::class)
        );
    }

    /** @before */
    function createForm()
    {
        $this->categoryForm = FormFactory::buildFromConfiguration()->create('Detail', 'Category');
    }

    /** @var Detail */
    private $categoryForm;
}
