<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Service;

use App\Model\Address;
use App\Service\Address as AddressService;
use PHPUnit_Framework_TestCase as TestCase;

class AddressTest extends TestCase
{
    /** @test */
    function it_can_create_an_address_model()
    {
        $this->assertInstanceOf(Address::class, $this->addressService->getModel());
    }

    /** @before */
    function createService()
    {
        $this->addressService = new AddressService('Address');
    }

    /** @var AddressService */
    private $addressService;
}
