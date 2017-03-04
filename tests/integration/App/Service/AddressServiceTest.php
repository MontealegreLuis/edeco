<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use App\Container\AddressContainer;
use App\Enum\PropertyLandUse;
use App\Model\Address;
use App\Model\Category;
use App\Model\City;
use App\Model\Dao\AddressDao;
use App\Model\Dao\CategoryDao;
use App\Model\Dao\CityDao;
use App\Model\Dao\StateDao;
use App\Model\Gateway\AddressGateway;
use App\Model\Gateway\Category as CategoryGateway;
use App\Model\Gateway\City as CityGateway;
use App\Model\Gateway\State as StateGateway;
use App\Model\Property;
use App\Model\State;
use ControllerTestCase;
use Mandragora\PHPUnit\DoctrineTest\DoctrineTestInterface;

class AddressServiceTest extends ControllerTestCase implements DoctrineTestInterface
{
    public function testGetAddressForm()
    {
        $dataForm = $this->addressService->getFormForCreating('')->getErrors();
        $this->assertInternalType('array', $dataForm);
    }

    public function testIsAddressFormValidMustReturnTrue()
    {
        $state = new State(['name' => 'Puebla', 'url' => 'puebla']);
        $this->stateGateway->insert($state);
        $city = new City([
            'name' => 'Tepexi',
            'url' => 'tepexi',
            'stateId' => $state->id
        ]);
        $this->cityGateway->insert($city);
        $data = [
            'id' => 1, 'streetAndNumber' => 'priv tabacos',
            'neighborhood' => 'Ignacio Romero V ', 'state' => $state->id,
            'addressReference' => 'Antes de llegar a la salida a la autopista',
            'cityId' => $city->id, 'zipCode' => 72120, 'version' => 1,
        ];

        $this->addressService->setCities($state->id);
        $form = $this->addressService->getFormForCreating('');

        $this->assertTrue($form->isValid($data));
    }

    public function testFindAddressByPropertyId()
    {
        $state = new State(['name' => 'Puebla', 'url' => 'puebla']);
        $this->stateGateway->insert($state);
        $city = new City([
            'name' => 'Tepexi',
            'url' => 'tepexi',
            'stateId' => $state->id
        ]);
        $this->cityGateway->insert($city);
        $data = [
            'id' => 1, 'streetAndNumber' => 'priv tabacos',
            'neighborhood' => 'Ignacio Romero V ', 'state' => $state->id,
            'addressReference' => 'Antes de llegar a la salida a la autopista',
            'cityId' => $city->id, 'zipCode' => 72120, 'version' => 1,
        ];
        $address = new Address($data);
        $gateway = new AddressGateway(new AddressDao());
        $gateway->insert($address);

        $addressDB = $this->addressService->retrieveAddressById($address->id);
        $this->assertEquals($address->id, $addressDB->id);
        $this->assertEquals($address->streetAndNumber, $addressDB->streetAndNumber);
        $this->assertEquals($address->neighborhood, $addressDB->neighborhood);
        $this->assertEquals($address->cityId, $addressDB->cityId);
        $this->assertEquals($address->zipCode, $addressDB->zipCode);
    }

    public function testDeletePropertyAddress()
    {
        $state = new State(['name' => 'Puebla', 'url' => 'puebla']);
        $this->stateGateway->insert($state);
        $city = new City([
            'name' => 'Tepexi',
            'url' => 'tepexi',
            'stateId' => $state->id
        ]);
        $this->cityGateway->insert($city);
        $values = [
            'streetAndNumber' => 'Priv. Tabacos',
            'neighborhood' => 'Col. Centro',
            'zipCode' => 72120,
            'cityId' => $city->id
        ];
        $address = new Address($values);
        $gateway = new AddressGateway(new AddressDao());
        $gateway->insert($address);
        $values['zipCode'] = 78209;
        $values['id'] = $address->id;
        $form = $this->addressService->getFormForEditing('');
        $form->populate($values);

        $this->addressService->updateAddress();

        $modifiedAddress = $gateway->findOneById($address->id);
        $this->assertEquals(78209, $modifiedAddress['zipCode']);
    }

    protected function createProperty(): Property
    {
        $category = new Category([
            'name' => 'Premises',
            'url' => 'premises',
        ]);
        (new CategoryGateway(new CategoryDao()))->insert($category);
        $property = new Property([
            'name' => 'Local Comercial X', 'url' => 'www.ejemplo.com',
            'description' => 'Buena ubicación', 'price' => 'Casi regalado',
            'address' => 'Priv tabacos,Ignacio Romero V,72120,Puebla,Tepexi,México',
            'addressReference' => 'ejemplo de referencias de direccon',
            'latitude' => 120.5, 'longitude' => 457.8, 'category' => 'lands',
            'totalSurface' => 120, 'metersOffered' => 13, 'metersFront' => 10,
            'landUse' => PropertyLandUse::Commercial,
            'creationDate' => '2010-01-01', 'showOnWeb' => 1,
            'categoryId' => $category->id,
        ]);
        return $property;
    }

    /** @before */
    public function configure()
    {
        $this->addressService = (new AddressContainer())->getAddressService();

        $this->cacheManager = $this->_frontController->getParam('bootstrap')->getResource('cachemanager');
        $this->doctrineManager = $this->_frontController->getParam('bootstrap')->getResource('doctrine');

        $this->cityDao = new CityDao();
        $this->cityGateway = new CityGateway($this->cityDao);

        $this->stateDao = new StateDao();
        $this->stateGateway = new StateGateway($this->stateDao);
    }

    /** @var  \App\Service\AddressService */
    private $addressService;

    /** @var \Mandragora\Application\Doctrine\Manager */
    private $doctrineManager;

    /** @var \Zend_Application_Resource_Cachemanager */
    private $cacheManager;

    /** @var CityGateway */
    protected $cityGateway;

    /** @var CityDao */
    protected $cityDao;

    /** @var StateGateway */
    protected $stateGateway;

    /** @var StateDao */
    protected $stateDao;
}
