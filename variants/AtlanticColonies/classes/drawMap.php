<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class CustomCountryIcons_drawMap extends drawMap
{
	// Arrays for the custom icons:
	protected $unit_c =array(); // An array to store the owner of each territory
	protected $army_c =array(); // Custom army icons
	protected $fleet_c=array(); // Custom fleet icons

	// Load custom icons (fleet and army) for each country
	protected function loadImages()
	{
		$this->army_c[0]  = $this->loadImage('variants/AtlanticColonies/resources/'.($this->smallmap ? 'small' : '').'army_1.png');
		$this->fleet_c[0] = $this->loadImage('variants/AtlanticColonies/resources/'.($this->smallmap ? 'small' : '').'fleet_1.png');
		
		for ($i=1; $i<=count($GLOBALS['Variants'][VARIANTID]->countries); $i++) {
			$this->army_c[$i]  = $this->loadImage('variants/AtlanticColonies/resources/'.($this->smallmap ? 'small' : '').'army_' .$i.'.png');
			$this->fleet_c[$i] = $this->loadImage('variants/AtlanticColonies/resources/'.($this->smallmap ? 'small' : '').'fleet_'.$i.'.png');
		}
		parent::loadImages();
	}
	
	// Save the countryID for every colored Territory (and their coasts)
	public function colorTerritory($terrID, $countryID)
	{
		$terrName=$this->territoryNames[$terrID];
		$this->unit_c[$terrID]=$countryID;
		$this->unit_c[array_search($terrName. " (North Coast)" ,$this->territoryNames)]=$countryID;
		$this->unit_c[array_search($terrName. " (East Coast)"  ,$this->territoryNames)]=$countryID;
		$this->unit_c[array_search($terrName. " (South Coast)" ,$this->territoryNames)]=$countryID;
		$this->unit_c[array_search($terrName. " (West Coast)"  ,$this->territoryNames)]=$countryID;
		parent::colorTerritory($terrID, $countryID);
	}
	
	// Store the country if a unit needs to draw a flag for a custom icon.
	public function countryFlag($terrName, $countryID)
	{
		$this->unit_c[$terrName]=$countryID;
	}
	
	// Draw the custom icons:
	public function addUnit($terrID, $unitType)
	{
		$this->army  = $this->army_c[$this->unit_c[$terrID]];
		$this->fleet = $this->fleet_c[$this->unit_c[$terrID]];
		parent::addUnit($terrID, $unitType);
	}
	
}

class AtlanticColoniesVariant_drawMap extends CustomCountryIcons_drawMap {

	protected $countryColors = array(
		0 => array(198, 149, 116), // Neutral
		1 => array(231, 161,  68), // France
		2 => array(177, 176,  99), // Spain
		3 => array(239, 178, 161), // England
		4 => array(245, 211, 118), // Portugal
	);

	public function __construct($smallmap)
	{
		// Map is too big, so up the memory-limit
		parent::__construct($smallmap);
		if ( !$this->smallmap )
			ini_set('memory_limit',"60M");
	}

	protected function resources() {
		if( $this->smallmap )
		{
			return array(
				'map'=>'variants/AtlanticColonies/resources/smallmap.png',
				'army'=>'contrib/smallarmy.png',
				'fleet'=>'contrib/smallfleet.png',
				'names'=>'variants/AtlanticColonies/resources/smallmapNames.png',
				'standoff'=>'images/icons/cross.png'
			);
		}
		else
		{
			return array(
				'map'=>'variants/AtlanticColonies/resources/map.png',
				'army'=>'contrib/army.png',
				'fleet'=>'contrib/fleet.png',
				'names'=>'variants/AtlanticColonies/resources/mapNames.png',
				'standoff'=>'images/icons/cross.png'
			);
		}
	}
}

?>