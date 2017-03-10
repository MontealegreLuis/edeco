<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use App\Container\AddressContainer;
use App\Model\Address;
use App\Model\City;
use App\Model\Dao\AddressDao;
use App\Model\Dao\CityDao;
use App\Model\Dao\StateDao;
use App\Model\Gateway\AddressGateway;
use App\Model\Gateway\City as CityGateway;
use App\Model\Gateway\State as StateGateway;
use App\Model\State;
use ControllerTestCase;
use Mandragora\PHPUnit\DoctrineTest\DoctrineTestInterface;

class AddressServiceTest extends ControllerTestCase implements DoctrineTestInterface
{
    /** @test */
    function it_configures_the_form_for_creating()
    {
        $errors = $this->addressService->getFormForCreating('', ['id' => 1])->getErrors();
        $this->assertInternalType('array', $errors);
    }

    /** @test */
    function it_passes_form_validation_if_input_is_valid()
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

        $form = $this->addressService->getFormForCreating('', $data);

        $this->assertTrue($form->isValid($data));
    }

    /** @test */
    function it_finds_an_address_by_its_property_id()
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

    /** @test */
    function it_updates_an_address()
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
            'cityId' => $city->id,
            'state' => $city->State->id
        ];
        $address = new Address($values);
        $gateway = new AddressGateway(new AddressDao());
        $gateway->insert($address);
        $values['zipCode'] = 78209;
        $values['id'] = $address->id;
        $form = $this->addressService->getFormForEditing('', $values);
        $form->populate($values);

        $this->addressService->updateAddress();

        $modifiedAddress = $gateway->findOneById($address->id);
        $this->assertEquals(78209, $modifiedAddress['zipCode']);
    }

    /** @before */
    function configure()
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
