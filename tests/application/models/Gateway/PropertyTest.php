<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use App\Model\Dao\PropertyDao;
use App\Model\Property;
use App\Model\Gateway\Property as PropertyGateway;
use Doctrine_Core as Doctrine;
use Mandragora\PHPUnit\DoctrineTest\DoctrineTestInterface;

/**
 * Unit tests for Edeco_Model_Gateway_Property class
 */
class Edeco_Model_Gateway_PropertyTest extends ControllerTestCase implements DoctrineTestInterface
{
    public function testCanCreateProperty()
    {
        $property = $this->createProperty();
        $gatewayProperty = new PropertyGateway(new PropertyDao());
        $gatewayProperty->insert($property);
        $propertyTable = Doctrine::getTable(Property::class);
        $this->assertEquals(
            $property->toArray(),
            $propertyTable->findOneById($property->id)->toArray()
        );
    }

    public function testCanFindPropertyById()
    {
        $property = $this->createProperty();
        $gatewayProperty = new PropertyGateway(new PropertyDao());
        $gatewayProperty->insert($property);
        $foundProperty = $gatewayProperty->findOneById($property->id);
        // Correct some stuff, Doctrine always return strings
        $foundProperty['latitude'] = (float) $foundProperty['latitude'];
        $foundProperty['longitude'] = (float) $foundProperty['longitude'];
        $foundProperty['Picture'] = [];
        $this->assertEquals($property->toArray(), $foundProperty);
    }

    /**
     * @expectedException \Mandragora\Gateway\NoResultsFoundException
     */
    public function testFindNonExistentPropertyByIdThrowsException()
    {
        $gatewayProperty = new PropertyGateway(new PropertyDao());
        $gatewayProperty->findOneById(-1);
    }

    public function testCanFindAllProperties()
    {
        // Insert some properties
        $createdProperties = [];
        $gatewayProperty = new PropertyGateway(new PropertyDao());
        for ($i = 0; $i < 3; $i++) {
            $createdProperties[$i] = $this->createProperty();
            $gatewayProperty->insert($createdProperties[$i]);
        }

        // Find the properties recently inserted
        $allProperties = $gatewayProperty->findAll();
        $this->assertCount(3, $allProperties);

        // Check that the properties found were the same that were inserted
        for ($i = 0; $i < 3; $i++) {
            $this->assertEquals(
                $createdProperties[$i]->toArray(), $allProperties[$i]
            );
        }
    }

    public function testFindAllPropertiesRetrievesZeroElementsWhenTableIsEmpty()
    {
        $gatewayProperty = new PropertyGateway(new PropertyDao());
        $allProperties = $gatewayProperty->findAllWebProperties();
        $this->assertCount(0, $allProperties);
    }

    public function testFindAddressByIdMustRetunString()
    {
    	$property = $this->createProperty();
        $gatewayProperty = new PropertyGateway(new PropertyDao());
        $gatewayProperty->insert($property);
        $address =  $gatewayProperty->findAddressByPropertyId(1);
        $this->assertEquals(
            'Priv tabacos,Ignacio Romero V,72120,Puebla,Tepexi,México', $address
        );
    }

    public function testUpdateAddressById()
    {
    	$property = $this->createProperty();
        $gatewayProperty = new PropertyGateway(new PropertyDao());
        $gatewayProperty->insert($property);
        $address = "violetas 59,la vista, 1,1,72100,lv";
        $gatewayProperty->updateAddressByPropertyId($address, 1);
    }

    /**
     * @return Property
     */
    protected function createProperty()
    {
        $propertyInformation = [
            'id' => null, 'name' => 'Local Plaza Dorada',
            'url' => 'local-plaza-dorada', 'description' => 'Local amplio',
            'price' => '5000 al mes',
            'address' => 'Priv tabacos,Ignacio Romero V,72120,Puebla,Tepexi,México',
            'addressReference' => 'Junto a Suburbia',
            'latitude' => 100.1, 'longitude' => 120.2, 'category' => 'premises',
            'totalSurface' => 12.5, 'metersOffered' => 16.5,
            'metersFront' => 20.5, 'landUse' => 'commercial',
            'creationDate' => '2010-01-01', 'active' => 1,
            'Picture' => []
        ];
        return new Property($propertyInformation);
    }
}
