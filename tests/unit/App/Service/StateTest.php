<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use App\Model\State;
use App\Service\State as StateService;
use PHPUnit_Framework_TestCase as TestCase;

class StateTest extends TestCase
{
    /** @test */
    function it_creates_a_state_model()
    {
        $this->assertInstanceOf(State::class, $this->stateService->getModel());
    }

    /** @before */
    function createService()
    {
        $this->stateService = new StateService('State');
    }

    /** @var StateService */
    private $stateService;
}
