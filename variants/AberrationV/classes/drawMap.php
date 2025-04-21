<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class AberrationVVariant_drawMap extends drawMap {

	protected $countryColors = array(
		0  => array(226, 198, 158), /* Neutral   */
		1  => array(121, 175, 198), /* Burgundy */
		2  => array(234, 234, 175), /* Byzantium */
		3  => array(196, 143, 133), /* Hungary */
		4  => array(239, 196, 228), /* Ireland */
		5  => array(160, 138, 117), /* Israel */
		6  => array(120, 120, 120), /* Poland */
		7  => array(164, 196, 153), /* Sicily */
		8  => array(206, 153, 103), /* Spain */
		9  => array(168, 126, 159), /* Ukraine */
	);
	
	public function __construct($smallmap)
	{
		// Map is too big, so up the memory-limit
		parent::__construct($smallmap);
		if ( !$this->smallmap )
			ini_set('memory_limit',"24M");
	}

	protected function resources() {
		if( $this->smallmap )
		{
			return array(
				'map'=>'variants/AberrationV/resources/smallmap.png',
				'army'=>'contrib/smallarmy.png',
				'fleet'=>'contrib/smallfleet.png',
				'names'=>'variants/AberrationV/resources/smallmapNames.png',
				'standoff'=>'images/icons/cross.png'
			);
		}
		else
		{
			return array(
				'map'=>'variants/AberrationV/resources/map.png',
				'army'=>'contrib/army.png',
				'fleet'=>'contrib/fleet.png',
				'names'=>'variants/AberrationV/resources/mapNames.png',
				'standoff'=>'images/icons/cross.png'
			);
		}
	}
}

?>