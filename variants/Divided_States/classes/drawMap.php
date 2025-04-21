<?php
/*
	Copyright (C) 2018 Technostar / Oliver Auth

	This file is part of the Divided States variant for webDiplomacy

	The Divided States variant for webDiplomacy is free software: you can redistribute
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
		$this->army_c[0]  = $this->loadImage('variants/Divided_States/resources/armyNeutral.png');
		$this->fleet_c[0] = $this->loadImage('variants/Divided_States/resources/fleetNeutral.png');
		for ($i=1; $i<=count($GLOBALS['Variants'][VARIANTID]->countries); $i++) {
			$this->army_c[$i]  = $this->loadImage('variants/Divided_States/resources/army' .$GLOBALS['Variants'][VARIANTID]->countries[$i-1].'.png');
			$this->fleet_c[$i] = $this->loadImage('variants/Divided_States/resources/fleet'.$GLOBALS['Variants'][VARIANTID]->countries[$i-1].'.png');
		}
		$this->army_c[51]  = $this->loadImage('variants/Divided_States/resources/armyNeutral.png');
		$this->fleet_c[51] = $this->loadImage('variants/Divided_States/resources/fleetNeutral.png');
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
	
	// Store the 511 territories in seperated images so they do not fill the color palettes
	protected $terrMap1 = array();
	protected $terrMap2 = array();

	// Load the territory images
	protected function loadImages()
	{
		ini_set('memory_limit',"350M");
		ini_set('max_execution_time', 300);
		parent::loadImages();
		$this->terrMap1 = $this->loadImage('variants/Divided_States/resources/usphp1.png');
		$this->terrMap2 = $this->loadImage('variants/Divided_States/resources/usphp2.png');
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
		else
			$this->colorTerritoryOnImg ($terrID, $countryID, $this->terrMap2['image']);
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
	
	//Counted 84 total
	
		3   => array( 12, 0,  0, 3221,  1164), // Alexandria
		4   => array( 11, 0,  0, 3317,  1200), // Ocean City
		19  => array(  1, 0,  0, 3500,   775), // Biddleford
		32  => array(  4, 0,  0, 3394,   858), // Pittsfield
		35  => array(  4, 0,  0, 3550,   895), // Cape Cod
		37  => array(  4, 0,  0, 3553,   930), // Nantucket
		45  => array(  6, 0,  0, 3387,   963), // Bridgeport
		47  => array(  7, 0,  0, 3437,   973), // Hamptons
		54  => array(  7, 0,  0, 3232,   831), // Syracuse
		61  => array(  9, 0,  0, 3292,  1018), // Allentown
		63  => array(  9, 0,  0, 3275,   961), // Scranton
		69  => array(  9, 0,  0, 3146,  1041), // Altoona
		79  => array(  8, 0,  0, 3336,  1119), // Cape May
		93  => array( 12, 0,  0, 3159,  1224), // Charlottesville
		95  => array( 12, 0,  0, 3273,  1276), // Newport News
		100 => array( 12, 0,  0, 2951,  1322), // Jonesville
		104 => array( 13, 0,  0, 3103,  1367), // Greensboro
		106 => array( 13, 0,  0, 2965,  1413), // Asheville
		109 => array( 13, 0,  0, 3162,  1443), // Fayetteville, NC
		114 => array( 14, 0,  0, 3160,  1535), // Myrtle Beach
		122 => array( 15, 0,  0, 2919,  1538), // Athens
		124 => array( 15, 0,  0, 2984,  1581), // Augusta, GA
		133 => array( 16, 0,  0, 3039,  1798), // Jacksonville
		139 => array( 16, 0,  0, 3043,  2067), // Fort Myers
		145 => array( 17, 0,  0, 2686,  1603), // Tuscaloosa
		147 => array( 17, 0,  0, 2735,  1488), // Huntsville
		151 => array( 19, 0,  0, 2615,  1423), // Jackson, TN
		154 => array( 19, 0,  0, 2882,  1392), // Knoxville
		161 => array( 20, 0,  0, 2745,  1325), // Bowling Green
		165 => array( 20, 0,  0, 2848,  1245), // Lexington
		172 => array( 21, 0,  0, 3072,  1116), // Morgantown
		180 => array( 22, 0,  0, 2983,  1014), // Akron
		183 => array( 22, 0,  0, 2889,   983), // Toledo
		186 => array( 23, 0,  0, 2866,   880), // Flint
		190 => array( 23, 0,  0, 2673,   630), // Marquette
		194 => array( 24, 0,  0, 2692,   989), // Gary
		198 => array( 24, 0,  0, 2736,  1167), // Bloomington, IN
		207 => array( 25, 0,  0, 2608,  1068), // Bloomington, IL
		211 => array( 25, 0,  0, 2634,   969), // Aurora
		217 => array( 26, 0,  0, 2506,   826), // La Crosse
		226 => array( 27, 0,  0, 2446,   805), // Rochester, MN
		231 => array( 28, 0,  0, 2442,   918), // Waterloo
		232 => array( 28, 0,  0, 2484,   955), // Cedar Rapids
		239 => array( 29, 0,  0, 2433,  1182), // Columbia, MO
		243 => array( 29, 0,  0, 2379,  1298), // Joplin
		250 => array( 30, 0,  0, 2318,  1432), // Fort Smith
		252 => array( 30, 0,  0, 2436,  1522), // Pine Bluff
		257 => array( 18, 0,  0, 2619,  1661), // Meridian
		259 => array( 18, 0,  0, 2585,  1738), // Hattiesburg
		268 => array( 31, 0,  0, 2419,  1816), // Lafayette
		269 => array( 31, 0,  0, 2361,  1806), // Lake Charles
		272 => array( 32, 0,  0, 1977,  1979), // Laredo
		273 => array( 32, 0,  0, 2042,  1846), // San Antonio
		283 => array( 32, 0,  0, 1886,  1521), // Lubbock
		285 => array( 32, 0,  0, 1908,  1403), // Amarillo
		290 => array( 33, 0,  0, 2236,  1373), // Tulsa
		295 => array( 34, 0,  0, 2169,  1252), // Wichita
		300 => array( 34, 0,  0, 2159,  1164), // Salina
		305 => array( 35, 0,  0, 2135,  1008), // Kearney
		309 => array( 35, 0,  0, 1891,   910), // Scottsbluff
		317 => array( 36, 0,  0, 2162,   683), // Aberdeen
		318 => array( 36, 0,  0, 2228,   732), // Watertown
		322 => array( 37, 0,  0, 2238,   510), // Grand Forks
		327 => array( 38, 0,  0, 1614,   436), // Great Falls
		331 => array( 38, 0,  0, 1589,   571), // Bozeman
		337 => array( 39, 0,  0, 1827,   715), // Gilette
		344 => array( 39, 0,  0, 1611,   884), // Rock Springs
		349 => array( 40, 0,  0, 1779,  1025), // Boulder
		353 => array( 40, 0,  0, 1797,  1164), // Pueblo
		360 => array( 41, 0,  0, 1731,  1548), // Roswell
		367 => array( 42, 0,  0, 1344,  1429), // Mesa
		372 => array( 44, 0,  0, 1218,  1207), // Henderson
		375 => array( 44, 0,  0, 1057,   879), // Reno
		384 => array( 43, 0,  0, 1469,   952), // Provo
		388 => array( 43, 0,  0, 1471,   873), // Ogden
		390 => array( 45, 0,  0, 1508,   715), // Idaho Falls
		393 => array( 45, 0,  0, 1320,   701), // Nampa
		407 => array( 47, 0,  0, 1093,   547), // Bend
		412 => array( 48, 0,  0,  948,   764), // Redding
		417 => array( 48, 0,  0,  893,  1008), // San Jose
		426 => array( 50, 0,  0,  312,  2123), // Kahului
		432 => array( 49, 0,  0,  195,   471), // Kodiak
		435 => array( 49, 0,  0,  205,    85), // Nome
		438 => array( 49, 0,  0,  449,    65), // Barrow
		506 => array(  2, 0,  0, 3458,   710)  // Berlin
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
		$this->sc = $this->loadImage('variants/Divided_States/resources/small_sc.png');	
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
		
	/* No need to draw the country flags for "neural-SC-territories if they get occupied by 
	** the country they should belong to
	*/
	public function countryFlag($terrID, $countryID)
	{
		if (isset($this->nsc[$terrID]) && ($this->nsc[$terrID] == $countryID)) return;
		parent::countryFlag($terrID, $countryID);
	}

}

class Divided_StatesVariant_drawMap extends NeutralScBox_drawMap {

	public function __construct($smallmap)
	{
		// Map is too big, so up the memory-limit
		parent::__construct($smallmap);
		ini_set('memory_limit',"408M");
		ini_set('max_execution_time', 300);
	}
	
	protected $countryColors = array(
         0=> array(226,198,158), /* Neutral */
		 1=> array(  0,200, 28), /* Maine */
		 2=> array(215, 57, 17), /* New-Hampshire */
		 3=> array(  0,101, 11), /* Vermont  */
		 4=> array(128, 91,124), /* Massachusetts */
		 5=> array(255,253, 51), /* Rhode-Island   */
		 6=> array(135,165,215), /* Connecticut  */
		 7=> array(196,150, 18), /* New-York  */
		 8=> array(238,247,130), /* New-Jersey */
		 9=> array( 41, 37,104), /* Pennsylvania */
		10=> array(238, 74,181), /* Delaware  */
		11=> array(115,113, 14), /* Maryland */
		12=> array(183,183,183), /* Virginia   */
		13=> array(  0,182,184), /* North-Carolina  */
		14=> array(162,  9, 50), /* South-Carolina  */
		15=> array(255,185, 86), /* Georgia */
		16=> array(197,251, 67), /* Florida */
		17=> array(111, 13,  3), /* Alabama  */
		18=> array(120, 14,136), /* Mississippi */
		19=> array(159,125, 78), /* Tennessee   */
		20=> array(255,145,214), /* Kentucky  */
		21=> array(104,104,104), /* West-Virginia  */
		22=> array(255,156,  0), /* Ohio */
		23=> array(  0,250, 44), /* Michigan */
		24=> array(212,  0,  0), /* Indiana  */
		25=> array(254,254,254), /* Illinois */
		26=> array(244,232, 26), /* Wisconsin   */
		27=> array(112, 63,145), /* Minnesota  */
		28=> array(118,139, 26), /* Iowa  */
		29=> array(176,154,145), /* Missouri */
		30=> array(207,250,250), /* Arkansas */
		31=> array( 57,108, 71), /* Louisiana  */
		32=> array( 90, 96,173), /* Texas */
		33=> array(235, 83,233), /* Oklahoma   */
		34=> array(215,149, 79), /* Kansas  */
		35=> array(159, 85, 15), /* Nebraska  */
		36=> array(  0,122,124), /* South-Dakota */
		37=> array(235,196, 58), /* North-Dakota */
		38=> array(230,105,116), /* Montana  */
		39=> array( 61,230,106), /* Wyoming */
		40=> array(224,198,221), /* Colorado   */
		41=> array(255,201,  0), /* New-Mexico  */
		42=> array(204,174, 88), /* Arizona  */
		43=> array(142,176,173), /* Utah */
		44=> array(255,241,190), /* Nevada */
		45=> array(186,183,108), /* Idaho  */
		46=> array(  0,181,107), /* Washington */
		47=> array( 10, 35,192), /* Oregon   */
		48=> array(109, 19,103), /* California  */
		49=> array(254,155,157), /* Alaska  */
		50=> array( 71,151,251), /* Hawaii */
		51=> array(226,198,158)  /* Neutral */
	);

	// The resources (map and default icons)
	protected function resources() {
		return array(
			'map'     =>'variants/Divided_States/resources/usphp1.png',
			'names'   =>'variants/Divided_States/resources/usnamesmap.png',
			'army'    =>'variants/Divided_States/resources/armyNeutral.png',
			'fleet'   =>'variants/Divided_States/resources/fleetNeutral.png',
			'standoff'=>'images/icons/cross.png'
		);
	}
	
}

?>