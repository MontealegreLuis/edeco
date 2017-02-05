<?php


namespace Mandragora\Geocoder\PlaceMark;



interface Mandragora_Geocoder_PlaceMark_JsonFormater
{
	public function format(array $placeMarkers);
}