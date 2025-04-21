<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class MoveFlags_drawMap extends drawMap
{
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
						 'x'=>$x+intval($this->fleet['width']/2+2),
						 'y'=>$y+intval($this->fleet['height']/2+1)
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

class WesternEurope1300Variant_drawMap extends MoveFlags_drawMap {

	protected $countryColors = array(
		0 => array(159, 167, 169), // Neutral
		1 => array(136, 167, 221), // France
		2 => array(157, 235, 175), // England
		3 => array(216, 209, 103), // Castile
		4 => array(210, 169,  77), // Aragon
		5 => array(188, 128, 153)  // Burgundy
	);

	protected function resources() {

		global $Variant;
		$prefix = ( ($this->smallmap) ? 'small' : '');

		return array(
			'map'     =>'variants/'.$Variant->name.'/resources/'.$prefix.'map.png',
			'names'   =>'variants/'.$Variant->name.'/resources/'.$prefix.'mapNames.png',
			'army'    =>'variants/'.$Variant->name.'/resources/'.$prefix.'army.png',
			'fleet'   =>'variants/'.$Variant->name.'/resources/'.$prefix.'fleet.png',
			'standoff'=>'images/icons/cross.png'
		);
	}

	// No need to set transparency. Icans have transparent background
	protected function setTransparancies() {}

}
?>