<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class CustomIconsPerCountry_drawMap extends CustomIcons_drawMap {

	// Arrays for the custom icons:
	protected $unit_c =array(); // An array to store the owner of each territory
	protected $army_c =array(); // Custom army icons
	protected $fleet_c=array(); // Custom fleet icons

	// Load custom icons (fleet and army) for each country
	protected function loadImages()
	{
		if(!$this->Variant->rules[RULE_CUSTOM_ICONS_PER_COUNTRY]){
			return parent::loadImages();
		}

		//$this->army_c[0]  = $this->loadImage('variants/'.$this->Variant->name.'/resources/'.($this->smallmap ? 'small' : '').'armyNeutral.png');
		//$this->fleet_c[0] = $this->loadImage('variants/'.$this->Variant->name.'/resources/'.($this->smallmap ? 'small' : '').'fleetNeutral.png');
		
		for ($i=1; $i<=count($this->Variant->countries); $i++) {
			$this->army_c[$i]  = $this->loadImage('variants/'.$this->Variant->name.'/resources/'.($this->smallmap ? 'small' : '').'army'.$this->Variant->countries[$i-1].'.png');
			$this->fleet_c[$i] = $this->loadImage('variants/'.$this->Variant->name.'/resources/'.($this->smallmap ? 'small' : '').'fleet'.$this->Variant->countries[$i-1].'.png');
		}
		parent::loadImages();
	}
	
	// Save the countryID for every colored Territory (and their coasts)
	public function colorTerritory($terrID, $countryID)
	{
		if(!$this->Variant->rules[RULE_CUSTOM_ICONS_PER_COUNTRY]){
			return parent::colorTerritory($terrID, $countryID);
		}

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
		if(!$this->Variant->rules[RULE_CUSTOM_ICONS_PER_COUNTRY]){
			return parent::countryFlag($terrName, $countryID);
		}

		$this->unit_c[$terrName]=$countryID;
	}
	
	// Draw the custom icons:
	public function addUnit($terrID, $unitType)
	{
		if(!$this->Variant->rules[RULE_CUSTOM_ICONS_PER_COUNTRY]){
			return parent::countryFlag($terrID, $unitType);
		}

		$this->army  = $this->army_c[$this->unit_c[$terrID]];
		$this->fleet = $this->fleet_c[$this->unit_c[$terrID]];
		parent::addUnit($terrID, $unitType);
	}
}

?>