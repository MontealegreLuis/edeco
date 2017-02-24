<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Form\PremiseInformation;

use Mandragora\FormFactory;
use PHPUnit_Framework_TestCase as TestCase;

class DetailTest extends TestCase
{
    /** @test */
    function it_can_be_created()
    {
        $this->assertInstanceOf(Detail::class, $this->premiseForm);
        $this->assertCount(9, $this->premiseForm->getElements());
    }

    /** @before */
    function createForm()
    {
        $this->premiseForm = FormFactory::buildFromConfiguration()->create('Detail', 'PremiseInformation');
    }

    /** @var Detail */
    private $premiseForm;
}
