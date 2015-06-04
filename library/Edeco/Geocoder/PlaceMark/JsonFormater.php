<?php
class      Edeco_Geocoder_PlaceMark_JsonFormater
implements Mandragora_Geocoder_PlaceMark_JsonFormater
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