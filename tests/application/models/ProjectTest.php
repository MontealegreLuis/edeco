<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use App\Model\Project;

/**
 * Unit tests for Edeco_Model_Address class
 *
 * @category   Tests
 * @package    Edeco
 * @subpackage Test
 * @author     LMV <luis.montealegre@mandragora-web-systems.com>
 * @copyright  Mandrágora Web-Based Systems 2010
 * @version    SVN: $Id$
 */
class Edeco_Model_ProjectTest extends ControllerTestCase
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
