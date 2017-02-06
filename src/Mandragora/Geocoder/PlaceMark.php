<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Geocoder;

use Mandragora\Geocoder\PlaceMark\JsonFormatter;

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

    /** @var Point */
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
     * @param Point $point
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
     * @return Point
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
     * @return PlaceMark
     */
    public static function fromJson($json)
    {
        $point = Point::fromCoordinate($json->geometry->location);

        $placeMark = new self(
            $point, $json->formatted_address, self::ACCURACY_STREET
        );

        return $placeMark;
    }

    public function arrayToJson(array $placeMarkers, JsonFormatter $formater)
    {
    	return $formater->format($placeMarkers);
    }
}
