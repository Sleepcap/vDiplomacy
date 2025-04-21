<?php
/*
	Copyright (C) 2019 Technostar / Oliver Auth

	This file is part of the Europa Renovatio variant for webDiplomacy

	The Europa Renovatio variant for webDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Divided States variant for webDiplomacy is distributed in the hope that it will be
	useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.
	
*/

defined('IN_CODE') or die('This script can not be run by itself.');

//Draw move flags for the icons at the right position
class MoveFlags_drawMap extends drawMap
{
	public function countryFlag($terrID, $countryID)
	{
		list($x, $y) = $this->territoryPositions[$terrID];
		$this->territoryPositions[0] = array($x,$y+$this->fleet['height']/2+2.6);
		$save = $this->fleet;
		$this->fleet = array('width'=>$this->fleet['width']+5, 'height'=>$this->fleet['height']+5);
		parent::countryFlag(0, $countryID);
		$this->fleet = $save;
	}
}

class CustomCountryIcons_drawMap extends MoveFlags_drawMap
{
	// Arrays for the custom icons:
	protected $unit_c =array(); // An array to store the owner of each territory
	protected $army_c =array(); // Custom army icons
	protected $fleet_c=array(); // Custom fleet icons

	// Load custom icons (fleet and army) for each country
	protected function loadImages()
	{
//		$this->army_c[0]  = $this->loadImage('variants/Europa_Renovatio/resources/ArmyNeutral.png');
//		$this->fleet_c[0] = $this->loadImage('variants/Europa_Renovatio/resources/FleetNeutral.png');
		for ($i=1; $i<=count($GLOBALS['Variants'][VARIANTID]->countries); $i++) {
			$this->army_c[$i]  = $this->loadImage('variants/Europa_Renovatio/resources/Army' .$GLOBALS['Variants'][VARIANTID]->countries[$i-1].'.png');
			$this->fleet_c[$i] = $this->loadImage('variants/Europa_Renovatio/resources/Fleet'.$GLOBALS['Variants'][VARIANTID]->countries[$i-1].'.png');
		}
//		$this->army_c[51]  = $this->loadImage('variants/Europa_Renovatio/resources/ArmyNeutral.png');
//		$this->fleet_c[51] = $this->loadImage('variants/Europa_Renovatio/resources/FleetNeutral.png');
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
		parent::countryFlag($terrName, $countryID);
	}
	
	// Draw the custom icons:
	public function addUnit($terrID, $unitType)
	{
		if ($this->unit_c[$terrID] == 0) return;// Added for the map-tool
		$this->army  = $this->army_c[$this->unit_c[$terrID]];
		$this->fleet = $this->fleet_c[$this->unit_c[$terrID]];
		parent::addUnit($terrID, $unitType);
	}
	
}

class Transform_drawMap extends CustomCountryIcons_drawMap
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

class MultiLayerMap_drawMap extends Transform_drawMap
{
	
	// Store the 570 territories in seperated images so they do not fill the color palettes
	protected $terrMap1 = array();
	protected $terrMap2 = array();
	protected $terrMap3 = array();

	// Load the territory images
	protected function loadImages()
	{
		ini_set('memory_limit',"650M");
		ini_set('max_execution_time', 500);
		parent::loadImages();
		$this->terrMap1 = $this->loadImage('variants/Europa_Renovatio/resources/php1.png');
		$this->terrMap2 = $this->loadImage('variants/Europa_Renovatio/resources/php2.png');
		$this->terrMap3 = $this->loadImage('variants/Europa_Renovatio/resources/php3.png');
		// use a blank image as base image for units and order arrows etc
		$this->map['image'] = imagecreate($this->map['width'], $this->map['height']);
		$this->setTransparancy($this->map);
	}
	
	// The territories that get colored on the corresponding extra images
	public function colorTerritory($terrID, $countryID)
	{
		list($x, $y) = $this->territoryPositions[$terrID];

		if (imagecolorat($this->terrMap1['image'], $x, $y) != 0)
			$this->colorTerritoryOnImg ($terrID, $countryID, $this->terrMap1['image']);
		elseif (imagecolorat($this->terrMap2['image'], $x, $y) != 0)
			$this->colorTerritoryOnImg ($terrID, $countryID, $this->terrMap2['image']);
		else
			$this->colorTerritoryOnImg ($terrID, $countryID, $this->terrMap3['image']);
	}
	
	protected function colorTerritoryOnImg($terrID, $countryID, &$img){
		$mapsave=$this->map['image'];
		$this->map['image']=$img;
		parent::colorTerritory($terrID, $countryID);
		$img=$this->map['image'];
		$this->map['image']=$mapsave;
	}

	// Combine the all maps.
	public function mergeMaps()
	{
		$w = $this->map['width'];
		$h = $this->map['height'];
		$im = imagecreate($this->map['width'], $this->map['height']);
		imagecopyresampled($im, $this->terrMap3['image'], 0, 0, 0, 0, $w, $h, $w, $h);
		imagecopyresampled($im, $this->terrMap2['image'], 0, 0, 0, 0, $w, $h, $w, $h);
		imagecopyresampled($im, $this->terrMap1['image'], 0, 0, 0, 0, $w, $h, $w, $h);
		imagecopyresampled($im, $this->map['image'], 0, 0, 0, 0, $w, $h, $w, $h);
		imagetruecolortopalette($im, true, 256);
		$this->map['image']=$im;
	}
	
	public function write($filename)
	{
		$this->mergeMaps();
		parent::write($filename);
	}
	
	public function writeToBrowser()
	{
		$this->mergeMaps();
		parent::writeToBrowser();
	}

}

class ZoomMap_drawMap extends MultiLayerMap_drawMap
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
	
	//Counted 111 total
		2   => array( 30, 0,  0, 874, 902), //Aberdeen
		14   => array( 18, 0,  0, 573,  2260), //Agadir
		20   => array( 16, 0,  0, 2429,  1941), //Aleppo
		38   => array( 9, 0,  0, 941,  1547), //Aquitaine
		46   => array( 16, 0,  0, 2224,  2405), //Aswan
		57   => array( 11, 0,  0, 2508,  1425), //Azov
		61   => array( 27, 0,  0, 2740,  2072), //Baghdad
		67   => array( 1, 0,  0, 1079,  1796), //Balearic Islands
		69   => array( 16, 0,  0, 1767,  2133), //Bangazi
		101   => array( 31, 0,  0, 1574,  734), //Bergslagen
		108   => array( 35, 0,  0, 1338,  1911), //Bizerte
		116   => array( 10, 0,  0, 1093,  1470), //Bourges
		119   => array( 14, 0,  0, 2110,  1364), //Bratslav
		121   => array( 36, 0,  0, 1346,  1508), //Brescia
		135   => array( 1, 0,  0, 1328,  1811), //Cagliari
		155   => array( 20, 0,  0, 1585,  1695), //Capitanata
		173   => array( 33, 0,  0, 1690,  1133), //Chelmno
		186   => array( 26, 0,  0, 633,  1756), //Coimbra
		191   => array( 7, 0,  0, 765,  1856), //Cordoba
		206   => array( 36, 0,  0, 1574,  1586), //Dalmatia
		207   => array( 16, 0,  0, 2410,  2088), //Damascus
		209   => array( 10, 0,  0, 1191,  1539), //Dauphine
		217   => array( 35, 0,  0, 1389,  2056), //Djerba
		242   => array( 4, 0,  0, 1456,  1285), //Eger
		252   => array( 9, 0,  0, 999,  1190), //Essex
		259   => array( 21, 0,  0, 695,  617), //Faroe Islands
		264  => array( 34, 0,  0, 854,  2107), //Figuig
		273   => array( 28, 0,  0, 1279,  1549), //Fontferrat
		275   => array( 6, 0,  0, 1189,  1442), //Franche-Comte
		281   => array( 7, 0,  0, 637,  1637), //Galicia
		283   => array( 18, 0,  0, 704,  2054), //Gharb
		289   => array( 15, 0,  0, 1809,  899), //Goldingen
		290   => array( 2, 0,  0, 1491,  1496), //Gorz
		291   => array( 8, 0,  0, 1691,  871), //Gotland
		292   => array( 7, 0,  0, 353,  2318), //Gran Canaria
		347   => array( 31, 0,  0, 1640,  615), //Halsingland
		351   => array( 9, 0,  0, 906,  1243), //Hampshire
		392   => array( 36, 0,  0, 1500,  1511), //Istria
		396   => array( 35, 0,  0, 1193,  1941), //Kabylia
		410   => array( 35, 0,  0, 1296,  1957), //Kef
		411   => array( 9, 0,  0, 997,  1227), //Kent
		412   => array( 22, 0,  0, 2584,  483), //Kholmogory
		441   => array( 7, 0,  0, 734,  1672), //Leon
		443   => array( 4, 0,  0, 1599,  1229), //Liegnitz
		454   => array( 22, 0,  0, 2205,  760), //Ladoga
		458   => array( 12, 0,  0, 2612,  1397), //Lower Don
		461   => array( 20, 0,  0, 1610,  1761), //Lucania
		473   => array( 10, 0,  0, 1148,  1504), //Lyon
		474   => array( 23, 0,  0, 1868,  1743), //Macedonia
		482   => array( 9, 0,  0, 944,  1407), //Maine
		489   => array( 1, 0,  0, 1527,  1970), //Malta
		501   => array( 33, 0,  0, 1802,  1000), //Memel
		503   => array( 23, 0,  0, 2087,  1921), //Mentese
		505   => array( 1, 0,  0, 1552,  1872), //Messina
		509   => array( 14, 0,  0, 2048,  1092), //Minsk
		511   => array( 35, 0,  0, 1085,  1935), //Mitidja
		514   => array( 10, 0,  0, 1106,  1608), //Montpellier
		520   => array( 7, 0,  0, 908,  1878), //Murcia
		529   => array( 33, 0,  0, 1551,  1135), //Neumark
		532   => array( 28, 0,  0, 1249,  1594), //Nice
		564   => array( 31, 0,  0, 1975,  719), //Nyland
		567   => array( 1, 0,  0, 1327,  1726), //Olbia
		568   => array( 4, 0,  0, 1631,  1294), //Olomouc
		569   => array( 22, 0,  0, 2262,  698), //Olonets
		572   => array( 15, 0,  0, 1858,  838), //Osel
		575   => array( 31, 0,  0, 1611,  838), //Ostergotland
		577   => array( 8, 0,  0, 1353,  961), //Ostjylland
		581   => array( 18, 0,  0, 706,  2207), //Ouarzazate
		585   => array( 9, 0,  0, 714,  1105), //Pale
		598   => array( 28, 0,  0, 1259,  1535), //Piedmont
		603   => array( 4, 0,  0, 1492,  1297), //Plzen
		605   => array( 14, 0,  0, 1850,  1166), //Podlasie
		607   => array( 14, 0,  0, 2097,  996), //Polotsk
		611   => array( 25, 0,  0, 1614,  1178), //Poznan
		612   => array( 13, 0,  0, 1637,  1368), //Pozsony
		625   => array( 10, 0,  0, 1115,  1330), //Reims
		626   => array( 15, 0,  0, 1954,  778), //Reval
		633   => array( 19, 0,  0, 2509,  874), //Rostov
		636   => array( 19, 0,  0, 2295,  957), //Rzhev
		637   => array( 7, 0,  0, 722,  1737), //Salamanca
		639   => array( 20, 0,  0, 1554,  1755), //Salerno
		643   => array( 12, 0,  0, 2795,  1419), //Sarai
		665   => array( 7, 0,  0, 711,  1891), //Sevilla
		672   => array( 21, 0,  0, 916,  748), //Shetland Islands
		673   => array( 9, 0,  0, 861,  1149), //Shrewsbury
		676   => array( 23, 0,  0, 2078,  1595), //Silistria
		690   => array( 21, 0,  0, 1393,  784), //Smalenene
		695   => array( 22, 0,  0, 2348,  489), //Soroka
		706   => array( 13, 0,  0, 1754,  1339), //Spis
		730   => array( 23, 0,  0, 2029,  1853), //Sugla
		731   => array( 2, 0,  0, 1232,  1405), //Sundgau
		732   => array( 18, 0,  0, 635,  2231), //Sus
		738   => array( 18, 0,  0, 734,  1980), //Tangiers
		739   => array( 16, 0,  0, 2379,  2023), //Tarabulus
		741   => array( 23, 0,  0, 1994,  1622), //Tarnovo
		747   => array( 12, 0,  0, 2752,  1559), //Terek
		757   => array( 2, 0,  0, 1409,  1447), //Tirol
		770   => array( 21, 0,  0, 1414,  494), //Trondelag
		771   => array( 33, 0,  0, 1676,  1101), //Tuchola
		787   => array( 24, 0,  0, 1450,  1593), //Urbino
		792   => array( 13, 0,  0, 1625,  1482), //Varasd
		794   => array( 20, 0,  0, 1532,  1681), //Vasto
		797   => array( 36, 0,  0, 1385,  1511), //Verona
		806   => array( 14, 0,  0, 1953,  1232), //Volhynia
		808   => array( 12, 0,  0, 2497,  1208), //Voronezh
		810   => array( 32, 0,  0, 1289,  1453), //Waldstatte
		813   => array( 33, 0,  0, 1746,  1061), //Warmia
		814   => array( 25, 0,  0, 1788,  1188), //Warzawa
		816   => array( 34, 0,  0, 940,  1982), //Wehran
		835   => array( 29, 0,  0, 1468,  1189), //Wittenberg
		843   => array( 27, 0,  0, 2714,  1769) //Yerevan
		
	);
	
	/**
	* An array containing the neutral support-center icon image resource, and its width and height.
	* $image['image'],['width'],['height']
	* @var array
	**/
	protected $sc=array();
	
	/**
	* An array containing the information if one of the first 9 territories 
	* still has a neutral support-center (So we might not need to draw a flag)
	**/
	protected $nsc=array();

	protected function loadImages()
	{
		parent::loadImages();
		$this->sc = $this->loadImage('variants/Europa_Renovatio/resources/small_sc.png');	
	}

	/**
	* There are some territories on the map that belong to a country but have a supply-center
	* that is considered "neutral".
	* They are set to owner "Neutral" in the installation-file, so we need to check if they are
	* still "neutal" and paint the territory in the color of the country they "should" belong to.
	* After that draw the "Neutral-SC-overloay" on the map.
	**/
	public function ColorTerritory($terrID, $countryID)
	{

		if ((isset($this->nsc_info[$terrID][0])) && $countryID==0)
		{
			
			$this->nsc[$terrID]=$countryID;
			$sx=($this->smallmap ? $this->nsc_info[$terrID][1] : $this->nsc_info[$terrID][3]);
			$sy=($this->smallmap ? $this->nsc_info[$terrID][2] : $this->nsc_info[$terrID][4]);
			$this->putImage($this->sc, $sx, $sy);
			parent::ColorTerritory($terrID, $this->nsc_info[$terrID][0]);
		}
		else
		{
			parent::ColorTerritory($terrID, $countryID);
		}
	}
		
	/* No need to draw the country flags for "neural-SC-territories if they get occupied by 
	** the country they should belong to
	*/
	public function countryFlag($terrID, $countryID)
	{
		if (isset($this->nsc[$terrID]) && ($this->nsc[$terrID] == $countryID)) return;
		parent::countryFlag($terrID, $countryID);
	}

}

class Europa_RenovatioVariant_drawMap extends NeutralScBox_drawMap {

	public function __construct($smallmap)
	{
		// Map is too big, so up the memory-limit
		parent::__construct($smallmap);
		ini_set('memory_limit',"650M");
		ini_set('max_execution_time', 500);
	}
	
	protected $countryColors = array(
         0=> array(226,198,158), /* Neutral */
		 1=> array(166, 68, 72), /* Aragon */
		 2=> array(220,220,220), /* Austria */
		 3=> array( 17,116,193), /* Bavaria  */
		 4=> array(161,139, 40), /* Bohemia */
		 5=> array(123, 90, 90), /* Brandenburg   */
		 6=> array(148, 30, 70), /* Burgundy  */
		 7=> array(193,171,  8), /* Castille  */
		 8=> array(190, 70, 70), /* Denmark */
		 9=> array(193, 26, 14), /* England */
		10=> array( 20, 50,210), /* France  */
		11=> array(218,215, 56), /* Genoa */
		12=> array(193,179,127), /* Great-Horde   */
		13=> array(152, 85, 92), /* Hungary  */
		14=> array(154, 69,116), /* Lithuania  */
		15=> array(125, 30,100), /* Livonian-Order */
		16=> array(188,166, 93), /* Mamluks */
		17=> array(166,108,146), /* Milan  */
		18=> array(191,110, 62), /* Morocco */
		19=> array(206,181, 97), /* Muscovy   */
		20=> array(100, 50,150), /* Naples  */
		21=> array(117,165,188), /* Norway  */
		22=> array( 97,126, 37), /* Novgorod */
		23=> array(126,203,120), /* Ottomans */
		24=> array(211,220,178), /* Papacy  */
		25=> array(197, 92,106), /* Poland */
		26=> array( 50,140, 88), /* Portugal   */
		27=> array( 70,121,136), /* Qara-Qoyunlu  */
		28=> array(235,196,231), /* Savoy  */
		29=> array(155,147,180), /* Saxony */
		30=> array(218,184, 13), /* Scotland */
		31=> array(  8, 82,165), /* Sweden  */
		32=> array(153,122,108), /* Switzerland */
		33=> array(102,105,104), /* Teutonic-Order   */
		34=> array( 40,110,140), /* Tlemcen  */
		35=> array(125,114, 49), /* Tunis  */
		36=> array( 54,167,156)  /* Venice */
	);

	// The resources (map and default icons)
	protected function resources() {
		return array(
			'map'     =>'variants/Europa_Renovatio/resources/php1.png',
			'names'   =>'variants/Europa_Renovatio/resources/namesmap.png',
			'army'    =>'variants/Europa_Renovatio/resources/armyNeutral.png',
			'fleet'   =>'variants/Europa_Renovatio/resources/fleetNeutral.png',
			'standoff'=>'images/icons/cross.png'
		);
	}
	
}

?>