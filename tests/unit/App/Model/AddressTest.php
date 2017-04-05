<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace App\Model;

use ControllerTestCase;
use Edeco\Controller\Action\Helper\GoogleMaps;
use Mandragora\Geocoder\PlaceMark;

class AddressTest extends ControllerTestCase
{
    /** @test */
    function it_can_be_converted_to_string()
    {
        $this->assertEquals('priv tabacos, puebla, puebla, México', (string)$this->address);
    }

    /** @test */
    function it_can_be_converted_to_html()
    {
        $this->assertEquals(
            'priv tabacos<br /><br />puebla, puebla, México<br />C. P. 72120',
            $this->address->toHtml()
        );
    }

    /** @test */
    function it_can_be_geocoded()
    {
        //Inject action helper dependency
        $helper = new GoogleMaps();
        $helper->direct($this->getRequest());
        $dataAddress = $this->address->geocode();

        $this->assertInternalType('array', $dataAddress);
        if (count($dataAddress) > 0) {
        	$this->assertInstanceOf(PlaceMark::class, $dataAddress[0]);
        }
    }

    /** @before */
    function configureAddress(): void
    {
        $this->address = new Address([
            'streetAndNumber' => 'priv tabacos',
            'neighborhood' => null, 'zipCode' => 72120,
            'City' => ['name' => 'puebla', 'State' => ['name' => 'puebla']],
            'country' => 'México'
        ]);
    }

    /** @var Address */
    private $address;
}
