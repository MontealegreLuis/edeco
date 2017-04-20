<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use App\Model\Address;
use App\Model\City;
use App\Model\Dao\AddressDao;
use App\Model\Gateway\AddressGateway;
use Edeco\Fixtures\PropertiesFixture;
use Mandragora\PHPUnit\DoctrineTest\DoctrineTestInterface;
use PHPUnit\Zf\AuthenticatesUsers;
use PHPUnit\Zf\CleansUpHttpState;
use Zend_Controller_Plugin_ErrorHandler as ErrorHandler;
use Zend_View_Helper_Url as UrlHelper;

/**
 * Integration tests for AddressController class
 */
class AddressControllerTest extends ControllerTestCase implements DoctrineTestInterface
{
    use CleansUpHttpState, AuthenticatesUsers;

    /** @test */
    function it_shows_the_form_to_edit_an_address()
    {
        $this->getRequest()->setMethod('GET');
        $this->getRequest()->setParams(['id' => $this->fixture->addressId()]);
        $this->authenticateAs($this->fixture->adminUser());

        $this->dispatch($this->urlHelper->url([
            'module' => 'admin',
            'controller' => 'address',
            'action' => 'edit',
            'id' => $this->fixture->addressId(),
        ], 'controllers'));

        $this->assertController('address');
        $this->assertAction('edit');
        $this->assertModule('admin');
        $this->assertNotRedirect();
        $this->assertQuery('#address');
    }

    /** @test */
    function it_updates_an_existing_address()
    {
        $gateway = new AddressGateway(new AddressDao());
        $newValues = [
            'id' => $this->fixture->addressId(),
            'streetAndNumber' => 'juan de palafox',
            'neighborhood' => 'centro',
            'zipCode' => 72000,
            'cityId' => $this->fixture->cityId(),
            'state' => $this->fixture->stateId(),
            'version' => $this->fixture->address()['version'],
        ];
        $this->getRequest()->setMethod('POST')->setPost($newValues);
        $this->getRequest()->setParams(['id' => $this->fixture->addressId()]);
        $this->authenticateAs($this->fixture->adminUser());

        $this->dispatch(
            $this->urlHelper->url([
                'module' => 'admin',
                'controller' => 'address',
                'action' => 'update',
                'id' => $this->fixture->addressId(),
            ], 'controllers')
        );

        $savedAddress = new Address($gateway->findOneById($this->fixture->addressId()));
        $this->assertEquals($newValues['streetAndNumber'], $savedAddress->streetAndNumber);
        $this->assertEquals($newValues['neighborhood'], $savedAddress->neighborhood);
        $this->assertEquals($newValues['zipCode'], $savedAddress->zipCode);
        $this->assertEquals($newValues['cityId'], $savedAddress->City->id);
        $this->assertController('address');
        $this->assertAction('update');
        $this->assertModule('admin');
        $this->assertRedirect();
    }

    /** @before */
    function configureController(): void
    {
        !defined('PUBLIC_PATH') && define('PUBLIC_PATH', __DIR__ . '/../../../../../admin.edeco.mx');
        $this->_frontController->registerPlugin(new ErrorHandler([
            'module' => 'admin', 'controller' => 'error', 'action' => 'error'
        ]));
        $this->_frontController->setParam('noErrorHandler', true);
        $this->urlHelper = new UrlHelper();
        /** @var \Mandragora\Application\Doctrine\Manager $manager */
        $manager = $this->_frontController->getParam('bootstrap')->getResource('doctrine');
        $this->fixture = PropertiesFixture::fromDSN($manager->getConfiguration()['dsn']);
        $this->fixture->includeSecurityRows();
    }

    /** @var PropertiesFixture */
    private $fixture;

    /** @var City */
    private $city;

    /** @var UrlHelper */
    protected $urlHelper;
}
