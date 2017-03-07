<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Gateway;

use App\Enum\PropertyAvailability;
use App\Model\Category;
use App\Model\Dao\CategoryDao;
use App\Model\Dao\PropertyDao;
use App\Model\Gateway\Category as CategoryGateway;
use App\Model\Gateway\Property as PropertyGateway;
use App\Model\Property;
use ControllerTestCase;
use Doctrine_Core as Doctrine;
use Mandragora\Gateway\NoResultsFoundException;
use Mandragora\Model\Property\PropertyInterface;
use Mandragora\PHPUnit\DoctrineTest\DoctrineTestInterface;

class PropertyTest extends ControllerTestCase implements DoctrineTestInterface
{
    /** @test */
    function it_creates_a_property()
    {
        $this->gateway->clearRelated();
        $this->gateway->insert($this->property);

        $propertyTable = Doctrine::getTable(PropertyDao::class);
        $savedProperty = $propertyTable->findOneById($this->property->id)->toArray();

        $this->assertEquals(
            $this->normalizePropertyValues($this->property->toArray()),
            $savedProperty
        );
    }

    /** @test */
    function it_finds_a_property_by_id()
    {
        $this->addProperty($this->property);

        $foundProperty = $this->gateway->findOneById($this->property->id);

        $this->assertEquals(
            $this->normalizePropertyValues($this->property->toArray()),
            $this->normalizePropertyValues($foundProperty)
        );
    }

    /** @test */
    function it_does_not_find_a_non_existing_property()
    {
        $this->expectException(NoResultsFoundException::class);
        $this->gateway->findOneById(-1);
    }

    /** @test */
    function it_finds_all_properties()
    {
        // Insert some properties
        $createdProperties = [];
        for ($i = 0; $i < 3; $i++) {
            $createdProperties[$i] = $this->createProperty($this->category);
            $this->addProperty($createdProperties[$i]);
        }

        // Find the properties recently inserted
        $allProperties = $this->gateway->getQueryFindAll()->removeDqlQueryPart('join')->fetchArray();
        $this->assertCount(3, $allProperties);

        // Check that the properties found were the same that were inserted
        for ($i = 0; $i < 3; $i++) {
            $this->assertEquals(
                $this->normalizePropertyValues($createdProperties[$i]->toArray()),
                $this->normalizePropertyValues($allProperties[$i])
            );
        }
    }

    function it_retrieves_zero_elements_when_table_is_empty()
    {
        $this->assertCount(0, $this->gateway->findAllWebProperties());
    }

    /** @before */
    function configureProperties()
    {
        $this->category = new Category(['name' => 'Premises', 'url' => 'premises']);
        (new CategoryGateway(new CategoryDao()))->insert($this->category);
        $this->gateway = new PropertyGateway($this->newRecord());
        $this->property = $this->createProperty($this->category);
    }

    private function createProperty(Category $category): Property
    {
        return new Property([
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
            'categoryId' => $category->id,
            'showOnWeb' => 1,
            'availabilityFor' => PropertyAvailability::Rent,
            'Picture' => []
        ]);
    }

    /**
     * @return string[]
     */
    private function normalizePropertyValues(array $property): array
    {
        return array_filter(
            array_map(function ($value) {
                if ($value instanceof PropertyInterface) {
                    return (string)$value;
                }
                return $value;
            }, $property),
            function ($key) {
                return $key{0} !== strtoupper($key{0});
            },
            ARRAY_FILTER_USE_KEY
        );
    }

    private function newRecord(): PropertyDao
    {
        return new PropertyDao();
    }

    private function addProperty(Property $property): void
    {
        $record = $this->newRecord();
        $record->fromArray($property->toArray());
        $record->clearRelated();
        $record->save();
        $property->fromArray($record->toArray());
    }

    /** @var PropertyGateway */
    private $gateway;

    /** @var Category */
    private $category;

    /** @var Property */
    private $property;
}
