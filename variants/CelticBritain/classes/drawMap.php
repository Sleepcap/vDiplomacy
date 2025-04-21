<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class CelticBritainVariant_drawMap extends drawMap {

	public function __construct($smallmap)
	{
		// Map is too big, so up the memory-limit
		parent::__construct($smallmap);
		if ( !$this->smallmap )
			ini_set('memory_limit',"32M");
	}

	protected $countryColors = array(
		0 => array(211, 249, 188),
		1 => array(255, 126,   0), // Brigantes
		2 => array(111,  49, 152), // Iceni
		3 => array(  0, 183, 239), // Caledonii
		4 => array( 67,  72, 224), // Picts
		5 => array( 34, 117,  76), // Cornovii
		6 => array(168, 230,  29), // Ivernia
		7 => array(254, 162, 176), // Voluntii
		8 => array(153,   0,  48), // Durotriges
	);
	
	// No need to set the transparency for our custom icons and mapnames.
	protected function setTransparancy(array $image, array $color=array(255,255,255)) {}

	protected function resources() {
		if( $this->smallmap )
		{
			return array(
				'map'     =>'variants/CelticBritain/resources/smallmap.png',
				'army'    =>'variants/CelticBritain/resources/SmallArmy.png',
				'fleet'   =>'variants/CelticBritain/resources/SmallFleet.png',
				'names'   =>'variants/CelticBritain/resources/smallmapNames.png',
				'standoff'=>'images/icons/cross.png'
			);
		}
		else
		{
			return array(
				'map'     =>'variants/CelticBritain/resources/map.png',
				'army'    =>'variants/CelticBritain/resources/Army.png',
				'fleet'   =>'variants/CelticBritain/resources/Fleet.png',
				'names'   =>'variants/CelticBritain/resources/mapNames.png',
				'standoff'=>'images/icons/cross.png'
			);
		}
	}
	
	// Draw the flags behind the units for a better readability
	public function countryFlag($terrName, $countryID)
	{
		$flagBlackback = $this->color(array(0, 0, 0));

		$flagColor = $this->color($this->countryColors[$countryID]);

		list($x, $y) = $this->territoryPositions[$terrName];

		$coordinates = array(
			'top-left' => array( 
							'x'=>$x-intval($this->fleet['width']/2+2),
							'y'=>$y-intval($this->fleet['height']/2+2)-1
							),
			'bottom-right' => array(
							'x'=>$x+intval($this->fleet['width']/2+2)-1,
							'y'=>$y+intval($this->fleet['height']/2+2)+1
							)
			);

		imagefilledrectangle($this->map['image'],
			$coordinates['top-left']['x'], $coordinates['top-left']['y'],
			$coordinates['bottom-right']['x'], $coordinates['bottom-right']['y'],
			$flagBlackback);
		imagefilledrectangle($this->map['image'],
			$coordinates['top-left']['x']+1, $coordinates['top-left']['y']+1,
			$coordinates['bottom-right']['x']-1, $coordinates['bottom-right']['y']-1,
			$flagColor);
	}
}

?>