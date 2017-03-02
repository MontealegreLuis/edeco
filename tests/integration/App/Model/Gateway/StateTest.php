<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Gateway;

use App\Model\Dao\StateDao;
use App\Model\Gateway\State as StateGateway;
use App\Model\State;
use ControllerTestCase;
use Mandragora\PHPUnit\DoctrineTest\DoctrineTestInterface;

class StateTest extends ControllerTestCase implements DoctrineTestInterface
{
    public function testCanFindAllStates()
    {
        // Insert some states
        $createdStates = [];
        $stateGateway = new StateGateway(new StateDao());
        for ($i = 0; $i < 5; $i++) {
            $createdStates[$i] = $this->createState();
            $this->saveState($createdStates[$i]);
        }

        // Find the states recently inserted
        $allStates = $stateGateway->findAll();
        $this->assertCount(5, $allStates);

        // Check that the states found were the same that were inserted
        for ($i = 0; $i < 5; $i++) {
            $this->assertEquals($createdStates[$i]->name, $allStates[$i]['name']);
            $this->assertEquals($createdStates[$i]->url, $allStates[$i]['url']);
        }
    }

    public function testFindAllStatesRetrievesZeroElementsWhenTableIsEmpty()
    {
        $stateGateway = new StateGateway(new StateDao());
        $allStates = $stateGateway->findAll();
        $this->assertCount(0, $allStates);
    }

    private function createState(): State
    {
        $state = new State();
        $state->name = 'Puebla';
        $state->url = 'puebla';

        return $state;
    }

    private function saveState(State $state): void {
        $record = new StateDao();
        $record->fromArray($state->toArray());
        $record->save();
        $state->fromArray($record->toArray());
    }
}
