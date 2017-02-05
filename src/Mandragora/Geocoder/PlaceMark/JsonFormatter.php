<?php
/**
 * PHP version 5
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Mandragora\Geocoder\PlaceMark;

interface JsonFormatter
{
	public function format(array $placeMarkers);
}