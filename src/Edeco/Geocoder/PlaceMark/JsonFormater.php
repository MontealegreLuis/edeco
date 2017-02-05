<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Edeco\Geocoder\PlaceMark;

use Mandragora\Geocoder\PlaceMark\JsonFormatter as BaseFormatter;
use Zend_Json;

class JsonFormatter implements BaseFormatter
{
    /**
     * @see Mandragora_Geocoder_PlaceMark_JsonFormater::format()
     */
	public function format(array $placeMarkers)
	{
	    $jsonPlaceMarkers = array();
	    foreach ($placeMarkers as $placeMark) {
	    	$json = array();
            $json['name'] = ' ';
            $json['Address'] = $placeMark->getAddress();
            $json['latitude'] = $placeMark->getPoint()->getLatitude();
            $json['longitude'] = $placeMark->getPoint()->getLongitude();
            $jsonPlaceMarkers[] = $json;
	    }
		return Zend_Json::encode($jsonPlaceMarkers);
	}
}