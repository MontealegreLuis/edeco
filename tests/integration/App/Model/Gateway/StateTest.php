<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Gateway;

use App\Model\Dao\StateDao;
use App\Model\Gateway\State as StateGateway;
use ControllerTestCase;
use Edeco\Fixtures\CitiesFixture;
use Mandragora\PHPUnit\DoctrineTest\DoctrineTestInterface;

class StateTest extends ControllerTestCase implements DoctrineTestInterface
{
    /** @test */
    function it_finds_all_states()
    {
        $this->fixture->addMoreStates();
        $createdStates = array_values($this->fixture->states());

        $allStates = $this->stateGateway->findAll();

        $this->assertCount(5, $allStates);
        for ($i = 0; $i < 5; $i++) {
            $this->assertEquals($createdStates[$i]['id'], $allStates[$i]['id']);
            $this->assertEquals($createdStates[$i]['name'], $allStates[$i]['name']);
            $this->assertEquals($createdStates[$i]['url'], $allStates[$i]['url']);
        }
    }

    /** @test */
    function it_finds_an_state_by_its_url()
    {
        $state = $this->stateGateway->findOneByUrl($this->fixture->stateUrl());

        $this->assertEquals($this->fixture->state(), $state);
    }

    /** @before */
    function configureFixture()
    {
        $this->stateGateway = new StateGateway(new StateDao());
        /** @var \Mandragora\Application\Doctrine\Manager $manager */
        $manager = $this->_frontController->getParam('bootstrap')->getResource('doctrine');
        $this->fixture = CitiesFixture::fromDSN($manager->getConfiguration()['dsn']);
    }

    /** @var CitiesFixture */
    private $fixture;

    /** @var StateGateway */
    private $stateGateway;
}
