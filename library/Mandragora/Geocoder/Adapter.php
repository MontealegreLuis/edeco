<?php
class Mandragora_Geocoder_Adapter
{
    const SUCCESS = 200;
    const BAD_REQUEST = 400;
    const SERVER_ERROR = 500;
    const MISSING_QUERY = 601;
    const MISSING_ADDRESS = 601;
    const UNKNOWN_ADDRESS = 602;
    const UNAVAILABLE_ADDRESS = 603;
    const UNKNOWN_DIRECTIONS = 604;
    const BAD_KEY = 610;
    const TOO_MANY_QUERIES = 620;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @param string $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = (string)$apiKey;
    }

    /**
     * @param string $address
     * @return array
     */
    public function lookup($address)
    {
        $client = new Zend_Http_Client();
        $client->setUri($this->getGeocodeUri());
        $client->setParameterGet('q', (string)$address)
               ->setParameterGet('output', 'json')
               ->setParameterGet('sensor', 'false')
               ->setParameterGet('key', $this->apiKey);

        $result = $client->request('GET');

        $response = Zend_Json_Decoder::decode(
            $result->getBody(), Zend_Json::TYPE_OBJECT
        );

        if ($response instanceof stdClass) {
            $status = $response->Status->code;
        }

        switch ($status) {

            case self::SUCCESS:

                $placemarks = array();
                foreach ($response->Placemark as $placemark) {

                    $placemarks[] = Mandragora_Geocoder_PlaceMark::fromJson(
                        $placemark
                    );
                }
                return $placemarks;

            case self::UNKNOWN_ADDRESS:
            case self::UNAVAILABLE_ADDRESS:
                return array();
            default:
                throw new Exception(
                    sprintf('Google Geocode error %d occurred', $status)
                );
        }
    }

    /**
     * @return string
     */
    protected function getGeocodeUri()
    {
        return 'http://maps.google.com/maps/geo';
    }

}