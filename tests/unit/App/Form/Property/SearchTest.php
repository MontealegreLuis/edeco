<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Form\Property;

use Mandragora\FormFactory;
use PHPUnit_Framework_TestCase as TestCase;

class SearchTest extends TestCase
{
    /** @test */
    function it_can_be_created()
    {
        $this->assertInstanceOf(Search::class, $this->searchForm);
        $this->assertCount(2, $this->searchForm->getElements());
    }

    /** @before */
    function createForm()
    {
        $this->searchForm = FormFactory::useConfiguration()->create('Search', 'Property');
    }

    /** @var Search */
    private $searchForm;
}
