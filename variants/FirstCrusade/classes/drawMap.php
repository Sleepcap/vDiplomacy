<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class FirstCrusadeVariant_drawMap extends drawMap {

	protected $countryColors = array(
		0 => array(226, 198, 158), // 
		1 => array(234, 234, 175), // Holy Roman Empire
		2 => array(239, 196, 228), // England
		3 => array(114, 115, 158), // France
		4 => array(121, 175, 198), // Seljuk Turks
		5 => array(212, 171, 131), // Byzantine Empire
		6 => array(196, 143, 133), // Russia
		7 => array(164, 196, 153)  // Almoravids
	);

	public function __construct($smallmap)
	{
		// Map is too big, so up the memory-limit
		parent::__construct($smallmap);
		if ( !$this->smallmap )
			ini_set('memory_limit',"30M");
	}
	
	// Arrays for the custom icons:
	protected $unit_c =array(); // An array to store the owner of each territory
	protected $army_c =array(); // Custom army icons
	protected $fleet_c=array(); // Custom fleet icons

	// Load custom icons (fleet and army) for each country
	protected function loadImages()
	{
	
		for ($i=1; $i<=count($GLOBALS['Variants'][VARIANTID]->countries); $i++) {
			$this->army_c[$i]  = $this->loadImage('variants/FirstCrusade/resources/'.($this->smallmap?'small':'').'army_' .$i.'.png');
			$this->fleet_c[$i] = $this->loadImage('variants/FirstCrusade/resources/'.($this->smallmap?'small':'').'fleet_'.$i.'.png');
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
		if ($this->unit_c[$terrID] == 0) $this->unit_c[$terrID] = 1;
		$this->army  = $this->army_c[$this->unit_c[$terrID]];
		$this->fleet = $this->fleet_c[$this->unit_c[$terrID]];
		parent::addUnit($terrID, $unitType);
	}
	
	protected function resources() {
		if( $this->smallmap )
		{
			return array(
				'map'=>'variants/FirstCrusade/resources/smallmap.png',
				'army'=>'contrib/smallarmy.png',
				'fleet'=>'contrib/smallfleet.png',
				'names'=>'variants/FirstCrusade/resources/smallmapNames.png',
				'standoff'=>'images/icons/cross.png'
			);
		}
		else
		{
			return array(
				'map'=>'variants/FirstCrusade/resources/map.png',
				'army'=>'contrib/army.png',
				'fleet'=>'contrib/fleet.png',
				'names'=>'variants/FirstCrusade/resources/mapNames.png',
				'standoff'=>'images/icons/cross.png'
			);
		}
	}
}

?>