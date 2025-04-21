<?php
/*
	Copyright (C) 2020 Jared Kish

	This file is part of the A_Modern_Europe variant for vDiplomacy

	The A_Modern_Europe variant for vDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The A_Modern_Europe variant for vDiplomacy is distributed in the hope that it will
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with vDiplomacy. If not, see <http://www.gnu.org/licenses/>.
*/

defined('IN_CODE') or die('This script can not be run by itself.');

class Transform_drawMap extends drawMap
{
	private $trafo=array();

	public function drawSupportHold($fromTerrID, $toTerrID, $success)
	{
		if ($toTerrID < 1000) return parent::drawSupportHold($fromTerrID, $toTerrID, $success);

		$toTerrID = $toTerrID - 1000;
		if ($success)
			$this->trafo[$fromTerrID]=$toTerrID;

		$this->drawTransform($fromTerrID, $toTerrID, $success);
	}

	// If a unit did a transform draw the new unit-type on the board instead of the old...
	public function addUnit($terrID, $unitType)
	{
		if (array_key_exists($terrID,$this->trafo))
			return parent::addUnit($this->trafo[$terrID], ($unitType == 'Fleet' ? 'Army' : 'Fleet'));
		parent::addUnit($terrID, $unitType);
	}

	// Draw the transformation circle:
	protected function drawTransform($fromTerrID, $toTerrID, $success)
	{

		$terrID = ($success ?  $toTerrID : $fromTerrID);

		if ( $fromTerrID != $toTerrID )
			$this->drawMove($fromTerrID,$toTerrID, $success);

		$darkblue  = $this->color(array(40, 80,130));
		$lightblue = $this->color(array(70,150,230));

		list($x, $y) = $this->territoryPositions[$terrID];

		$width=($this->fleet['width'])+($this->fleet['width'])/2;

		imagefilledellipse ( $this->map['image'], $x, $y, $width, $width, $darkblue);
		imagefilledellipse ( $this->map['image'], $x, $y, $width-2, $width-2, $lightblue);

		if ( !$success ) $this->drawFailure(array($x-1, $y),array($x+2, $y));
	}
}

class ZoomMap_drawMap extends Transform_drawMap
{
	// Always only load the largemap (as there is no smallmap)
	public function __construct($smallmap)
	{
		parent::__construct(false);
	}

	protected function loadOrderArrows()
	{
		$this->smallmap=true;
		parent::loadOrderArrows();
		$this->smallmap=false;
	}

	// Always use the small standoff-Icons
	public function drawStandoff($terrName)
	{
		$this->smallmap=true;
		parent::drawStandoff($terrName);
		$this->smallmap=false;
	}

	// Always use the small failure-cross...
	protected function drawFailure(array $from, array $to)
	{
		$this->smallmap=true;
		parent::drawFailure($from, $to);
		$this->smallmap=false;
	}

}

class NeutralScBox_drawMap extends ZoomMap_drawMap
{
	/**
	* An array containing the XY-positions of the "neutral-SC-box" and
	* the country-color it should be colored if it's still unoccupied.
	*
	* Format: terrID => array (countryID, smallmapx, smallmapy, mapx, mapy)
	**/
	protected $nsc_info=array(
		  78 => array(  6, 1330, 1513, 1330, 1513), // Patras
		 202 => array( 20,  554,  715,  554,  715), // Aberdeen
		 203 => array( 20,  504,  795,  504,  795), // Glasgow
		 204 => array( 20,  437,  843,  437,  843), // Belfast
		 205 => array( 20,  521,  962,  521,  962), // Cardiff
		 206 => array( 20,  502, 1007,  502, 1007), // Plymouth
		 207 => array(  3,  715, 1023,  715, 1023), // Lille
		 208 => array(  3,  598, 1157,  598, 1157), // Nantes
		 209 => array(  3,  616, 1273,  616, 1273), // Bordeaux
		 210 => array(  3,  685, 1299,  685, 1299), // Toulouse
		 211 => array( 16,  572, 1326,  572, 1326), // Bilbao
		 212 => array( 16,  464, 1538,  464, 1538), // Seville
		 213 => array( 16,  635, 1486,  635, 1486), // Valencia
		 214 => array( 20,  470, 1579,  470, 1579), // Gibraltar
		 215 => array( 12, 	141, 1741,  141, 1741), // Madeira
		 216 => array(  8,  933, 1409,  933, 1409), // Sardinia
		 217 => array(  8, 1005, 1270, 1005, 1270), // Bologna
		 218 => array(  8, 1020, 1232, 1020, 1232), // Venice
		 219 => array(  3,  884, 1102,  884, 1102), // Strasbourg
		 220 => array(  5,  849, 1014,  849, 1014), // Cologne
		 221 => array(  5,  881,  994,  881,  994), // Dortmund
		 222 => array(  5,  915, 1058,  915, 1058), // Frankfurt
		 223 => array(  5, 1052,  982, 1052,  982), // Leipzig
		 224 => array(  1, 1210, 1059, 1210, 1059), // Ostrava
		 225 => array( 11, 1099,  912, 1099,  912), // Szczecin
		 226 => array( 11, 1231,  879, 1231,  879), // Gdansk
		 227 => array( 11, 1185, 1001, 1185, 1001), // Wroclaw
		 228 => array( 17, 1219,  748, 1219,  748), // Gotland
		 229 => array( 17, 1265,  429, 1265,  429), // Umea
		 230 => array(  2, 1444,  301, 1444,  301), // Rovaniemi
		 231 => array( 14, 1296,  857, 1296,  857), // Kaliningrad
		 232 => array( 15, 1331, 1320, 1331, 1320), // Nis
		 234 => array(  6, 1418, 1616, 1418, 1616), // Crete
		 235 => array( 13, 1491, 1159, 1491, 1159), // Iasi
		 236 => array( 19, 1604, 1181, 1604, 1181), // Odessa
		 237 => array( 19, 1751, 1145, 1751, 1145), // Zaporizhia
		 238 => array( 14, 1892, 1037, 1892, 1037), // Voronezh
		 239 => array( 14, 2226,  912, 2226,  912), // Samara
		 240 => array( 14, 2398,  882, 2398,  882), // Ufa
		 241 => array( 14, 2008, 1138, 2008, 1138), // Volgograd
		 242 => array( 14, 1880, 1161, 1880, 1161), // Rostov-on-Don
		 243 => array( 14, 1902, 1238, 1902, 1238), // Krasnodar
		 244 => array( 18, 1775, 1398, 1775, 1398), // Samsun
		 245 => array( 18, 1742, 1482, 1742, 1482), // Kayseri
		 246 => array( 18, 1751, 1558, 1751, 1558), // Adana
		 247 => array( 18, 1852, 1548, 1852, 1548), // Urfa
		 251 => array(  8, 1120, 1418, 1120, 1418) // Naples
	);

	/**
	* An array containing the neutral supply-center icon image resource, and its width and height.
	* $image['image'],['width'],['height']
	* @var array
	**/
	protected $sc=array();

	/**
	* An array containing the information if one of the first 9 territories
	* still has a neutral supply-center (So we might not need to draw a flag)
	**/
	protected $nsc=array();

	protected function loadImages()
	{
		parent::loadImages();
		$this->sc = $this->loadImage('variants/A_Modern_Europe/resources/small_sc.png');
	}

	/**
	* There are some territories on the map that belong to a country but have a supply-center
	* that is considered "neutral".
	* They are set to owner "Neutral" in the installation-file, so we need to check if they are
	* still "neutral" and paint the territory in the color of the country they "should" belong to.
	* After that draw the "Neutral-SC-overlay" on the map.
	**/
	public function ColorTerritory($terrID, $countryID)
	{
		if ((isset($this->nsc_info[$terrID][0])) && $countryID==0)
		{
			parent::ColorTerritory($terrID, $this->nsc_info[$terrID][0]);
			$this->nsc[$terrID]=$countryID;
			$sx=($this->smallmap ? $this->nsc_info[$terrID][1] : $this->nsc_info[$terrID][3]);
			$sy=($this->smallmap ? $this->nsc_info[$terrID][2] : $this->nsc_info[$terrID][4]);
			$this->putImage($this->sc, $sx, $sy);
		}
		else
		{
			parent::ColorTerritory($terrID, $countryID);
		}
	}

	/* No need to draw the country flags for neutral-SC-territories if they get occupied by
	** the country they should belong to
	*/
	public function countryFlag($terrID, $countryID)
	{
		if (isset($this->nsc[$terrID]) && ($this->nsc[$terrID] == $countryID)) return;
		parent::countryFlag($terrID, $countryID);
	}

}

class ResetPaletteVariant_drawMap extends NeutralScBox_drawMap
{
	public $countColor=0;
	public $setColors=false;

	protected function loadColors()
	{
		if ($this->setColors==true)
			$this->colors = array(
				'border'=>array(0,0,0),
				'standoff'=>array(200,20,20)
			);
		parent::loadColors();
	}
	
	protected function loadOrderArrows()
	{
		if ($this->setColors==true)
			parent::loadOrderArrows();
	}
	
	// After 200 territories reset the palette, so there is enough color for the units left.
	public function colorTerritory($terrID, $countryID)
	{
		$this->countColor++;
		if ($this->countColor == 230)
		{
			$im = imagecreate($this->map['width'],$this->map['height']);
			imagecopy($im, $this->map['image'], 0, 0, 0, 0, $this->map['width'],$this->map['height']);
			imagedestroy($this->map['image']);
			$this->map['image']=$im;
			$this->setColors=true;
			$this->loadColors();
			$this->loadOrderArrows();
		}
		parent::colorTerritory($terrID, $countryID);
	}
}

class A_Modern_EuropeVariant_drawMap extends ResetPaletteVariant_drawMap {

	protected $countryColors = array(
		0 => array(226, 198, 158), // Neutral
		1 => array(151, 129,   4), // Czechia
		2 => array(205, 212, 228), // Finland
		3 => array( 20,  50, 210), // France
		4 => array(170,   0,   0), // Georgia
		5 => array( 76,  97, 121), // Germany
		6 => array( 93, 181, 227), // Greece
		7 => array(  0, 158,  96), // Ireland
		8 => array(125, 171,  84), // Italy
		9 => array(154,  69, 116), // Lithuania
		10 => array(220, 138,  57), // Netherlands
		11 => array(197,  92, 106), // Poland
		12 => array( 39, 116,  70), // Portugal
		13 => array( 21,  96, 178), // Romania
		14 => array( 96, 131,  80), // Russia
		15 => array(166,  72,  57), // Serbia
		16 => array(231, 181,  12), // Spain
		17 => array(  8,  82, 165), // Sweden
		18 => array(192,  89,  67), // Turkey
		19 => array(106, 104, 104), // Ukraine
		20 => array(153,   0,   0)  // United Kingdom
	);

	public function __construct($smallmap)
	{
		// Map is too big, so up the memory-limit
		parent::__construct(true);
		ini_set('memory_limit',"32M");
	}

	protected function resources() {

		global $Variant;

		return array(
			'map'     =>'variants/'.$Variant->name.'/resources/map.png',
			'names'   =>'variants/'.$Variant->name.'/resources/mapNames.png',
			'army'    =>'contrib/smallarmy.png',
			'fleet'   =>'contrib/smallfleet.png',
			'standoff'=>'images/icons/cross.png'
		);
	}
}
?>