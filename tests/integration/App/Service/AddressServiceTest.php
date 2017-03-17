<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use App\Container\AddressContainer;
use App\Form\Address\Detail;
use App\Model\Address;
use App\Model\Dao\AddressDao;
use App\Model\Gateway\AddressGateway;
use ControllerTestCase;
use Edeco\Fixtures\PropertiesFixture;
use Mandragora\PHPUnit\DoctrineTest\DoctrineTestInterface;

class AddressServiceTest extends ControllerTestCase implements DoctrineTestInterface
{
    /** @test */
    function it_configures_the_form_for_creating()
    {
        $form = $this->addressService->getFormForCreating('', [
            'id' => $this->fixture->addressId()
        ]);

        /** @var \Zend_Form_Element_Select $states */
        $states = $form->getElement('state');
        /** @var \Zend_Form_Element_Select $cities */
        $cities = $form->getElement('cityId');

        $this->assertCount(2, $states->getMultiOptions());
        $this->assertCount(1, $cities->getMultiOptions());
        $this->assertInstanceOf(Detail::class, $form);
    }

    /** @test */
    function it_passes_form_validation_if_input_is_valid()
    {
        $data = [
            'id' => 1, 'streetAndNumber' => 'priv tabacos',
            'neighborhood' => 'Ignacio Romero V ', 'state' => $this->fixture->stateId(),
            'addressReference' => 'Antes de llegar a la salida a la autopista',
            'cityId' => $this->fixture->cityId(), 'zipCode' => 72120, 'version' => 1,
        ];

        $form = $this->addressService->getFormForCreating('', $data);

        $this->assertTrue($form->isValid($data));
    }

    /** @test */
    function it_finds_an_address_by_its_property_id()
    {
        $address = new Address($this->fixture->address());

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
        $gateway = new AddressGateway(new AddressDao());
        $existingAddress = $this->fixture->address();
        $newZipCode = 78209;
        $existingAddress['zipCode'] = $newZipCode;

        $form = $this->addressService->getFormForEditing('', $existingAddress);
        $form->populate($existingAddress);
        $this->addressService->updateAddress();

        $modifiedAddress = $gateway->findOneById($this->fixture->addressId());
        $this->assertEquals($newZipCode, $modifiedAddress['zipCode']);
    }

    /** @before */
    function configure()
    {
        $this->addressService = (new AddressContainer())->getAddressService();
        /** @var \Mandragora\Application\Doctrine\Manager $manager */
        $manager = $this->_frontController->getParam('bootstrap')->getResource('doctrine');
        $this->fixture = PropertiesFixture::fromDSN($manager->getConfiguration()['dsn']);
    }

    /** @var  \App\Service\AddressService */
    private $addressService;

    /** @var PropertiesFixture */
    private $fixture;
}
