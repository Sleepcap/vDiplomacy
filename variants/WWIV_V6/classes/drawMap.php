<?php
/*
	Copyright (C) 2013 kaner406 & Oliver Auth

	This file is part of the WWIV_V6 variant for webDiplomacy

	The WWIV_V6 variant for webDiplomacy is free software: you can
	redistribute it and/or modify it under the terms of the GNU Affero General
	Public License as published by the Free Software Foundation, either version
	3 of the License, or (at your option) any later version.

	The WWIV_V6 variant for webDiplomacy is distributed in the hope
	that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
	warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.
*/

defined('IN_CODE') or die('This script can not be run by itself.');

class WWIV_V6Variant_drawMap extends WWIVVariant_drawMap {

	protected $countryColors = array(
  		 0=> array(226, 198, 158), // Neutral
		 1=> array( 10,  35, 192), // Amazon-Empire 
		 2=> array(196, 150,  18), // Argentina     
		 3=> array( 10,  49, 255), // Australia     
		 4=> array(111,  13,   3), // Brazil        
		 5=> array(109,  19, 103), // California    
		 6=> array( 81,  81,   0), // Canada        
		 7=> array(  0, 200,  28), // Catholica     
		 8=> array(  0, 250,  44), // Central-Asia  
		 9=> array(185, 185, 185), // Colombia      
		10=> array(215,  57,  17), // Kongo         
		11=> array(255, 156,   0), // Cuba          
		12=> array(255, 253,  51), // North-Africa         
		13=> array(235,  83, 233), // Germany       
		14=> array(254, 254, 254), // Central States      
		15=> array(251,  51, 131), // Inca-Empire   
		16=> array(115, 113,  14), // India         
		17=> array( 71, 251, 151), // Indonesia     
		18=> array(255, 145, 214), // Iran          
		19=> array( 71, 151, 251), // Japan         
		20=> array(  0, 182, 184), // East-Africa         
		21=> array(  0, 122, 124), // Manchuria     
		22=> array(140, 186,  28), // Mexico        
		23=> array(104, 104, 104), // Nigeria       
		24=> array(197, 251,  67), // Oceania       
		25=> array(255,   0,   0), // Philippines   
		26=> array(  0, 101,  11), // Quebec        
		27=> array(162, 166, 254), // Russia        
		28=> array(255, 106, 109), // Sichuan-Empire
		29=> array(183,  96,  10), // Song-Empire   
		30=> array(  0, 181, 107), // South-Africa  
		31=> array( 90,  96, 173), // Texas         
		32=> array(195,  35, 104), // Thailand      
		33=> array( 20,  20, 162), // Turkey        
		34=> array(235, 196,  58), // United-Kingdom
		35=> array(186, 183, 108), // United-States
		36=> array(168, 126, 159), // Saudi-Arabia
	);

	// The resources (map and default icons)
	protected function resources() {
		return array(
			'map'     =>'variants/WWIV_V6/resources/map.png',
			'names'   =>'variants/WWIV_V6/resources/mapNames.png',
			'army'    =>'variants/WWIV/resources/armyNeutral.png',
			'fleet'   =>'variants/WWIV/resources/fleetNeutral.png',
			'standoff'=>'images/icons/cross.png'
		);
	}
	
	// Load custom icons (fleet and army) for each country
	protected function loadImages()
	{
		$newCountries = array (
			9  => 'Kongo',
			11 => 'North-Africa',
			13 => 'Central-States',
			19 => 'East-Africa',
			35 => 'Saudi-Arabia'
		);
		
		foreach ($newCountries as $id => $name)
			$GLOBALS['Variants'][VARIANTID]->countries[$id]='Neutral';
			
		parent::loadImages();
		
		foreach ($newCountries as $id => $name)
		{
			$GLOBALS['Variants'][VARIANTID]->countries[$id]=$name;
			$this->army_c[$id]  = $this->loadImage('variants/WWIV_V6/resources/army'.$name.'.png');
			$this->fleet_c[$id] = $this->loadImage('variants/WWIV_V6/resources/fleet'.$name.'.png');
		}

		$this->map2 = $this->loadImage('variants/WWIV_V6/resources/map_2.png');
	}
	
}

?>