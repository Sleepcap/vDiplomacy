<?php
/*
    Copyright (C) 2018 Oliver Auth

	This file is part of vDiplomacy.

    vDiplomacy is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    vDiplomacy is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with webDiplomacy.  If not, see <http://www.gnu.org/licenses/>.
 */

define('DELETECACHE', 1);

chdir ('../');
require_once('header.php');
require_once('map/drawMap.php');
ini_set('memory_limit', '14M');

// all possible parameters:

// The variantID for the map
$variantID = (isset($_REQUEST['variantID'])) ? (int)$_REQUEST['variantID'] : '0'; 
if (isset(Config::$hiddenVariants) && in_array($variantID,Config::$hiddenVariants) && $User->type['Guest'])	$variantID = 0;			
if ($variantID == 0) die('variantID not provided; cannot draw map');

// Global or only one territory
$terrID = (isset($_REQUEST['terrID'])) ? (int)$_REQUEST['terrID'] : '0';      

// zoom map or view all
$mapmode = ((isset($_REQUEST['mapmode']) && $_REQUEST['mapmode'] == 'zoom') ? 'zoom' : 'all');

// large or smallmap
$mapsize = ((isset($_REQUEST['mapsize']) && $_REQUEST['mapsize'] == 'large')) ? 'large' : 'small';  

// border or territory data
$mode = (isset($_REQUEST['mode'])) ? $_REQUEST['mode'] : 'all';   
switch($mode) {
	case 'none':
	case 'units':
	case 'links': break;
	default: $mode = 'all';
}

// Zoomoffsets:
$zoom_x = (isset($_REQUEST['zoom_x'])) ? (int)$_REQUEST['zoom_x'] : '0'; // change the offset for the x coordinate if map is zoomed
$zoom_y = (isset($_REQUEST['zoom_y'])) ? (int)$_REQUEST['zoom_y'] : '0'; // change the offset for the y coordinate if map is zoomed

/*
 * Setup the variant-data and drawmap routines...
 */
 
global $Variant;
$Variant = libVariant::loadFromVariantID($variantID);
$mapID = $Variant->mapID;
libVariant::setGlobals($Variant);
	
// Load the drawMap object for the given map type
if ($mapsize == 'large')
	$drawMap = $Variant->drawMap(false);
else
	$drawMap = $Variant->drawMap(true);

// Draw TerrStatus
$tabl = $DB->sql_tabl("SELECT id, type, countryID, MapX, SmallMapX FROM wD_Territories WHERE (coast='No' OR coast='Parent') AND type!='Sea' AND mapID=" . $mapID);
while (list($id, $type, $countryID, $x, $sx) = $DB->tabl_row($tabl)) {
	if ((($x != 0) && ($mapsize == 'large')) || (($sx != 0) && ($mapsize == 'small'))) {
		if ($mode == 'none')
			$drawMap->colorTerritory($id, $countryID);
		elseif ($id == $terrID)
			$drawMap->colorTerritory($id, 2);
		else
			$drawMap->colorTerritory($id, 0);
	}
}
if (($mode == 'none') && ($terrID != '0')) {
	list($type, $coast) = $DB->sql_row('SELECT type,coast FROM wD_Territories WHERE id=' . $terrID . ' AND mapID=' . $mapID);
	if (($type == 'Sea') || (($coast != 'Parent') && ($coast != 'No')))
		$drawMap->countryFlag($terrID, 2);
}

 // Draw links
if ($mode == 'links' || $mode == 'all') {
	$sql = "SELECT fromTerrID, toTerrID, fleetsPass, armysPass
		  FROM wD_CoastalBorders
		  WHERE mapID=" . $mapID;
	if ($terrID != '0')
		$sql .= " AND (fromTerrID=" . $terrID . " OR toTerrID=" . $terrID . ")";
	$tabl = $DB->sql_tabl($sql);
	while ($row = $DB->tabl_hash($tabl)) {
		if ($row['fleetsPass'] == 'Yes' && $row['armysPass'] == 'Yes')
			$drawMap->drawSupportHold($row['fromTerrID'], $row['toTerrID'], $row['toTerrID'], true);
		else if ($row['fleetsPass'] == 'Yes')
			$drawMap->drawSupportMove($row['fromTerrID'], $row['toTerrID'], $row['toTerrID'], true);
		else if ($row['armysPass'] = 'Yes')
			$drawMap->drawMove($row['fromTerrID'], $row['toTerrID'], $row['toTerrID'], true);
	}
}

// Draw units:
if ($mode == 'units' || $mode == 'all') {
	$sql = "SELECT id, type, countryID, coast, supply, MapX, SmallMapX
		  FROM wD_Territories
		  WHERE mapID=" . $mapID;
	if ($terrID != "0")
		$sql .= " AND id=" . $terrID;
	$tabl = $DB->sql_tabl($sql);
	while (list($terr, $terrType, $country, $coast, $supply, $x, $sx) = $DB->tabl_row($tabl)) {
		if ((($x != 0) && ($mapsize == 'large')) || (($sx != 0) && ($mapsize == 'small'))) {
			if (($coast == "No" || $coast == "Parent") && $terrType != "Sea")
				$unitType = 'Army';
			else
				$unitType = 'Fleet';
			if (($supply == 'Yes') || ($terrType == 'Sea'))
				$drawMap->countryFlag($terr, 1);
			$drawMap->addUnit($terr, $unitType, $country);
		}
	}
}

$drawMap->addTerritoryNames();
$drawMap->write(libVariant::cacheDir($Variant->name) . '/mappertool.png');

$img = imagecreatefrompng (libVariant::cacheDir($Variant->name) . '/mappertool.png');
if (imageistruecolor($img))
{
	$drawMap->caption("ATTENTION: Image must be indexed/palette PNG.");
	$drawMap->write(libVariant::cacheDir($Variant->name) . '/mappertool.png');
}
imagedestroy($img);

if ($mapmode == 'zoom')
{
	$imgSrc = 'variants/' . $Variant->name . '/cache/mappertool.png';
	$img = imagecreatefrompng($imgSrc);
	$zoomImg = imagecreate(600, 300);
	imagecopy($zoomImg, $img, 0, 0, $zoom_x, $zoom_y, 600, 300);
	imagepng($zoomImg, $imgSrc);
}

unset($drawMap); // $drawMap is memory intensive and should be freed as soon as no longer needed
libHTML::serveImage(libVariant::cacheDir($Variant->name) . '/mappertool.png');

?>