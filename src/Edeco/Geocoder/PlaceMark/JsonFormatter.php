<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Edeco\Geocoder\PlaceMark;

use Mandragora\Geocoder\PlaceMark\JsonFormatter as BaseFormatter;
use Zend_Json as Json;

class JsonFormatter implements BaseFormatter
{
    /**
     * @see \Mandragora\Geocoder\PlaceMark\JsonFormatter::format()
     */
	public function format(array $placeMarkers)
	{
	    $jsonPlaceMarkers = [];
	    foreach ($placeMarkers as $placeMark) {
	    	$json = [];
            $json['name'] = ' ';
            $json['Address'] = $placeMark->getAddress();
            $json['latitude'] = $placeMark->getPoint()->getLatitude();
            $json['longitude'] = $placeMark->getPoint()->getLongitude();
            $jsonPlaceMarkers[] = $json;
	    }
		return Json::encode($jsonPlaceMarkers);
	}
}