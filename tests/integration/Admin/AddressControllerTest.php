<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use App\Model\Address;
use App\Model\City;
use App\Model\Dao\AddressDao;
use App\Model\Dao\CityDao;
use App\Model\Dao\StateDao;
use App\Model\Gateway\AddressGateway;
use App\Model\Gateway\City as CityGateway;
use App\Model\Gateway\State as StateGateway;
use App\Model\State;
use App\Model\User;
use Doctrine_Manager as Manager;
use Mandragora\PHPUnit\DoctrineTest\DoctrineTestInterface;
use Zend_Controller_Plugin_ErrorHandler as ErrorHandler;
use Zend_View_Helper_Url as UrlHelper;

/**
 * Unit tests for AddressController class
 */
class AddressControllerTest extends ControllerTestCase implements DoctrineTestInterface
{
    /** @var City */
    private $city;

    /**
     * @var Zend_View_Helper_Url
     */
    protected $urlHelper;

    /**
     * Setup application to run test cases
     *
     * @see tests/application/ControllerTestCase#setUp()
     */
    public function setUp()
    {
        parent::setUp();
        defined('PUBLIC_PATH') || define('PUBLIC_PATH', __DIR__ . '/../../../../../admin.edeco.mx');
        $this->_frontController->registerPlugin(new ErrorHandler([
            'module' => 'admin', 'controller' => 'error', 'action' => 'error'
        ]));
        $this->_frontController->setParam('noErrorHandler', true);
        $this->urlHelper = new UrlHelper();
        $this->saveCity();
        $connection = Manager::connection();
        $handler = $connection->getDbh();
        $handler->query(<<<QUERY
    INSERT INTO `resource` (`name`) VALUES
        ('*'),
        ('admin_address'),
        ('admin_category'),
        ('admin_city'),
        ('admin_error'),
        ('admin_excel'),
        ('admin_google-map'),
        ('admin_help'),
        ('admin_image'),
        ('admin_index'),
        ('admin_picture'),
        ('admin_project'),
        ('admin_property'),
        ('admin_user'),
        ('default_error'),
        ('default_index'),
        ('default_javascript'),
        ('default_property'),
        ('help_index'),
        ('help_property'),
        ('help_user')
QUERY
        );
        $handler->query(<<<QUERY
INSERT INTO `role` (`name`, `parentRole`) VALUES
    ('admin', NULL),
    ('client', 'guest'),
    ('guest', NULL)
QUERY
        );
        $handler->query(<<<QUERY
INSERT INTO `permission` (`name`, `roleName`, `resourceName`) VALUES
    ('*', 'admin', '*'),
    ('download', 'client', 'admin_project'),
    ('list', 'client', 'admin_project'),
    ('logout', 'client', 'admin_index'),
    ('show', 'client', 'admin_project'),
    ('*', 'guest', 'admin_error'),
    ('*', 'guest', 'default_error'),
    ('*', 'guest', 'default_index'),
    ('*', 'guest', 'default_javascript'),
    ('*', 'guest', 'default_property'),
    ('authenticate', 'guest', 'admin_index'),
    ('confirm', 'guest', 'admin_user'),
    ('index', 'guest', 'admin_index'),
    ('login', 'guest', 'admin_index')
QUERY
);
    }

    /**
     * Reset request
     */
    public function tearDown() {
        $this->resetRequest();
        $this->resetResponse();
        parent::tearDown();
    }

    public function testUpdateActionChangeAddress()
    {
    	$address = $this->createAddress($this->city);
        $gateway = new AddressGateway(new AddressDao());
        $gateway->insert($address);

        $this->getRequest()->setMethod('GET');
    	$this->getRequest()->setParams(['id' => $address->id]);
    	$this->doLogin();
        $this->dispatch($this->urlHelper->url([
            'module' => 'admin',
            'controller' => 'address',
            'action' => 'edit',
            'id' => $address->id
        ], 'controllers'));
        $this->assertController('address');
        $this->assertAction('edit');
        $this->assertModule('admin');
        $this->assertNotRedirect();
        $this->assertQuery('#address');
    }

    public function testAddActionInsertsANewAddressForProperty()
    {
        $address = $this->createAddress($this->city);
        $gateway = new AddressGateway(new AddressDao());
        $gateway->insert($address);

        $newValues = [
            'id' => $address->id,
            'streetAndNumber' => 'juan de palafox',
            'neighborhood' => 'centro',
            'zipCode' => 72000,
            'cityId' => $this->city->id,
            'state' => $this->city->stateId,
            'version' => $address->version,
        ];
        $this->getRequest()->setMethod('POST')->setPost($newValues);
        $this->getRequest()->setParams(['id' => $address->id]);
        $this->doLogin();
        $this->dispatch(
            $this->urlHelper->url([
                'module' => 'admin',
                'controller' => 'address',
                'action' => 'update',
                'id' => $address->id,
            ], 'controllers')
        );
        $savedAddress = new Address($gateway->findOneById($address->id));
        $this->assertEquals($newValues['streetAndNumber'], $savedAddress->streetAndNumber);
        $this->assertEquals($newValues['neighborhood'], $savedAddress->neighborhood);
        $this->assertEquals($newValues['zipCode'], $savedAddress->zipCode);
        $this->assertEquals($newValues['cityId'], $savedAddress->City->id);
        $this->assertController('address');
        $this->assertAction('update');
        $this->assertModule('admin');
        $this->assertRedirect();
    }

    protected function doLogin($identity  = null )
    {
        if ( $identity === null ) {
            $identity = $this->createUser();
        }

        Zend_Auth::getInstance()->getStorage()->write($identity);
    }

    public function createUser(): User
    {
        return new User([
            'username' => 'x0_nonoal_x0@gmail.com',
            'password' => 'lemuel', 'state' => 'active',
            'roleName' => 'admin',
        ]);
    }

    protected function createAddress(City $city): Address
    {
        return new Address([
            'id' => 1,
            'streetAndNumber' => 'Priv. Tabacos',
            'neighborhood' => 'La Noria', 'zipCode' => 72120,
            'cityId' => $city->id,
        ]);
    }

    private function saveCity(): void
    {
        $state = new State([
            'name' => 'Puebla',
            'url' => 'puebla',
        ]);
        (new StateGateway(new StateDao()))->insert($state);
        $this->city = new City([
            'name' => 'Puebla', 'url' => 'puebla', 'stateId' => $state->id,
        ]);
        (new CityGateway(new CityDao()))->insert($this->city);
    }
}
