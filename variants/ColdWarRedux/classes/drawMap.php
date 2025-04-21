<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class ColdWarReduxVariant_drawMap extends drawMap {

	protected $countryColors = array(
		0 => array(226, 198, 158), // Neutral
		1 => array(190,   7,   7), // USSR
		2 => array( 60, 188, 216), // NATO
		3 => array(123,  94, 255), // USA
		4 => array(255, 106,   0)  // PRC
	);

	protected function resources() {
		if( $this->smallmap )
		{
			return array(
				'map'=>'variants/ColdWarRedux/resources/smallmap.png',
				'army'=>'contrib/smallarmy.png',
				'fleet'=>'contrib/smallfleet.png',
				'names'=>'variants/ColdWarRedux/resources/smallmapNames.png',
				'standoff'=>'images/icons/cross.png'
			);
		}
		else
		{
			return array(
				'map'=>'variants/ColdWarRedux/resources/map.png',
				'army'=>'contrib/army.png',
				'fleet'=>'contrib/fleet.png',
				'names'=>'variants/ColdWarRedux/resources/mapNames.png',
				'standoff'=>'images/icons/cross.png'
			);
		}
	}
}

?>