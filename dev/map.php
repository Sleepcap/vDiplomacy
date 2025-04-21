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
 
defined('IN_CODE') or die('This script can not be run by itself.');

// all possible parameters:

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

// view or edit data, or different confirm screens
if ($edit == true) // first check if the user can edit the map (set in dev.php)
	$edit = (isset($_REQUEST['edit'])) ? $_REQUEST['edit'] : 'newoff'; 

switch($edit) {
	case 'off':
	case 'on':
	case 'newon':
	case 'del_cache':
	case 'install':
	case 'del_terr':
	case 'calc_links':
	case 'save': break;
	default: $edit = 'newoff';
}

// new XY coordinates
$map_x = isset($_REQUEST['map_x']) ? (int)$_REQUEST['map_x'] : ''; // new X coordinate
$map_y = isset($_REQUEST['map_y']) ? (int)$_REQUEST['map_y'] : ''; // new Y coordinate

// new type (Land, Sea, Coast)
$type = (isset($_REQUEST['type'])) ? $_REQUEST['type'] : '';
switch($type) {
	case 'Land':
	case 'Coast':
	case 'Sea': break;
	default: $type = '';
}

// SupportCenter (Yes, No)
$sc = (isset($_REQUEST['sc'])) ? $_REQUEST['sc'] : ''; 
switch($sc) {
	case 'Yes':
	case 'No': break;
	default: $sc = '';
}

// new name
//$name = isset($_REQUEST['name']) ? str_replace ( "'" , '&#39;', $DB->escape($_REQUEST['name'])) : '';
$name = isset($_REQUEST['name']) ? htmlspecialchars($DB->escape($_REQUEST['name'],true), ENT_QUOTES) : '';

// new countryID
$countryID = isset($_REQUEST['countryID']) ? (int)$_REQUEST['countryID'] : '-1';

// calculate the coordinates for the largemap from the smallmap
$calcxy = (isset($_REQUEST['calcxy'])) ? $_REQUEST['calcxy'] : '';
switch($calcxy) {
	case 'terr':
	case 'all': break;
	default: $calcxy = '';
}

// calculate the links for the map
$calclinks = (isset($_REQUEST['calclinks'])) ? 'all' : ''; 

// change what units can pass a border
$set_link = (isset($_REQUEST['set_link'])) ? $_REQUEST['set_link'] : ''; 
switch(substr($set_link, 0, 2)) {
	case 'yn':  $set_link = 'yn'.(int)substr($set_link, 2); break; // Fleets only
	case 'ny':  $set_link = 'ny'.(int)substr($set_link, 2); break; // Armys only
	case 'yy':  $set_link = 'yy'.(int)substr($set_link, 2); break; // Fleets and Armys
	case 'nn':  $set_link = 'nn'.(int)substr($set_link, 2); break; // (delete link)
	default:    $set_link = '';
}
  
$new_link = (isset($_REQUEST['new_link'])) ? (int)$_REQUEST['new_link'] : ''; // add a new border
$del_terr = (isset($_REQUEST['del_terr'])) ? (int)$_REQUEST['del_terr'] : ''; // delte territory

// Zoomoffsets:
$zoom_x = (isset($_REQUEST['zoom_x'])) ? (int)$_REQUEST['zoom_x'] : '0'; // change the offset for the x coordinate if map is zoomed
$zoom_y = (isset($_REQUEST['zoom_y'])) ? (int)$_REQUEST['zoom_y'] : '0'; // change the offset for the y coordinate if map is zoomed

// VersionNumber (only numbers and dots)
$version = (isset($_REQUEST['version'])) ? $_REQUEST['version'] : ''; // Change the version number
$version = preg_replace('/[^0-9\.]/i', '', $version);

if (isset(Config::$hiddenVariants) && in_array($variantID,Config::$hiddenVariants) && $User->type['Guest'])
	$variantID = 0;			

if ($variantID != 0)
{
	global $Variant;
    $Variant = libVariant::loadFromVariantID($variantID);
    $mapID = $Variant->mapID;
    libVariant::setGlobals($Variant);
	
	if (!($User->type['Admin']) && $edit != 'off') {
		if (!isset(Config::$devs))
			$edit = 'off';
        elseif (!(array_key_exists($User->username, Config::$devs))) {
            $edit = 'off';
        } elseif (!(in_array(Config::$variants[$variantID], Config::$devs[$User->username]))) {
            $edit = 'off';
        }
    }
}

if ($edit == 'on')
	write_changes();
if ($variantID != 0)
	check_edit();
display_interface();

function write_changes() {
    global $DB, $mapID, $terrID, $countryID, $mapsize, $map_x, $map_y, $zoom_x, $zoom_y, $type, $sc, $name, $set_link, $new_link, $del_terr, $calcxy, $Variant, $calclinks;

    if ($map_x != '')
        $DB->sql_put('UPDATE wD_Territories SET ' . ($mapsize == "small" ? 'small' : '') . 'MapX=' . ($map_x + $zoom_x) . ' WHERE mapID=' . $mapID . ' AND id=' . $terrID);
    if ($map_y != '')
        $DB->sql_put('UPDATE wD_Territories SET ' . ($mapsize == "small" ? 'small' : '') . 'MapY=' . ($map_y + $zoom_y) . ' WHERE mapID=' . $mapID . ' AND id=' . $terrID);
    if ($type != '')
        $DB->sql_put('UPDATE wD_Territories SET type="' . $type . '" WHERE mapID=' . $mapID . ' AND id=' . $terrID);
    if ($sc != '')
        $DB->sql_put('UPDATE wD_Territories SET supply="' . $sc . '" WHERE mapID=' . $mapID . ' AND id=' . $terrID);
    if (($name != '') && ($terrID != '0'))
	{
        list($duplicate) = $DB->sql_row('SELECT COUNT(*) FROM wD_Territories WHERE mapID=' . $mapID . ' AND name="'.$name.'"');
		if ($duplicate == 0)
			$DB->sql_put('UPDATE wD_Territories SET name="' . $name . '" WHERE mapID=' . $mapID . ' AND id=' . $terrID);
    }
    if ($countryID >= 0 && $terrID != '0')
        $DB->sql_put('UPDATE wD_Territories SET countryID="' . $countryID . '" WHERE mapID=' . $mapID . ' AND id=' . $terrID);
    if (($name != '') && ($terrID == '0'))
	{
        list($duplicate) = $DB->sql_row('SELECT COUNT(*) FROM wD_Territories WHERE mapID=' . $mapID . ' AND name="'.$name.'"');
		if ($duplicate == 0)
		{
			list($terrID) = $DB->sql_row('SELECT id FROM wD_Territories WHERE mapID=' . $mapID . ' ORDER BY id DESC LIMIT 1;');
			$terrID++;
			$DB->sql_put('INSERT INTO wD_Territories (mapid,type,id,name,coastParentID) VALUES (' . $mapID . ',"Coast",' . $terrID . ',"' . $name . '",' . $terrID . ')');
		}
    }
    if ($set_link != '') {
        $toTerrID = substr($set_link, 2);
        $move = substr($set_link, 0, 2);
		list($check_last_link) = $DB->sql_row('SELECT COUNT(*) FROM wD_CoastalBorders WHERE mapID=' . $mapID);
		if (($move != 'nn') OR ($check_last_link > 2) )
		{
			$DB->sql_put('DELETE FROM wD_CoastalBorders WHERE toTerrID=' . $toTerrID . ' AND fromTerrID=' . $terrID . '   AND mapID=' . $mapID);
			$DB->sql_put('DELETE FROM wD_CoastalBorders WHERE toTerrID=' . $terrID . '   AND fromTerrID=' . $toTerrID . ' AND mapID=' . $mapID);
		}
        if ($move != 'nn') {
            if ($move == 'yn') {
                $move = '"Yes","No"';
            }
            if ($move == 'ny') {
                $move = '"No" ,"Yes"';
            }
            if ($move == 'yy') {
                $move = '"Yes","Yes"';
            }
            $sql = 'INSERT INTO wD_CoastalBorders (mapID,fleetsPass,armysPass,fromTerrID,toTerrID) VALUES (' . $mapID . ',' . $move . ',';
            $DB->sql_put($sql . $terrID . ',' . $toTerrID . ')');
            $DB->sql_put($sql . $toTerrID . ',' . $terrID . ')');
        }
    }
    if ($new_link != '') {
		if ($new_link == 9999)
		{
            $DB->sql_put('DELETE FROM wD_CoastalBorders WHERE mapID='.$mapID.' AND (fromTerrID='.$terrID.' OR toTerrID='.$terrID.')');
		}
		elseif ($new_link != $terrID)
		{
			$toTerrID = $new_link;
			list($toType) = $DB->sql_row('SELECT type FROM wD_Territories WHERE id=' . $toTerrID . ' AND mapID=' . $mapID);
			list($fromType) = $DB->sql_row('SELECT type FROM wD_Territories WHERE id=' . $terrID . ' AND mapID=' . $mapID);
			if (($toType == 'Sea') || ($fromType == 'Sea'))
				$move = '"Yes","No"';
			elseif (($toType == 'Land') || ($fromType == 'Land'))
				$move = '"No" ,"Yes"';
			else
				$move = '"Yes","Yes"';
			$sql = 'INSERT INTO wD_CoastalBorders (mapID,fleetsPass,armysPass,fromTerrID,toTerrID) VALUES (' . $mapID . ',' . $move . ',';
			$DB->sql_put($sql . $terrID . ',' . $toTerrID . ')');
			$DB->sql_put($sql . $toTerrID . ',' . $terrID . ')');
		}
    }
    if ($del_terr != '')
	{
		$DB->sql_put('DELETE FROM wD_CoastalBorders WHERE   toTerrID=' . $del_terr . ' AND mapID=' . $mapID);
		$DB->sql_put('DELETE FROM wD_CoastalBorders WHERE fromTerrID=' . $del_terr . ' AND mapID=' . $mapID);
		$DB->sql_put('DELETE FROM wD_Territories    WHERE         id=' . $del_terr . ' AND mapID=' . $mapID);
        $terrID = 0;
    }
    if ($calcxy != '') {
        list($sw, $sh) = getimagesize('variants/' . $Variant->name . '/resources/smallmap.png');
        list($lw, $lh) = getimagesize('variants/' . $Variant->name . '/resources/map.png');
        $multix = $lw / $sw;
        $multiy = $lh / $sh;
        if ($calcxy == 'terr')
            $query = "SELECT id,smallMapX,smallMapY FROM wD_Territories WHERE mapID=" . $mapID . " AND id=" . $terrID;
        else
            $query="SELECT id,smallMapX,smallMapY FROM wD_Territories WHERE mapID=" . $mapID . " AND MapX=0 AND MapY=0";
        $tabl = $DB->sql_tabl($query);
        while (list($id, $sx, $sy) = $DB->tabl_row($tabl)) {
            $x = (int) $sx * $multix;
            $y = (int) $sy * $multiy;
            $DB->sql_put('UPDATE wD_Territories SET MapX=' . $x . ',MapY=' . $y . ' WHERE mapID=' . $mapID . ' AND id=' . $id);
        }
    }
    if ($calclinks != '') {
        $img = imagecreatefrompng('variants/' . $Variant->name . '/resources/smallmap.png');
        $width = imagesx($img);
        $height = imagesy($img);
        $black = imagecolorallocate($img, 0, 0, 0);

        // make sure every territory has black borders:
        for ($x = 0; ($x < $width - 1); $x++) {
            for ($y = 0; ($y < $height - 1); $y++) {
                $col1 = imagecolorat($img, $x, $y);
                $col2 = imagecolorat($img, $x, $y + 1);
                $col3 = imagecolorat($img, $x + 1, $y);
                if (($col1 != $col2) && ($col1 != $black) && ($col2 != $black))
                    imagesetpixel($img, $x, $y, $black);
                if (($col1 != $col3) && ($col1 != $black) && ($col3 != $black))
                    imagesetpixel($img, $x, $y, $black);
            }
        }
        $query = "SELECT id,type,name,smallMapX,smallMapY FROM wD_Territories WHERE coastParentID=id AND mapID=" . $mapID;
        $tabl = $DB->sql_tabl($query);
        while (list($id, $type, $name, $x, $y) = $DB->tabl_row($tabl)) {
            if ($type == 'Sea') {
                $r = rand(0, 255);
                $g = rand(0, 255);
                $b = rand(0, 255);
                $col = imageColorAllocate($img, $r, $g, $b);
                imagefilltoborder($img, $x, $y, $black, $col);
            } else {
                $col = imagecolorat($img, $x, $y);
            }
            $calcID[$col] = $id;
            $calcType[$col] = $type;
        }

        $cols = array();
        $borders = array();
        for ($x = 1; ($x < $width - 1); $x++) {
            for ($y = 1; ($y < $height - 1); $y++) {
                if (imagecolorat($img, $x, $y) == $black) {
                    $cols[1] = imagecolorat($img, $x - 1, $y - 1);
                    $cols[2] = imagecolorat($img, $x, $y - 1);
                    $cols[3] = imagecolorat($img, $x + 1, $y - 1);
                    $cols[4] = imagecolorat($img, $x - 1, $y);
                    $cols[5] = imagecolorat($img, $x + 1, $y);
                    $cols[6] = imagecolorat($img, $x - 1, $y + 1);
                    $cols[7] = imagecolorat($img, $x, $y + 1);
                    $cols[8] = imagecolorat($img, $x + 1, $y + 1);
                    for ($i = 1; $i < 8; $i++) {
                        for ($j = 2; $j < 9; $j++) {
                            if (($cols[$i] != $cols[$j]) && ($cols[$i] != $black || $cols[$j] != $black)) {
                                if (!isset($borders[$cols[$i]]))
                                    $borders[$cols[$i]][0] = $cols[$j];
                                if (array_search($cols[$j], $borders[$cols[$i]]) === false)
                                    $borders[$cols[$i]][] = $cols[$j];
                            }
                        }
                    }
                }
            }
        }
        $DB->sql_put('DELETE FROM wD_CoastalBorders WHERE mapID=' . $mapID);
        foreach ($borders as $from => $toarray) {
            foreach ($toarray as $to) {
                if (isset($calcType[$from]) && isset($calcType[$to])) {
                    $fromType = $calcType[$from];
                    $toType = $calcType[$to];
                    if (($toType == 'Sea') || ($fromType == 'Sea'))
                        $move = '"Yes","No"';
                    elseif (($toType == 'Land') || ($fromType == 'Land'))
                        $move = '"No" ,"Yes"';
                    else
                        $move = '"Yes","Yes"';
                    $sql = 'INSERT INTO wD_CoastalBorders (mapID,fleetsPass,armysPass,fromTerrID,toTerrID) VALUES (' . $mapID . ',' . $move . ',';
                    $DB->sql_put($sql . $calcID[$from] . ',' . $calcID[$to] . ')');
                    $DB->sql_put($sql . $calcID[$to] . ',' . $calcID[$from] . ')');
                }
            }
        }
    }
}

// Check if we switch between the edit modes...
function check_edit() {
    global $DB, $Variant, $edit, $mapID, $terrID, $variantID, $version;

    $inst_dir = 'variants/' . $Variant->name . '/';
    $inst_file = $inst_dir . 'install.php';

    if ($edit == 'newoff') {
        $edit = 'off';
        if ($version != '' && $version != $Variant->codeVersion) {
            //read the variant.php
            $str = file_get_contents($inst_dir . 'variant.php');
            // replace the versionstring
            $str = str_replace("'" . $Variant->codeVersion . "'", "'" . $version . "'", $str);
            $fp = fopen($inst_dir . 'variant.php', 'w');
            //now, TOTALLY rewrite the file
            fwrite($fp, $str, strlen($str));
        }
        if (!(file_exists($inst_file))) {
            print '<li class="formlisttitle">ATTENTION: Map Data for variant "' . $Variant->name . '" already in editor. Always save after you leave the editor. </li>';
            print display_button_form('edit', 'on', 'Continue to edit.');
            print '<hr>';
            libHTML::footer();
            exit;
        }
    } elseif ($edit == 'newon') {
        $edit = 'on';
        if (file_exists($inst_file)) {
            copy($inst_file, $inst_dir . 'cache/'.date("ymd-His").'-install.php');
            rename($inst_file, $inst_dir . 'cache/install-backup.php');
        } else {
            print 'ATTENTION: Map Data for variant "' . $Variant->name . '" already in editor.</li>';
            print display_button_form('edit', 'del_cache', 'Discard changes and exit.');
            print ' - ';
            print display_button_form('edit', 'on', 'Back to edit.');
            print '<hr>';
            libHTML::footer();
            exit;
        }
    } elseif ($edit == 'del_cache') {
        rename($inst_dir . 'cache/install-backup.php', $inst_file);
        unlink($inst_dir . 'cache/data.php');
		$del_files = glob($inst_dir.'cache/territories*.js');
		foreach ($del_files as $v) unlink($v);
        $edit = 'off';
        $terrID = '0';
        print '<li class="formlisttitle">ATTENTION: Old install.php for variant "' . $Variant->name . '" restored.</li>';
        print display_button_form('edit', 'newoff', 'Click here to clear editor-cache and reload "live" data');
        print '<hr>';
        libHTML::footer();
        exit;
    } elseif ($edit == 'install') {
		$noInstall = array();
		
		// Check for 2 territories
		list($check_last_terr) = $DB->sql_row('SELECT COUNT(*) FROM wD_Territories WHERE mapID=' . $mapID);
		if ($check_last_terr < 2)
			$noInstall[] = 'There needs to be at least 2 territories on your map.';
		
		// check for 1 SC
		list($check_last_sc) = $DB->sql_row('SELECT COUNT(*) FROM wD_Territories WHERE supply="Yes" AND mapID=' . $mapID);
		if ($check_last_sc < 1)
			$noInstall[] = 'There needs to be at least 1 supplycenter on your map.';
		
		// check for 1 countryID
		list($check_last_country) = $DB->sql_row('SELECT COUNT(*) FROM wD_Territories WHERE countryID != 0 AND mapID=' . $mapID);
		if ($check_last_country < 1)
			$noInstall[] = 'There needs to be at least 1 countryID on your map.';
		
		// check for 1 border
		list($check_last_link) = $DB->sql_row('SELECT COUNT(*) FROM wD_CoastalBorders WHERE mapID=' . $mapID);
		if ($check_last_link < 1)
			$noInstall[] = 'There needs to be at least 1 border/link on your map.';

		// check for coasts without main-territory
		$tabl = $DB->sql_tabl("SELECT id, name FROM wD_Territories WHERE name LIKE '% Coast)%' AND mapID=" . $mapID);
		while ( list($id, $name) = $DB->tabl_row($tabl) )
		{
			$mainTerrName=substr($name, 0, strrpos($name," ("));
			list($check_mainTerritoryID) = $DB->sql_row(
				"SELECT id FROM wD_Territories WHERE name = '".$mainTerrName."' AND mapID=" . $mapID);
			if ($check_mainTerritoryID == 0)
				$noInstall[] = 'There needs to be a main-territory for "'.$name.'" (id='.$id.').';
			if ($check_mainTerritoryID > $id)
				$noInstall[] = 'Problem with "'.$name.'". The id for the main-territory ('.$check_mainTerritoryID.') is greater than the territoryID ('.$id.').';
		}
		
		if (count($noInstall) > 0) {
			print '<li class="formlisttitle">ATTENTION: Unable to write install.php.</li>';
			foreach ($noInstall as $errorText)
				print '<li class="formlisttitle">'.$errorText.'</li>';
			$edit = 'on';
			print '<li class="formlisttitle">';
			print display_button_form('edit', 'on', 'Back to edit-mode.');
			print '<hr>';
			libHTML::footer();
			exit;
		}
        $handle = fopen($inst_dir . 'install-new.php', 'w');
        foreach (generate_install () as $line) {
            $line .= "\n";
            $line = remove_html($line);
            fwrite($handle, $line);
        }
        fclose($handle);
        rename($inst_dir . 'install-new.php', $inst_file);
        unlink($inst_dir . 'cache/install-backup.php');
		unlink($inst_dir . 'cache/data.php');
		$del_files = glob($inst_dir.'cache/territories*.js');
		foreach ($del_files as $v) unlink($v);
		$del_files = glob($inst_dir.'cache/*.png');
		foreach ($del_files as $v) unlink($v);
        print '<li class="formlisttitle">ATTENTION: New install.php for variant "' . $Variant->name . '" written.</li>';
        print 'Version-number: ';
        $html = '<form style="display: inline" method="get" name="edit">';
        $html .= add_form_defaults();
        $html .= '<input type="text" name="version" value="' . $Variant->codeVersion . '" size="3"> - ';
        $html .= '<input type="hidden" name="edit" value="newoff">';
        $html .= '<input type="submit" class="form-submit" value="Click here to clear editor-cache, and reload "live" data" />';
        $html .= '</form>';
        print $html;
        print '<hr>';
        libHTML::footer();
        exit;
    } elseif ($edit == 'del_terr') {
        print '
			<li class="formlisttitle">
				ATTENTION:Deletion of a territory will rearange all IDs. This will screw all games using your variant.<br>
				Use this function only if your are in development of a new variant, or if there are no games going on at the moment.
			</li>
			<li class="formlisttitle">
				If there are older games of your variant in the database the cached maps will be allright, but the orderhistory will be screwed too.
			</li>
			<li class="formlisttitle">
				Your install-file needs at least <b>2 territories, 1 supplycenter and one border-link</b>.
			</li>
			<li class="formlisttitle">';
        $edit = 'on';
        print display_button_form('del_terr', $terrID, 'Yes I\'m sure, delete territory.');
        print ' - ';
        print display_button_form('edit', 'on', 'No, keep the territory in the database.');
        print '<hr>';
        libHTML::footer();
        exit;
    } elseif ($edit == 'calc_links') {
        print '<li class="formlisttitle">ATTENTION:
			<li class="formlisttitle">This will now recalculate all of your borderlinks. Old data will be deleted and new links will be created
			</li><li class="formlisttitle">Please remember: <ul>
			</li><li class="formlisttitle">You need a finished smallmap
			</li><li class="formlisttitle">Max 250 territories on your map
			</li><li class="formlisttitle">All coordinates must be set for the smallmap
			</li><li class="formlisttitle">It does not create links for Coasts (and links everything to the parrent-territory)
			</li><li class="formlisttitle">The island-territories usually get wrong links.
			</li><li class="formlisttitle">It does guess the bordertypes. This does not work always correct.
			</li><li class="formlisttitle">It does create links for all neighbour territories. This might not enough or too much, depending on your map.
			</li></ul></li>
			</li><li class="formlisttitle">Is does not save the data in the install.php. You need to save as usuall.
			</li><li class="formlisttitle">If you have edited other Variant-data, you might cancel now and save your data bevore proceeding.
			</li><li class="formlisttitle"><u>If you do not like the result you can turn edit mode off and restore the old data.</u>
			<li class="formlisttitle">';
        $edit = 'on';
        print display_button_form('calclinks', 'all', 'Yes I\'m sure, recalculate everthing.');
        print ' - ';
        print display_button_form('edit', 'on', 'No, do not change the links.');
        print '<hr>';
        libHTML::footer();
        exit;
    }
}

function display_interface()
{

    global $selectVariantForm, $DB, $Variant, $variantID, $mapID, $terrID, $mode, $mapsize, $mapmode, $edit, $User;

    //MapID
	print '<b>Variant: '.$selectVariantForm.'</b> <div class="hr"></div>';

    // Print main menues:
    if ($variantID != 0 && $edit != 'save')
	{
		print '<li class="formlisttitle">';
		
		// Mapsize
		print 'Mapsize: ';
		print display_select_form('mapsize', array('small' => 'Smallmap', 'large' => 'Largemap'), $mapsize);
		print display_select_form('mapmode', array('all' => 'All', 'zoom' => 'Zoom'), $mapmode);

		// Mode
		print ' - Mode: ';
		print display_select_form('mode', array('all' => 'All', 'units' => 'Territories', 'links' => 'Borders','none' => 'Init'), $mode);

		// Edit
		print ' - Edit: ';
		if ($edit == 'off')
			print display_select_form('edit', array('off' => 'Off', 'newon' => 'On'), $edit);
		else
			print display_select_form('edit', array('on' => 'On', 'save' => 'Exit / Save'), $edit);
		print '</li>';

		//TerrID
		$all_terr = array();
		$tabl = $DB->sql_tabl('SELECT id,name FROM wD_Territories WHERE mapID=' . $mapID);
		$all_terr['0'] = '(all/new)';
		while (list($id, $name) = $DB->tabl_row($tabl))
			$all_terr[$id] = $name;
		asort($all_terr);
		// A bit complex code to skip "empty" terrID's during development (eg. after deleting an territory).
		$all_id = array_keys($all_terr);   // get all ID's
		sort($all_id);                     // sort them
		$last = array_push($all_id, '0', '1'); // push the "0" and the "1" at the end
		$all_id[0] = "--";                   // replace the "0" with fake data
		$ind = array_search($terrID, $all_id); // search for the index postiton
		$all_id[0] = '0';                    // overwrite the fake "0" with the last one to make a circle
		print '<li class="formlisttitle">Terr-ID: ';
		if ($ind > 0)
			print display_button_form('terrID', $all_id[$ind - 1], '-');
		print display_text_form('terrID', $terrID, 1);
		print display_button_form('terrID', $all_id[$ind + 1], '+');
		print ' ';
		print display_select_form('terrID', $all_terr, $terrID);
		if (($edit == 'on') && ($mode == 'units')) {
			if ($terrID == 0) {
				print ' - New Territory: ';
				print display_text_form('name', '', 30);
			} else {
				print ' - Change Name: ';
				print display_text_form('name', $all_terr[$terrID], 25);
				print ' - ';
				print display_button_form('edit', 'del_terr', '-=> Delete <=-');
			}
		}
		print '</li>';

		// Territory information
		if ($terrID != '0')
		{
			// Get values from database
			list($type, $supply, $countryID, $x, $y, $sx, $sy) = $DB->sql_row('SELECT type,supply,countryID,mapX,mapY,smallMapX,smallMapY FROM wD_Territories WHERE mapID=' . $mapID . ' AND id=' . $terrID);

			//Landtype + Supply-Centers + initial occupation
			print '<li class="formlisttitle">Type: ';
			if ($edit != 'on')
				print '<span style="font-weight: normal;">'.$type.'</span>';
			else
				print display_select_form('type', array('Land' => 'Land', 'Coast' => 'Coast', 'Sea' => 'Sea'), $type);
				
			print ' - Supply: ';
			if ($edit != 'on')
				print '<span style="font-weight: normal;">'.$supply.'</span>';
			else
				print display_select_form('sc', array('Yes' => 'Yes', 'No' => 'No'), $supply);
				
			print ' - Initial country: ';
			array_unshift($Variant->countries, "Neutral");
			if ($countryID >= count($Variant->countries))
				$Variant->countries[$countryID] = 'Special ID: ' . $countryID;
				
			if ($edit != 'on')
				print '<span style="font-weight: normal;">'.$Variant->countries[$countryID].'</span>';
			else
				print display_select_form('countryID', $Variant->countries, $countryID). ' ID: ' . display_text_form('countryID', $countryID, 1);
			
			print '</li>';

			// Coordinates:
			if ($mode == 'units')
			{
				print '<li class="formlisttitle">';
				if ($mapsize == 'large') {
					print 'MapX: ';
					if ($edit != 'on')
						print '<span style="font-weight: normal;">'.$x.'</span>';
					else
					{
						print display_button_form('map_x', ($x - 5), '-5');
						print display_button_form('map_x', ($x - 1), '-1');
						print display_text_form('map_x', $x);
						print display_button_form('map_x', ($x + 1), '+1');
						print display_button_form('map_x', ($x + 5), '+5');
					}
					print ' - MapY: ';
					if ($edit != 'on')
						print '<span style="font-weight: normal;">'.$y.'</span>';
					else
					{
						print display_button_form('map_y', ($y - 5), '-5');
						print display_button_form('map_y', ($y - 1), '-1');
						print display_text_form('map_y', $y);
						print display_button_form('map_y', ($y + 1), '+1');
						print display_button_form('map_y', ($y + 5), '+5');
						print ' - Calculate: ';
						print display_button_form('calcxy', 'terr', 'territory');
						print display_button_form('calcxy', 'all', 'all unset');
					}
				} else {
					print 'SmallMapX: ';
					if ($edit != 'on')
						print '<span style="font-weight: normal;">'.$sx.'</span>';
					else
					{
						print display_button_form('map_x', ($sx - 1), '-');
						print display_text_form('map_x', $sx);
						print display_button_form('map_x', ($sx + 1), '+');
					}
					print ' - SmallMapY: ';
					if ($edit != 'on')
						print '<span style="font-weight: normal;">'.$sy.'</span>';
					else
					{
						print display_button_form('map_y', ($sy - 1), '-');
						print display_text_form('map_y', $sy);
						print display_button_form('map_y', ($sy + 1), '+');
					}
				}
				print '</li>';
			}
		}

		// Link-list
		if ($mode == 'links'|| $mode == 'all')
		{
			if ($terrID != '0') {
				$tabl = $DB->sql_tabl('SELECT a.id,a.name, armysPass, fleetsPass FROM wD_CoastalBorders c
					INNER JOIN wD_Territories a ON ( toTerrID=a.id ) WHERE c.fromTerrID=' . $terrID . ' AND a.mapID=' . $mapID . ' AND c.mapID=' . $mapID . ' ORDER BY a.name ASC');
					
				print '<li class="formlisttitle">Links:</li><table>';
				while (list($toTerrID, $toTerrName, $armysPass, $fleetsPass) = $DB->tabl_row($tabl)) {
					if (($fleetsPass == 'Yes') && ($armysPass == 'No')) {
						$def = 'yn' . $toTerrID;
						$deftxt = 'Fleets only';
					}
					if (($fleetsPass == 'No') && ($armysPass == 'Yes')) {
						$def = 'ny' . $toTerrID;
						$deftxt = 'Armys only';
					}
					if (($fleetsPass == 'Yes') && ($armysPass == 'Yes')) {
						$def = 'yy' . $toTerrID;
						$deftxt = 'Fleets and Armys';
					}
					
					unset($all_terr[$toTerrID]);
					
					if ($edit != 'on')
						print "<TR><TD style='padding:0;'>" . display_button_form('terrID', $toTerrID, $toTerrName) . " </TD>
						<TD style='padding:0; width:100%'><b>=> </b> ".$deftxt.'</TD></TR>';
					else
						print "<TR><TD style='padding:0;'>" . display_button_form('terrID', $toTerrID, $toTerrName) .
								" </TD><TD style='padding:0; width:100%'>=> " .display_select_form('set_link', array(
									'yn' . $toTerrID => 'Fleets only',
									'ny' . $toTerrID => 'Armys only',
									'yy' . $toTerrID => 'Fleets and Armys',
									'nn' . $toTerrID => '(delete link)'), $def) . '</TD></TR>';
				}
				print '</table>';
				
				if ($edit == 'on')
				{
					print '<li class="formlisttitle">Add Link: ';
					$all_terr['9999']='(delete all links)';
					print display_select_form('new_link', $all_terr, '');
					print '</li>';
				}
			} elseif ($edit == 'on') {
				print '<li class="formlisttitle">Expermental: ';
				print display_button_form('edit', 'calc_links', '(re-)calculate all borderlinks!');
				print '</li>';
			}
		}

		// Display Map:
		print '</div>';
		$zoomstr = '';
		if ($mapmode == 'zoom' && ($terrID > 0))
		{
			$sql = 'SELECT ' . ($mapsize == 'small' ? 'small' : '') . 'MapX, ' . ($mapsize == 'small' ? 'small' : '') . 'MapY
						FROM wD_Territories WHERE mapID=' . $mapID . ' AND id=' . $terrID;
			list($x, $y) = $DB->sql_row($sql);
			if ($x > 0 && $y > 0) {
				$imgSrc = 'variants/' . $Variant->name . '/resources/' . ($mapsize == 'small' ? 'small' : '') . 'map.png';
				
				// If there is no smallmap we will only use the largemap...
				if (!(file_exists ($imgSrc)))
					$imgSrc = 'variants/' . $Variant->name . '/resources/map.png';

				list($width, $height) = getimagesize($imgSrc);

				if (($x < 300) || ($width < 600))
					$x = 0;
				elseif ($x > ($width - 300))
					$x = $width - 600;
				else
					$x=$x - 300;

				if (($y < 150) || ($height < 300))
					$y = 0;
				elseif ($y > ($height - 150))
					$y = $height - 300;
				else
					$y=$y - 150;
				$zoomstr = "&mapmode=zoom&zoom_x=" . $x . "&zoom_y=" . $y;
			}
		}
		if (($mode == 'units') && ($terrID != '0') && ($edit == 'on')) {
			print '<form action="" method=get>';
			print add_form_defaults();
			if ($zoomstr != '') {
				print '<input type="hidden" name="zoom_x" value="' . $x . '">';
				print '<input type="hidden" name="zoom_y" value="' . $y . '">';
			}
			print '<input style=cursor:crosshair type="image" name=';
			print 'map onError="if ((this.src.match(/X/g)||[]).length < 5) this.src=this.src + \'X\'; else this.src = \'images/icons/alert.png\';" src="dev/map_draw.php?terrID=' . $terrID . '&variantID=' . $variantID . '&mode=' . $mode . '&mapsize=' . $mapsize . $zoomstr . '&nocache=' . (rand(1, 9999)) . '" >';
			print '</form>';
		} else {
			 print '<img onError="if ((this.src.match(/X/g)||[]).length < 5) this.src=this.src + \'X\'; else this.src = \'images/icons/alert.png\';" src="dev/map_draw.php?terrID=' . $terrID . '&variantID=' . $variantID . '&mode=' . $mode . '&mapsize=' . $mapsize . $zoomstr . '&nocache=' . (rand(1, 9999)) . '" >';
	   }
		print '<div align="center"> If you can\'t see the map <b><a href="dev.php?viewErrorLog">click here</a></b> to view the error message.</div>';
	}

    // Show Data
    if ($edit == 'save')
  		print '<li class="formlisttitle">Save your current data to install.php?</li>'.
					display_button_form('edit', 'install', 'Write install.php and exit.').' - '.
					display_button_form('edit', 'on', 'Back to edit.').' - '.
					display_button_form('edit', 'del_cache', 'Discard changes and exit.').
				'</li>';
}

function add_form_defaults() {
    global $variantID, $mode, $mapsize, $mapmode, $terrID, $edit;
    return '
		<input type="hidden" name="tab"       value="Map">
		<input type="hidden" name="variantID" value="' . $variantID . '">
		<input type="hidden" name="mode"      value="' . $mode . '">
		<input type="hidden" name="edit"      value="' . $edit . '">
		<input type="hidden" name="mapsize"   value="' . $mapsize . '">
		<input type="hidden" name="mapmode"   value="' . $mapmode . '">
		<input type="hidden" name="terrID"    value="' . $terrID . '">';
}

function display_button_form($name, $value, $display_name) {
	return '
	<form style="display: inline" method="get" name="' . $name . '">
		'.add_form_defaults().'
		<input type="hidden" name="' . $name . '" value="' . $value . '">
		<input type="submit" class="form-submit" value="' . $display_name . '" />
	</form>';
}

function display_select_form($name, $options=array(), $default='', $showID=false) {
    $html = '<form style="display: inline" method="get" name="set_map">';
    $html .= add_form_defaults();
    $html .= '<select name=' . $name . ' onchange="this.form.submit();">';
    foreach ($options as $id => $name) {
        $html .= '<option value="' . $id . '"';
        if ($id == $default)
            $html .= ' selected';
        $html .= '>' . $name;
        if ($showID == true)
            $html .= ' (' . $id . ')';
        $html .= '</option>';
    }
    $html .='</select></form>';
    return $html;
}

function display_text_form($name, $value, $size=2) {
	return '
	<form style="display: inline" method="get" name="' . $name . '">
		'.add_form_defaults().'
		<input type="text" name="' . $name . '" value="' . $value . '" size="' . $size . '">
	</form>';
}

function remove_html($text) {
    $text = str_replace("&amp;", "&", $text);
    $text = str_replace("&uuml;", "ü", $text);
    $text = str_replace("&ouml;", "ö", $text);
    $text = str_replace("&auml;", "ä", $text);
    $text = str_replace("&Uuml;", "Ü", $text);
    $text = str_replace("&Auml;", "Ä", $text);
    $text = str_replace("&Ouml;", "Ö", $text);
    $text = str_replace("&szlig;", "ß", $text);
    $text = str_replace("&nbsp;", "\t", $text);
    return $text;
}

function generate_install() {

    global $DB, $Variant;

    $installPHP = array();
    $installPHP[] = '<?php';
    $installPHP[] = '// This is file installs the map data for the ' . $Variant->name . ' variant';
    $installPHP[] = "defined('IN_CODE') or die('This script can not be run by itself.');";
    $installPHP[] = 'require_once("variants/install.php");';
    $installPHP[] = '';
    $installPHP[] = 'InstallTerritory::$Territories=array();';
    $installPHP[] = '$countries=$this->countries;';
    $installPHP[] = '$territoryRawData=array(';

    $tabl = $DB->sql_tabl("SELECT w.name, w.type, w.supply, w.countryID, w.mapX, w.mapY, w.smallMapX, w.smallMapY FROM wD_Territories w WHERE w.mapID=" . $Variant->mapID . " ORDER BY w.id");
    while (list($name, $type, $supply, $countryID, $mapX, $mapY, $smallMapX, $smallMapY) = $DB->tabl_row($tabl)) {
        $name = $DB->escape($name);
        $name = str_replace('\\', '\\\\\\', $name);
		if (strpos($name,' Coast)') !== false)
		{
			$supply = 'No';
			$type = 'Coast';
		}
        $installPHP[] = "&nbsp;array('$name', '$type', '$supply', $countryID, $mapX, $mapY, $smallMapX, $smallMapY),";
    }

    $installPHP[count($installPHP) - 1] = substr($installPHP[count($installPHP) - 1], 0, -1);
    $installPHP[] = ');';
    $installPHP[] = '';
    $installPHP[] = 'foreach($territoryRawData as $territoryRawRow)';
    $installPHP[] = '{';
    $installPHP[] = '&nbsp;list($name, $type, $supply, $countryID, $x, $y, $sx, $sy)=$territoryRawRow;';
    $installPHP[] = '&nbsp;new InstallTerritory($name, $type, $supply, $countryID, $x, $y, $sx, $sy);';
    $installPHP[] = '}';
    $installPHP[] = 'unset($territoryRawData);';
    $installPHP[] = '';
    $installPHP[] = '$bordersRawData=array(';

    $tabl = $DB->sql_tabl('SELECT a.name, b.name, armysPass, fleetsPass FROM wD_CoastalBorders c
		INNER JOIN wD_Territories a ON ( fromTerrID=a.id ) INNER JOIN wD_Territories b ON ( toTerrID=b.id )
		WHERE a.mapID=' . $Variant->mapID . ' AND b.mapID=' . $Variant->mapID . ' AND c.mapID=' . $Variant->mapID);
    while (list($fromTerrName, $toTerrName, $armysPass, $fleetsPass) = $DB->tabl_row($tabl)) {
        $fromTerrName = $DB->escape($fromTerrName);
        $fromTerrName = str_replace('\\', '\\\\\\', $fromTerrName);
        $toTerrName = $DB->escape($toTerrName);
        $toTerrName = str_replace('\\', '\\\\\\', $toTerrName);
        if (array_search("&nbsp;array('$toTerrName','$fromTerrName','$fleetsPass','$armysPass'),", $installPHP) === false)
            $installPHP[] = "&nbsp;array('$fromTerrName','$toTerrName','$fleetsPass','$armysPass'),";
    }
    $installPHP[count($installPHP) - 1] = substr($installPHP[count($installPHP) - 1], 0, -1);

    $installPHP[] = ');';
    $installPHP[] = '';
    $installPHP[] = 'foreach($bordersRawData as $borderRawRow)';
    $installPHP[] = '{';
    $installPHP[] = '&nbsp;list($from, $to, $fleets, $armies)=$borderRawRow;';
    $installPHP[] = '&nbsp;InstallTerritory::$Territories[$to]  ->addBorder(InstallTerritory::$Territories[$from],$fleets,$armies);';
    $installPHP[] = '}';
    $installPHP[] = 'unset($bordersRawData);';
    $installPHP[] = '';

    // Check for custom footer
    $handle = fopen('variants/' . $Variant->name . '/cache/install-backup.php', 'r');
    while ((strpos(fgets($handle), 'Custom footer') === false) && (!(feof($handle)))) {
        
    }
    if (!feof($handle)) {
        $installPHP[] = '// Custom footer not changed by edit tool';
        while ((!feof($handle))) {
            $line = rtrim(fgets($handle));
            $line = str_replace("\t", "&nbsp;", $line);
            $installPHP[] = $line;
        }
    } else {
        $installPHP[] = 'InstallTerritory::runSQL($this->mapID);';
        $installPHP[] = 'InstallCache::terrJSON($this->territoriesJSONFile(),$this->mapID);';
        $installPHP[] = '?>';
    }
    fclose($handle);

    return $installPHP;
}

?>