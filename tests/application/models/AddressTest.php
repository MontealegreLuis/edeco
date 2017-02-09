<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use App\Model\Address;
use Edeco\Controller\Action\Helper\GoogleMaps;
use Mandragora\Geocoder\PlaceMark;

/**
 * Unit tests for Address class
 */
class Edeco_Model_AddressTest extends ControllerTestCase
{
    /** @var Address */
    private $address;

    public function testCanConvertToString()
    {
        $values = [
	        'streetAndNumber' => 'priv tabacos',
	        'neighborhood' => null, 'zipCode' => 72120,
	        'City' => ['name' => 'puebla', 'State' => ['name' => 'puebla']],
            'country' => 'México'
        ];
        $address = new Address($values);
        $this->assertEquals('priv tabacos, puebla, puebla, México', (string) $address);
    }

    public function testCanConvertToHtml()
    {
        $data = [
	        'streetAndNumber' => 'priv tabacos',
	        'neighborhood' => null, 'zipCode' => 72120,
            'City' => ['name' => 'puebla', 'State' => ['name' => 'puebla']],
            'country' => 'México'
        ];
        $address = new Address($data);
        $this->assertEquals(
            'priv tabacos<br /><br />puebla, puebla, México<br />C. P. 72120',
            $address->toHtml()
        );
    }

    public function testCanGeocode()
    {
    	$data = [
	        'streetAndNumber' => 'priv tabacos',
	        'neighborhood' => null, 'zipCode' => 72120,
            'City' => ['name' => 'puebla', 'State' => ['name' => 'puebla']],
            'country' => 'México'
        ];
        //Inject action helper dependency
        $helper = new GoogleMaps();
        $helper->direct($this->getRequest());
        $address = new Address($data);
        $dataAddress = $address->geocode();
        $this->assertInternalType('array', $dataAddress);
        if (count($dataAddress) > 0) {
        	$this->assertInstanceOf(PlaceMark::class, $dataAddress[0]);
        }
    }
}
