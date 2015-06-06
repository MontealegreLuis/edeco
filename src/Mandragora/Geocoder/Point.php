<?php
class Mandragora_Geocoder_Point
{
    /**
     * @var float
     */
    protected $latitude;

    /**
     * @var float
     */
    protected $longitude;

    /**
     * @param float $latitude
     * @param float $longitude
     */
    protected function __construct($latitude, $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param string $json
     * @return Mandragora_Geocoder_Point
     */
    public static function fromCoordinate(array $coordinate)
    {
        return new self($coordinate[1], $coordinate[0]);
    }

}