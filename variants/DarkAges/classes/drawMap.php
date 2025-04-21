<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class DarkAgesVariant_drawMap extends drawMap
{
	protected $countryColors = array(
		0  => array(226, 198, 158), // Neutral
		1  => array(239, 196, 228), // Gaels
		2  => array(121, 175, 198), // Scots
		3  => array(164, 196, 153), // Bretons
  		4  => array(160, 138, 117), // Norse
  		5  => array(196, 143, 133), // AngloSaxons
  		6  => array(234, 234, 175), // Danes
  		7  => array(168, 126, 159), // Swedes
	);

	protected function resources() {
		if( $this->smallmap )
		{
			return array(
				'map'     =>'variants/DarkAges/resources/smallmap.png',
				'army'    =>'variants/DarkAges/resources/smallarmy.png',
				'fleet'   =>'variants/DarkAges/resources/smallfleet.png',
				'names'   =>'variants/DarkAges/resources/smallmapNames.png',
				'standoff'=>'images/icons/cross.png'
			);
		}
		else
		{
			return array(
				'map'     =>'variants/DarkAges/resources/map.png',
				'army'    =>'variants/DarkAges/resources/army.png',
				'fleet'   =>'variants/DarkAges/resources/fleet.png',
				'names'   =>'variants/DarkAges/resources/mapNames.png',
				'standoff'=>'images/icons/cross.png'
			);
		}
	}
	
	// No need to set the transparency of the unit icons
	protected function setTransparancies() { }
	
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
						 'x'=>$x+intval($this->fleet['width']/2+1),
						 'y'=>$y+intval($this->fleet['height']/2-1)
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