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
		global $Game;
		for ($i=0; $i<=10; $i++)
		{
			$this->army_c[$i]  = $this->loadImage('variants/Napoleonic/resources/'.($this->smallmap ? 'small' : '').'army' .$i.'.png');
			$this->fleet_c[$i] = $this->loadImage('variants/Napoleonic/resources/'.($this->smallmap ? 'small' : '').'fleet'.$i.'.png');
		}
		parent::loadImages();
	}
	
	// Save the countryID for every colored Territory (and their coasts)
	public function colorTerritory($terrID, $countryID)
	{
		$this->unit_c[$terrID]=$countryID;
		foreach (preg_grep( "/^".$this->territoryNames[$terrID].".* Coast\)$/", $this->territoryNames) as  $id=>$name)
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
		if (strpos($this->territoryNames[$terrName],' (fake)')) return;
		$this->army  = $this->army_c[$this->unit_c[$terrName]];
		$this->fleet = $this->fleet_c[$this->unit_c[$terrName]];
		parent::addUnit($terrName, $unitType);
	}

}

class NapoleonicVariant_drawMap extends CustomIcons_drawmap {

	protected $countryColors = array(
		0 => array(226, 198, 158), /* Neutral */
		1 => array(196, 143, 133), /* Austria */
		2 => array(239, 196, 228), /* Britain   */
		3 => array(160, 138, 117), /* Denmark  */
		4 => array(121, 175, 198), /* France */
		5 => array(164, 196, 153), /* Naples   */
		6 => array(120, 120, 120), /* Prussia  */
		7 => array(168, 126, 159), /* Russia  */
		8 => array(206, 153, 103), /* Spain   */
		9 => array( 83, 114, 137), /* Sweden  */
		10=> array(234, 234, 175)  /* Turkey */
	);

	protected function resources() {
		if( $this->smallmap )
		{
			return array(
				'map'     =>'variants/Napoleonic/resources/smallmap.png',
				'army'    =>'contrib/smallarmy.png',
				'fleet'   =>'contrib/smallfleet.png',
				'names'   =>'variants/Napoleonic/resources/smallmapNames.png',
				'standoff'=>'images/icons/cross.png'
			);
		}
		else
		{
			return array(
				'map'     =>'variants/Napoleonic/resources/map.png',
				'army'    =>'contrib/army.png',
				'fleet'   =>'contrib/fleet.png',
				'names'   =>'variants/Napoleonic/resources/mapNames.png',
				'standoff'=>'images/icons/cross.png'
			);
		}
	}

}

?>