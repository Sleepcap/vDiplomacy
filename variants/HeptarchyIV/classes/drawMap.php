<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class HeptarchyIVVariant_drawMap extends drawMap {

	protected $countryColors = array(
		0 => array(226, 198, 158), // Neutral
		1 => array(239, 196, 228), // Anglia
		2 => array(121, 175, 198), // Cornubia
		3 => array(164, 196, 153), // Ireland
		4 => array(160, 138, 117), // Mercia
		5 => array(196, 143, 133), // Northumbria
		6 => array(234, 234, 175), // Scotland
		7 => array(168, 126, 159)  // Wales
	);
	
	public function __construct($smallmap)
	{
		// Map is too big, so up the memory-limit
		parent::__construct($smallmap);
		if ( !$this->smallmap )
			ini_set('memory_limit',"32M");
	}

	// Move the country-flag behind the countries
	public function countryFlag($terrID, $countryID)
	{
		
		$flagBlackback = $this->color(array(0, 0, 0));
		$flagColor = $this->color($this->countryColors[$countryID]);

		list($x, $y) = $this->territoryPositions[$terrID];

		$coordinates = array(
			'top-left' => array( 
						 'x'=>$x-intval($this->fleet['width']/2+1),
						 'y'=>$y-intval($this->fleet['height']/2+1)
						 ),
			'bottom-right' => array(
						 'x'=>$x+intval($this->fleet['width']/2+1)+1,
						 'y'=>$y+intval($this->fleet['height']/2-1)+1
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
	
	// No need to set transparency. Icans have transparent background
	protected function setTransparancies() {}	
	
	protected function resources() {
		if( $this->smallmap )
		{
			return array(
				'map'=>'variants/HeptarchyIV/resources/smallmap.png',
				'army'=>'variants/HeptarchyIV/resources/smallarmy.png',
				'fleet'=>'variants/HeptarchyIV/resources/smallfleet.png',
				'names'=>'variants/HeptarchyIV/resources/smallmapNames.png',
				'standoff'=>'images/icons/cross.png'
			);
		}
		else
		{
			return array(
				'map'=>'variants/HeptarchyIV/resources/map.png',
				'army'=>'variants/HeptarchyIV/resources/army.png',
				'fleet'=>'variants/HeptarchyIV/resources/fleet.png',
				'names'=>'variants/HeptarchyIV/resources/mapNames.png',
				'standoff'=>'images/icons/cross.png'
			);
		}
	}
}

?>