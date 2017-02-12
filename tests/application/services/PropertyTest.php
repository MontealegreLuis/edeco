<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use App\Model\Dao\PropertyDao;
use App\Model\Gateway\Property as PropertyGateway;
use App\Model\Property;
use App\Service\Property as PropertyService;
use Mandragora\PHPUnit\DoctrineTest\DoctrineTestInterface;

/**
 * Unit tests for Edeco_Service_Property class
 */
class Edeco_Service_PropertyTest extends ControllerTestCase implements DoctrineTestInterface
{
    /** @var PropertyService */
    protected $property;

    /** @var PropertyGateway */
    protected $propertyGateway;

    /** @var array */
    protected $propertyInformation;

    /**
     * Setup application to run test cases
     *
     * @see tests/application/ControllerTestCase#setUp()
     */
    public function setUp()
    {
        parent::setUp();
        $this->propertyGateway = new PropertyGateway(new PropertyDao());
        $this->property = new PropertyService('Property');
    }

    public function testCanRetrievePropertyByUrl()
    {
        $property = $this->createProperty();
        $this->propertyGateway->insert($property);
        // Setters are used in service, call'em to test equality
        $property->address = $this->propertyInformation['address'];
        $propertyFound = $this->property->retrievePropertyByUrl(
            $property->url
        );
        $propertyFound->latitude =
            (float) $this->propertyInformation['latitude'];
        $propertyFound->longitude =
            (float) $this->propertyInformation['longitude'];
        //Remove fields that aren't in query's projection
        $propertyInformation = $property->toArray();
        $propertyInformation['url'] = null;
        $propertyInformation['category'] = null;
        $propertyInformation['totalSurface'] = null;
        $propertyInformation['metersOffered'] = null;
        $propertyInformation['metersFront'] = null;
        $propertyInformation['landUse'] = null;
        $propertyInformation['creationDate'] = null;
        $propertyInformation['showOnWeb'] = null;
        $propertyInformation['active'] = null;
        $this->assertEquals($propertyInformation, $propertyFound->toArray());
    }

    /**
     * @return Edeco_Model_Property
     */
    protected function createProperty(): Property
    {
        $this->propertyInformation = [
            'id' => null, 'name' => 'Local Plaza Dorada',
            'url' => 'local-plaza-dorada', 'description' => 'Local amplio',
            'price' => '5000 al mes',
            'address' =>
                'Priv tabacos,Ignacio Romero V,72120,Puebla,Tepexi,México',
            'addressReference' => 'Junto a Suburbia',
            'latitude' => 100.1, 'longitude' => 120.2, 'category' => 'premises',
            'totalSurface' => 12.5, 'metersOffered' => 16.5,
            'metersFront' => 20.5, 'landUse' => 'commercial',
            'creationDate' => '2010-01-01', 'active' => 1,
        ];
        return new Property($this->propertyInformation);
    }
}
