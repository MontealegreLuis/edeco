<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Geocoder;

use RuntimeException;
use Zend_Http_Client as HttpClient;
use Zend_Http_Client_Adapter_Curl as CurlAdapter;
use Zend_Json_Decoder as JsonDecoder;
use Zend_Json as Json;
use stdClass;

class Adapter
{
    const OK = 'OK';
    const REQUEST_DENIED = 'REQUEST_DENIED';

    /** @var string */
    protected $apiKey;

    /**
     * @param string $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = (string) $apiKey;
    }

    /**
     * @param string $address
     * @return array
     * @throws \Zend_Json_Exception Id the response is not valid JSON
     * @throws \Zend_Http_Client_Exception If it cannot connect to Google
     * @throws RuntimeException If no recognized status is returned
     */
    public function lookup($address)
    {
        $client = new HttpClient();
        $client->setAdapter((new CurlAdapter())->setConfig([CURLOPT_FOLLOWLOCATION => true]));
        $client
            ->setUri($this->getGeocodeUri())
            ->setParameterGet('address', (string) $address)
            ->setParameterGet('output', 'json')
            ->setParameterGet('sensor', 'false')
            ->setParameterGet('key', $this->apiKey)
        ;
        $result = $client->request('GET');
        $response = JsonDecoder::decode($result->getBody(), Json::TYPE_OBJECT);

        $status = self::REQUEST_DENIED;
        if ($response instanceof stdClass) {
            $status = $response->status;
        }

        if ($status === self::OK) {
            $placeMarks = [];
            foreach ($response->results as $placeMark) {
                $placeMarks[] = PlaceMark::fromJson($placeMark);
            }
            return $placeMarks;
        }

        throw new RuntimeException(sprintf(
            'Google Geocode error %s occurred: %s',
            $status,
            $response->error_message
        ));
    }

    /**
     * @return string
     */
    protected function getGeocodeUri()
    {
        return 'https://maps.google.com/maps/api/geocode/json';
    }
}
