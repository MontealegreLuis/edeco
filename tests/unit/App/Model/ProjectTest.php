<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model;

use PHPUnit_Framework_TestCase as TestCase;

class ProjectTest extends TestCase
{
    public function testCanConvertToString()
    {
        $values = [
            'id' => 1, 'name' => 'Cool Test Project',
            'attachment' => 'cool-test-project.pps',
        ];
        $project = new Project($values);
        $this->assertEquals('Cool Test Project', (string) $project);
    }

    public function testCanAccessProperties()
    {
        $values = [
            'id' => 1, 'name' => 'Cool Test Project',
            'attachment' => 'cool-test-project.pps',
        ];
        $project = new Project($values);
        $this->assertEquals(1, $project->id);
        $this->assertEquals('Cool Test Project', $project->name);
        $this->assertEquals('cool-test-project.pps', $project->attachment);
    }
}
