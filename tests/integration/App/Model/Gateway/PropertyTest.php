<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Gateway;

use App\Enum\PropertyAvailability;
use App\Model\Dao\PropertyDao;
use App\Model\Gateway\Property as PropertyGateway;
use App\Model\Property;
use ControllerTestCase;
use Doctrine_Core as Doctrine;
use Edeco\Fixtures\PropertiesFixture;
use Mandragora\Gateway\NoResultsFoundException;
use Mandragora\PHPUnit\DoctrineTest\DoctrineTestInterface;

class PropertyTest extends ControllerTestCase implements DoctrineTestInterface
{
    /** @test */
    function it_creates_a_property()
    {
        $this->gateway->insert($this->property);

        /** @var PropertyDao $savedProperty */
        $savedProperty = $this->table->findOneById($this->property->id);

        $this->assertGreaterThan(0, $savedProperty->id);
        $this->assertPropertiesEqual($this->property->toArray(), $savedProperty->toArray());
    }

    /** @test */
    function it_finds_a_property_by_id()
    {
        $foundProperty = $this->gateway->findOneById($this->fixture->propertyId());

        $this->assertPropertiesEqual($this->fixture->property(), $foundProperty);
    }

    /** @test */
    function it_does_not_find_a_non_existing_property()
    {
        $this->expectException(NoResultsFoundException::class);
        $this->gateway->findOneById(-1);
    }

    /** @ */
    function it_finds_all_properties()
    {
        $this->fixture->addMoreProperties();
        $existingProperties = $this->fixture->properties();

        $allProperties = $this->gateway->getQueryFindAll()->fetchArray();

        $this->assertCount(5, $allProperties);

        // Check that the properties found were the same that were inserted
        foreach (range(0, 4) as $i) {
            $this->assertPropertiesEqual($existingProperties[$i], $allProperties[$i]);
        }
    }

    /** @before */
    function configureProperties()
    {
        /** @var \Mandragora\Application\Doctrine\Manager $manager */
        $manager = $this->_frontController->getParam('bootstrap')->getResource('doctrine');
        $this->fixture = PropertiesFixture::fromDSN($manager->getConfiguration()['dsn']);
        $this->gateway = new PropertyGateway(new PropertyDao());
        $this->property = new Property([
            'name' => 'Local Plaza Dorada',
            'url' => 'local-plaza-dorada',
            'description' => 'Local amplio',
            'price' => '5000 al mes',
            'totalSurface' => 12.5,
            'metersOffered' => 16.5,
            'metersFront' => 20.5,
            'landUse' => 'commercial',
            'creationDate' => '2010-01-01',
            'active' => 1,
            'categoryId' => $this->fixture->categoryId(),
            'showOnWeb' => 1,
            'availabilityFor' => PropertyAvailability::Rent,
            'Picture' => []
        ]);
        $this->table = Doctrine::getTable(PropertyDao::class);
    }

    private function assertPropertiesEqual(array $existingProperty, array $foundProperty): void
    {
        $this->assertEquals($existingProperty['name'], $foundProperty['name']);
        $this->assertEquals($existingProperty['url'], $foundProperty['url']);
        $this->assertEquals($existingProperty['description'], $foundProperty['description']);
        $this->assertEquals($existingProperty['price'], $foundProperty['price']);
        $this->assertEquals($existingProperty['totalSurface'], $foundProperty['totalSurface']);
        $this->assertEquals($existingProperty['metersOffered'], $foundProperty['metersOffered']);
        $this->assertEquals($existingProperty['metersFront'], $foundProperty['metersFront']);
        $this->assertEquals($existingProperty['landUse'], $foundProperty['landUse']);
        $this->assertEquals($existingProperty['showOnWeb'], $foundProperty['showOnWeb']);
        $this->assertEquals($existingProperty['availabilityFor'], $foundProperty['availabilityFor']);
        $this->assertEquals($existingProperty['categoryId'], $foundProperty['categoryId']);
    }

    /** @var PropertiesFixture */
    private $fixture;

    /** @var PropertyGateway */
    private $gateway;

    /** @var Property */
    private $property;

    /** @var \Doctrine_Table */
    private $table;
}
