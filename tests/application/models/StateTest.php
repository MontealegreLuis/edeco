<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use App\Model\State;

/**
 * Unit tests for Edeco_Model_State class
 */
class Edeco_Model_StateTest extends ControllerTestCase
{
    public function testCanConvertToString()
    {
        $state = new State(['id' => 1, 'name' => 'Puebla']);
        $this->assertEquals('Puebla', (string) $state);
    }

    public function testCanAccessProperties()
    {
        $state = new State(['id' => 1, 'name' => 'Puebla']);
        $this->assertEquals(1, $state->id);
        $this->assertEquals('Puebla', $state->name);
    }
}
