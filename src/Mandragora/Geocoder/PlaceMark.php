<?php


namespace Mandragora\Geocoder;

use Mandragora\Geocoder\Point;
use Mandragora\Geocoder\PlaceMark\JsonFormater;


class PlaceMark
{
    const ACCURACY_UNKNOWN = 0;
    const ACCURACY_COUNTRY = 1;
    const ACCURACY_REGION = 2;
    const ACCURACY_SUBREGION = 3;
    const ACCURACY_TOWN = 4;
    const ACCURACY_POSTCODE = 5;
    const ACCURACY_STREET = 6;
    const ACCURACY_INTERSECTION = 7;
    const ACCURACY_ADDRESS = 8;

    /**
     * @var Mandragora_Geocoder_Point
     */
    protected $point;

    /**
     * @var string
     */
    protected $address;

    /**
     * @var int
     */
    protected $accuracy;

    /**
     * @param Mandragora_Geocoder_Point $point
     * @param string $address
     * @param int $accuracy
     */
    protected function __construct(
        Point $point, $address, $accuracy
    )
    {
        $this->point = $point;
        $this->address = $address;
        $this->accuracy = $accuracy;
    }

    /**
     * @return Mandragora_Geocoder_Point
     */
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return int
     */
    public function getAccuracy()
    {
        return $this->accuracy;
    }

    /**
     * @param string $json
     * @return Mandragora_Geocoder_PlaceMark
     */
    public static function fromJson($json)
    {
        $point = Point::fromCoordinate(
            $json->Point->coordinates
        );

        $placemark = new self(
            $point, $json->address, $json->AddressDetails->Accuracy
        );

        return $placemark;
    }

    public function arrayToJson(array $placeMarkers, JsonFormater $formater)
    {
    	return $formater->format($placeMarkers);
    }
    
}