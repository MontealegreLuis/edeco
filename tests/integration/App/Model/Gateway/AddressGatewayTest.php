<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model\Gateway;

use App\Model\Dao\AddressDao;
use Edeco\Fixtures\PropertiesFixture;
use ControllerTestCase;
use Mandragora\Gateway\NoResultsFoundException;

class AddressGatewayTest extends ControllerTestCase
{
    /** @test */
    function it_finds_a_existing_property()
    {
        $existingAddress = $this->fixture->address();
        $foundAddress = $this->addressGateway->findOneById($this->fixture->addressId());

        $this->assertEquals($existingAddress['id'], $foundAddress['id']);
        $this->assertEquals($existingAddress['streetAndNumber'], $foundAddress['streetAndNumber']);
        $this->assertEquals($existingAddress['neighborhood'], $foundAddress['neighborhood']);
        $this->assertEquals($existingAddress['addressReference'], $foundAddress['addressReference']);
        $this->assertEquals($existingAddress['cityId'], $foundAddress['cityId']);
        $this->assertEquals($existingAddress['zipCode'], $foundAddress['zipCode']);
        $this->assertEquals($existingAddress['version'], $foundAddress['version']);
    }

    /** @test */
    function it_throws_exception_when_trying_to_find_an_unknown_address()
    {
        $this->expectException(NoResultsFoundException::class);

        $this->addressGateway->findOneById(-1);
    }

    /** @test */
    function it_saves_the_geo_location_of_a_given_address()
    {
        $latitude = 29.42;
        $longitude = -98.49;
        $location = ['latitude' => $latitude, 'longitude' => $longitude];

        $this->addressGateway->saveGeoPosition($this->fixture->addressId(), $location);

        $updatedAddress = $this->addressGateway->findOneById($this->fixture->addressId());
        $this->assertEquals($latitude, $updatedAddress['latitude']);
        $this->assertEquals($longitude, $updatedAddress['longitude']);
    }

    /** @before */
    function loadFixture(): void
    {
        /** @var \Mandragora\Application\Doctrine\Manager $manager */
        $manager = $this->_frontController->getParam('bootstrap')->getResource('doctrine');
        $this->fixture = PropertiesFixture::fromDSN($manager->getConfiguration()['dsn']);
        $this->addressGateway = new AddressGateway(new AddressDao());
    }

    /** @var AddressGateway */
    private $addressGateway;

    /** @var PropertiesFixture */
    private $fixture;
}
