<?php

defined('IN_CODE') or die('This script can not be run by itself.');

include_once ("variants/TenSixtySix/classes/drawMap.php");

class TenSixtySix_V3Variant_drawMap extends TenSixtySixVariant_drawMap
{
	protected $sea_terrs = array(
		'Central North Sea' , 'Firth of Clyde' , 'North Atlantic Ocean' , 'Mid Atlantic Ocean' , 'Irish Sea',
		'Bristol Channel', 'North English Channel', 'Southwest North Sea', 'Strait of Dover',
		'Thames Estuary' , 'South English Channel', 'Northwest North Sea', 'Skagerrak',
		'Norwegian Sea'  , 'Northeast North Sea'  , 'Southeast North Sea', 'Baltic Sea',
		'Channel Islands', 'Shetland and Orkneys' , 'Heligoland Bight');

	protected function resources() {
		if( $this->smallmap )
		{
			return array(
				'map' =>'variants/TenSixtySix_V3/resources/smallmap.png',
				'army' =>'contrib/smallarmy.png',
				'fleet' =>'contrib/smallfleet.png',
				'names' =>'variants/TenSixtySix_V3/resources/smallmapNames.png',
				'standoff'=>'images/icons/cross.png'
			);
		}
		else
		{
			return array(
				'map' =>'variants/TenSixtySix_V3/resources/map.png',
				'army' =>'contrib/army.png',
				'fleet' =>'contrib/fleet.png',
				'names' =>'variants/TenSixtySix_V3/resources/mapNames.png',
				'standoff'=>'images/icons/cross.png'
			);
		}
	}
	
	// Failsave for the initial-units drawn on a sea-space in FogOfWar (Channel Islands)
	public function addUnit($terrName, $unitType)
	{
		if (!isset($this->army_c[$this->unit_c[$terrName]]))
			$this->army_c[$this->unit_c[$terrName]] = $this->army_c[2];
		if (!isset($this->fleet_c[$this->unit_c[$terrName]]))
			$this->fleet_c[$this->unit_c[$terrName]] = $this->fleet_c[2];
		parent::addUnit($terrName, $unitType);
	}

}

?>
