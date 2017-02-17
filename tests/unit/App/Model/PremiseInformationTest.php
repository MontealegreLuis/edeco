<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use App\Model\PremiseInformation;

/**
 * Unit tests for Edeco_Model_PremiseInformation class
 */
class Edeco_Model_PremiseInformationTest extends ControllerTestCase
{
    public function testCanConvertToString()
    {
        $values = [
            'name' => 'Premise test', 'telephone' => '(111) 1-11-11-11',
            'emailAddress' => 'test@test.com', 'zone' => 'Test zone',
            'minPrice' => 3000, 'maxPrice' => 10000,
            'surface' => 20, 'characteristics' => 'Some nice ones'
        ];
        $premiseInformation = new PremiseInformation($values);
        $this->assertEquals('Premise test', (string)$premiseInformation);
    }

    public function testCanAccessProperties()
    {
        $values = [
            'name' => 'Premise test', 'telephone' => '(111) 1-11-11-11',
            'emailAddress' => 'test@test.com', 'zone' => 'Test zone',
            'minPrice' => '3000', 'maxPrice' => '10000',
            'surface' => 20, 'characteristics' => 'Some nice ones'
        ];
        $premiseInformation = new PremiseInformation($values);
        $this->assertEquals('Premise test', $premiseInformation->name);
        $this->assertEquals('(111) 1-11-11-11', $premiseInformation->telephone);
        $this->assertEquals('test@test.com', $premiseInformation->emailAddress);
        $this->assertEquals('Test zone', $premiseInformation->zone);
        $this->assertEquals('3000', (string) $premiseInformation->minPrice);
        $this->assertEquals('10000', (string) $premiseInformation->maxPrice);
        $this->assertEquals(20, $premiseInformation->surface);
        $this->assertEquals(
            'Some nice ones', $premiseInformation->characteristics
        );
    }
}
