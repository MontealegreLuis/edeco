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
    public function testCanConvertToString()
    {
        $address = new Address([
	        'streetAndNumber' => 'priv tabacos',
	        'neighborhood' => null, 'zipCode' => 72120,
	        'City' => ['name' => 'puebla', 'State' => ['name' => 'puebla']],
            'country' => 'México'
        ]);
        $this->assertEquals('priv tabacos, puebla, puebla, México', (string) $address);
    }

    public function testCanConvertToHtml()
    {
        $address = new Address([
	        'streetAndNumber' => 'priv tabacos',
	        'neighborhood' => null, 'zipCode' => 72120,
            'City' => ['name' => 'puebla', 'State' => ['name' => 'puebla']],
            'country' => 'México'
        ]);
        $this->assertEquals(
            'priv tabacos<br /><br />puebla, puebla, México<br />C. P. 72120',
            $address->toHtml()
        );
    }

    public function testCanGeocode()
    {
        //Inject action helper dependency
        $helper = new GoogleMaps();
        $helper->direct($this->getRequest());
        $address = new Address([
            'streetAndNumber' => 'priv tabacos',
            'neighborhood' => null, 'zipCode' => 72120,
            'City' => ['name' => 'puebla', 'State' => ['name' => 'puebla']],
            'country' => 'México'
        ]);
        $dataAddress = $address->geocode();

        $this->assertInternalType('array', $dataAddress);
        if (count($dataAddress) > 0) {
        	$this->assertInstanceOf(PlaceMark::class, $dataAddress[0]);
        }
    }
}
