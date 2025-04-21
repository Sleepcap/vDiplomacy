<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class ChromaticVariant_drawMap extends drawMap {

	protected $countryColors = array(
		0 =>  array(226, 198, 158), // Neutral
		1 =>  array(121, 175, 198), // Blue
		2 =>  array(234, 234, 175), // Yellow
		3 =>  array(127, 127, 127), // Dark
		4 =>  array(235, 235, 235), // Light
		5 =>  array(196, 143, 133)  // Red
	);

	protected function resources() {
		if( $this->smallmap )
		{
			return array(
				'map'=>'variants/Chromatic/resources/smallmap.png',
				'army'=>'contrib/smallarmy.png',
				'fleet'=>'contrib/smallfleet.png',
				'names'=>'variants/Chromatic/resources/smallmapNames.png',
				'standoff'=>'images/icons/cross.png'
			);
		}
		else
		{
			return array(
				'map'=>'variants/Chromatic/resources/map.png',
				'army'=>'contrib/army.png',
				'fleet'=>'contrib/fleet.png',
				'names'=>'variants/Chromatic/resources/mapNames.png',
				'standoff'=>'images/icons/cross.png'
			);
		}
	}
}

?>