<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use App\Container\AddressContainer;
use App\Model\Address;
use App\Service\Address as AddressService;
use PHPUnit_Framework_TestCase as TestCase;

class AddressServiceTest extends TestCase
{
    /** @test */
    function it_can_create_an_address_model()
    {
        $this->markTestSkipped('Add proper tests for this service now that it has a container');
        $this->assertInstanceOf(Address::class, $this->addressService->getModel());
    }

    /** @ before */
    function createService()
    {
        $this->addressService = (new AddressContainer())->getAddressService();
    }

    /** @var AddressService */
    private $addressService;
}
