<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class CustomIcons_drawmap extends drawmap
{
	// Arrays for the custom icons:
	protected $unit_c =array(); // An array to store the owner of each territory
	protected $army_c =array(); // Custom army icons
	protected $fleet_c=array(); // Custom fleet icons

	// Load the custom icons...
	protected function loadImages()
	{
		global $Variant;

		$this->army_c[0]  = $this->loadImage('contrib/'.($this->smallmap ? 'small' : '' ).'army.png');
		$this->fleet_c[0] = $this->loadImage('contrib/'.($this->smallmap ? 'small' : '' ).'fleet.png');
		for ($i=1; $i<=count($Variant->countries); $i++) {
			$this->army_c[$i]  = $this->loadImage('variants/'.$Variant->name.'/resources/'.($this->smallmap ? 'small' : '' ).'army' .$Variant->countries[$i-1].'.png');
			$this->fleet_c[$i] = $this->loadImage('variants/'.$Variant->name.'/resources/'.($this->smallmap ? 'small' : '' ).'fleet'.$Variant->countries[$i-1].'.png');
		}
		parent::loadImages();
	}

	// Save the countryID for every colored Territory (and their coasts)
	public function colorTerritory($terrID, $countryID)
	{
		$this->unit_c[$terrID]=$countryID;
		foreach (preg_grep( "/^".$this->territoryNames[$terrID].".* Coast.$/", $this->territoryNames) as  $id=>$name)
			$this->unit_c[$id]=$countryID;
		parent::colorTerritory($terrID, $countryID);
	}

	// Overwrite the country if a unit needs to draw a flag (and don't draw the flag) -> we use custom icons instead
	public function countryFlag($terrName, $countryID)
	{
		$this->unit_c[$terrName]=$countryID;
	}

	// Draw the custom icons:
	public function addUnit($terrName, $unitType)
	{
		$this->army  = $this->army_c[$this->unit_c[$terrName]];
		$this->fleet = $this->fleet_c[$this->unit_c[$terrName]];
		parent::addUnit($terrName, $unitType);
	}

}

class ChesspolitikVariant_drawMap extends CustomIcons_drawmap {

	protected $countryColors = array(
		0 => array(226, 198, 158), // Neutral
		1 => array(121, 175, 198), // Russians
		2 => array(196, 107, 119), // Ottomans
		3 => array(177, 176,  99), // Mughals
		4 => array(245, 211, 118)  // Chinese
	);

	protected function resources() {

		global $Variant;
		$prefix = ( ($this->smallmap) ? 'small' : '');

		return array(
			'map'     =>'variants/'.$Variant->name.'/resources/'.$prefix.'map.png',
			'names'   =>'variants/'.$Variant->name.'/resources/'.$prefix.'mapNames.png',
			'army'    =>'contrib/'.$prefix.'army.png',
			'fleet'   =>'contrib/'.$prefix.'fleet.png',
			'standoff'=>'images/icons/cross.png'
		);
	}
}
?>