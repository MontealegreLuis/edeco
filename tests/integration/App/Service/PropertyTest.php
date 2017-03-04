<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use App\Model\Address;
use App\Model\Category;
use App\Model\City;
use App\Model\State;
use App\Model\Dao\AddressDao;
use App\Model\Dao\CategoryDao;
use App\Model\Dao\CityDao;
use App\Model\Dao\PropertyDao;
use App\Model\Dao\StateDao;
use App\Model\Gateway\AddressGateway;
use App\Model\Gateway\Category as CategoryGateway;
use App\Model\Gateway\City as CityGateway;
use App\Model\Gateway\Property as PropertyGateway;
use App\Model\Gateway\State as StateGateway;
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
        $this->property->setCacheManager($this->_frontController->getParam('bootstrap')->getResource('cachemanager'));
        $this->property->setDoctrineManager($this->_frontController->getParam('bootstrap')->getResource('doctrine'));
    }

    public function testCanRetrievePropertyByUrl()
    {
        $property = $this->createProperty();
        $this->propertyGateway->clearRelated();
        $this->propertyGateway->insert($property);

        $state = new State(['name' => 'Puebla', 'url' => 'puebla']);
        (new StateGateway(new StateDao()))->insert($state);
        $city = new City([
            'name' => 'Tepexi',
            'url' => 'tepexi',
            'stateId' => $state->id
        ]);
        (new CityGateway(new CityDao()))->insert($city);
        $address = new Address([
            'id' => $property->id,
            'streetAndNumber' => 'priv tabacos',
            'neighborhood' => 'Ignacio Romero V ', 'state' => $state->id,
            'addressReference' => 'Antes de llegar a la salida a la autopista',
            'cityId' => $city->id, 'zipCode' => 72120,
        ]);
        (new AddressGateway(new AddressDao()))->insert($address);

        $propertyFound = $this->property->retrievePropertyByUrl($property->url);

        $this->assertEquals($property->id, $propertyFound->id);
        $this->assertEquals($property->name, $propertyFound->name);
        $this->assertEquals($property->url, $propertyFound->url);
        $this->assertEquals($property->description, $propertyFound->description);
        $this->assertEquals($property->price, $propertyFound->price);
    }

    protected function createProperty(): Property
    {
        $category = new Category(['name' => 'Premises', 'url' => 'premises']);
        (new CategoryGateway(new CategoryDao()))->insert($category);
        $this->propertyInformation = [
            'id' => null, 'name' => 'Local Plaza Dorada',
            'url' => 'local-plaza-dorada', 'description' => 'Local amplio',
            'price' => '5000 al mes', 'categoryId' => $category->id,
            'totalSurface' => 12.5, 'metersOffered' => 16.5,
            'metersFront' => 20.5, 'landUse' => 'commercial',
            'creationDate' => '2010-01-01', 'active' => 1,
        ];
        return new Property($this->propertyInformation);
    }
}
