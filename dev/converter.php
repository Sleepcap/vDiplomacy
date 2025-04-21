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
    along with vDiplomacy.  If not, see <http://www.gnu.org/licenses/>.
*/

defined('IN_CODE') or die('This script can not be run by itself.');

print '<b>Variant: '.$selectVariantForm.'</b>';

//$edit = false;
if ($edit != true)
{
	print "<b> - Only sysadmins and devs can use the Converter tool.</b></div>";
	libHTML::footer();
	exit;
}


if (isset($_REQUEST['SubmitRP'])) {

	$coasts = array("n"=>"North", "s"=>"South", "e"=>"East", "w"=>"West");

	$cntfile = ($_FILES['cntfile']['name'] != "" ? file_get_contents($_FILES['cntfile']['tmp_name']) : '');
	$mapfile = ($_FILES['mapfile']['name'] != "" ? file_get_contents($_FILES['mapfile']['tmp_name']) : '');
	$gamfile = ($_FILES['gamfile']['name'] != "" ? file_get_contents($_FILES['gamfile']['tmp_name']) : '');
	$rgnfile = ($_FILES['rgnfile']['name'] != "" ? file_get_contents($_FILES['rgnfile']['tmp_name']) : '');
	$bmpfile = ($_FILES['bmpfile']['name'] != "" ? file_get_contents($_FILES['bmpfile']['tmp_name']) : '');
	$smallfact = (isset($_REQUEST['smallmapfactor']) ? $_REQUEST['smallmapfactor'] : '1');
	$largefact = (isset($_REQUEST['mapfactor'])      ? $_REQUEST['mapfactor']      : '1');

	print '<div class="hr"></div>';
	
	// Get powers
	if ($cntfile != '')
	{
		$powers=array();
		$cntfile = preg_replace("/#.+/", "", $cntfile); // Remove comments;
		$lines = explode("\n", $cntfile);
		foreach ($lines as $cntline)
		{
			$line = explode(" ", $cntline);
			if (count($line) > 3)
				$powers[$line[2]]=$line[0];
		}

		print '<b>cnt-file:</b><br>Found <b>'.count($powers).'</b> countries:<br>
				public $countries=array(\''.implode("', '",$powers).'\');';
				
		print '<div class="hr"></div>';
	}
	
	// Get territory and border information:
	if ($mapfile != '')
	{
		print '<b>map-file:</b><br>';
		if (!(isset($powers)))
		{
			print '<b>No cnt-file supplied. Using the 1-letter country shortcut as owner!<br>Make sure to adjust the variant.php or the install.php.</b><br>';
			$powers = array();
		}

		$phases = array("territories", "connections","not used","not used");
		$connections = array(); $phase = 0;
		
		// Get the map file and put it in easy-to-use arrays
		$mapfile = preg_replace("/#.+/", "", $mapfile); // Remove comments;
		$lines = explode("\n", $mapfile);
		
		foreach ($lines as $mapline)
		{
			/*
			 * There are 4 phases in the map-file
			 *  1: territory information name, type and regular 3-letter abbreviations, 
			 *  2: connections
			 *  3: not used
			 *  4: not used
			 */
			if (preg_match("/-1/", $mapline)) {
				$phase++;
				continue;
			}
			
			switch ($phases[$phase])
			{
				/*
				 * Read the territory information
				 * $terrInfo[0] = name
				 * $terrInfo[1] = type + abbreviations (separated by " ")
				 *    types: 
				 *      w (water)     : type = Sea
				 *      l (land)      : type = Land/Coast
				 *      x (neutral SC): type = Land/Coast + supply = 'Yes'
				 *      capital initial (home SC): type = Land/Coast + supply = 'Yes' + owner = initial (need the cnt-file, else set to neutral)
				 */
				case "territories": 
					$terrInfo = explode(",", $mapline);

					// terrInfo[0] = Name
					$terrName = addslashes($terrInfo[0]);
					if ($terrName == "") continue;
					
					// $terrInfo[1] / typeCodes = territoryType and (home-)SC information.
					$terrInfo[1] = trim($terrInfo[1]);
					$typeCodes = trim(substr($terrInfo[1], 0, strpos($terrInfo[1], " ")));
					for ($i = 0; $i < strlen($typeCodes); $i++)
					{
						switch ($typeCodes[$i])
						{
							case "w":
								$type   = "Sea";
								$supply = "No";
								$owner  = "Neutral";
								break;
							case "l": 
								$type   = "Land/Coast";
								$supply = "No";
								$owner  = "Neutral";
								break;
							case "x": 
								$type   = "Land/Coast";
								$supply = "Yes";
								$owner  = "Neutral";
								break;
							default:
								if (!(array_key_exists($typeCodes[$i],$powers))) $powers[$typeCodes[$i]] = $typeCodes[$i];
								$owner = $powers[$typeCodes[$i]];
								$supply = "Yes";
								$type = "Land/Coast";
						}
					}
					$territories[$terrName] = array("name"=>$terrName, "type"=>$type, "supply"=>$supply, "owner"=>$owner);
					
					// Now the abbreviation of each territory.
					$terrAbbrs = explode(" ", trim(substr($terrInfo[1], strpos($terrInfo[1], " "))));
					foreach ($terrAbbrs as $terrAbbr)
						$abbr2name[strtoupper($terrAbbr)] = $terrName;
				
					break;
					
				/*
				 * Read the connection information:
				 * <abbreviation>-<type of adjacency>: <adjacencies>
				 *  types:
				 *    mv = only armies
				 *    xc = only fleets
				 *    nc, sc, ec, wc = coast information for armies
				 *    mx = armies with -1 support (not used in webDip)
				 */
				case "connections":
					$moveInfo = explode(": ", $mapline);
					if ($moveInfo[0] == "") break;
					
					$from     = $moveInfo[0];
					$to       = trim($moveInfo[1]);
					$fromTerr = substr($from, 0, strpos($from, "-"));
					$fromType = substr($from, (strpos($from, "-")+1), 2);
					$toTerrs  = explode(" ", str_replace("  ", " ", $to));

					// Get the fullname of the territory
					if (isset($abbr2name[strtoupper($fromTerr)]))
						$fromTerr = $abbr2name[strtoupper($fromTerr)];
					else
						print '<b>Error: missing territory name for abbreviation: '.$fromTerr.'</b><br>';
					
					// The from-territory is a separate coast:
					if ($fromType != "xc" && $fromType != 'mv')
					{
						$owner = $territories[$fromTerr]['owner'];
						$fromTerr = $fromTerr.' ('.$coasts[$fromType[0]].' Coast)';
						$territories[$fromTerr] = array("name"=>$fromTerr, "type"=>"Coast", "supply"=>"No", "owner"=>$owner);
					}
					
					// Let check who can pass:
					$fleetsPass = ($fromType[1]=="c" ? 'Yes' : 'No');
					$armysPass  = ($fromType[1]=="c" ? 'No' : 'Yes');
					
					foreach ($toTerrs as $toTerr)
					{
						$toTerr = trim($toTerr);
						
						// If there is a slash it targets a specific coast of that territory
						if (stristr($toTerr, "/"))
						{
							$slash  = strpos($toTerr, "/");
							$toType = substr($toTerr, ($slash+1), 2);
							$toTerr = substr($toTerr, 0, $slash);
							
							// Get the fullname of the territory
							if (isset($abbr2name[strtoupper($toTerr)]))
								$toTerr = $abbr2name[strtoupper($toTerr)];
							else
								print '<b>Error: missing territory name for abbreviation: '.$toTerr.'</b><br>';
							
							$owner = $territories[$toTerr]['owner'];
							$toTerr = $toTerr.' ('.$coasts[$toType[0]].' Coast)';
							$territories[$toTerr] = array("name"=>$toTerr, "type"=>"Coast", "supply"=>"No", "owner"=>$owner);						
						}
						else
						{
							// Get the fullname of the territory
							if (isset($abbr2name[strtoupper($toTerr)]))
								$toTerr = $abbr2name[strtoupper($toTerr)];
							else
								print '<b>Error: missing territory name for abbreviation: '.$toTerr.'</b><br>';
						}
						
						// If we have already have an entry created by the other type, we just need to add our fleet/army to it.
						if (isset($borderLinks[$fromTerr.$toTerr]))
						{
							if ($fromType[1]=="c")
								$borderLinks[$fromTerr.$toTerr]['fleetsPass'] = "Yes";
							else
								$borderLinks[$fromTerr.$toTerr]['armysPass'] = "Yes";
						}
						else
						{
							$borderLinks[$fromTerr.$toTerr] = array('fromTerr'=>$fromTerr, 'toTerr'=> $toTerr, 'fleetsPass'=>$fleetsPass, 'armysPass'=>$armysPass);
						}
					}
				default: break;
			}
		}
	
		// First update our landlocked countries, but make sure to take into account if we've already determined that a fleet can move there (from borders)
		foreach ($territories as $terrName => $territory)
		{
			$foundSea = $alreadyFleet = false;
			if ($territory['type'] != "Land/Coast") continue;
			
			foreach ($borderLinks as $linkInfo) {
				if (($terrName == $linkInfo['fromTerr']) || ($terrName == $linkInfo['toTerr']))
				{
					if ($linkInfo['fleetsPass'] == "Yes")
						$alreadyFleet = true;
					if ($territories[$linkInfo['toTerr']]['type'] == "Sea")
						$foundSea = true;
				} 
			}
			if (!$foundSea && !$alreadyFleet)
				$territories[$terrName]['type'] = "Land";
		}
		
		// Now that we have all the seas and landlocked countries, we can set the rest of the Land/Coast to Coast
		foreach ($territories as $terrName => $territory)
			if ($territory['type'] == "Land/Coast") $territories[$terrName]['type'] = "Coast";
/*		
		// Quick printout of each territory:
		foreach ($territories as $terrName => $territoryData)
			print $territoryData['name'].": type=".$territoryData['type']." / supply=".$territoryData['supply']." / owner=".$territoryData['owner']."<br>";
			
		// Quick printout of each link:
		foreach ($borderLinks as $index => $linkInfo)
			print $linkInfo['fromTerr']."->".$linkInfo['toTerr'].": fleet=".$linkInfo['fleetsPass']." / army=".$linkInfo['armysPass']."<br>";
*/
		print 'Got <b>'.count($territories).'</b> territories with <b>'.count($borderLinks).'</b> links.';
		print '<div class="hr"></div>';
	}
	
	// Get position-data.
	if ($rgnfile != '')
	{
		$rgnfile   = preg_replace("/Variant.+/", "", $rgnfile); // Sometimes 1st line is a refference to a path on someone elses harddrive;
		$lines     = explode("\n", $rgnfile);

//		$img = imagecreatefromstring($bmpfile);
//		if ($img === false)
//			print "Unknown Imageformat. Please convert to JPG.";

		// get the picture dimensions from the scanlines:
		ini_set('memory_limit',"32M");
		$maxX = $maxY = $scanLines = 0;
		foreach ($lines as $rgnLine)
		{
			$rgnLine=trim($rgnLine);
			
			if ($scanLines > 0)
			{
				list($x, $y, $size) = explode(" ",$rgnLine);
				if ($x + $size > $maxX)
					$maxX=$x + $size;
				if ($y + $scanLines > $maxY)
					$maxY=$y + $scanLines;
				$scanLines--;
			}

			if (preg_match('/^[1-9][0-9]*$/',$rgnLine))
				$scanLines=$rgnLine;
			
		}

		print $maxX." - ".$maxY."<br>";
		$img = imagecreate($maxX, $maxY);

		foreach ($lines as $rgnLine)
		{
			$rgnLine=trim($rgnLine);
			
			if ($scanLines > 0)
			{
				list($x, $y, $size) = explode(" ",$rgnLine);
				imageline ($img, $x, $y, $x + $size, $y, $col);
				$scanLines--;
			}

			$colors=array();
			if (preg_match('/^[1-9][0-9]*$/',$rgnLine))
			{
				$scanLines=$rgnLine;
				do {
					$r=rand(0,255); $g=rand(0,255); $b=rand(0,255);
				} while (in_array($r.$g.$b, $colors));
				$colors[]=$r.$g.$b;
				$col=imagecolorallocate($img,$r,$g,$b);
			}
		}
		
/*		
		$keys = array_keys($territories);
		$terrID=0;
		$scanlines=0;
		$mode="UnitXY";
		$colors=array();
		$terrName=$keys[$terrID];
		
		foreach ($lines as $rgnLine)
		{
			if ($rgnLine == "") continue;

			switch ($mode)
			{
				case "UnitXY":
					list($x,$y) = explode(",",$rgnLine);
	//				print "Terr: ".$terrName."<br>";
	//				print "X:".$x." - y:".$y."<br>";
					$territories[$terrName]['sx']=$smallfact * $x;
					$territories[$terrName]['sy']=$smallfact * $y;
					$territories[$terrName]['x'] =$largefact * $x;
					$territories[$terrName]['y'] =$largefact * $y;
					if (strpos($terrName," Coast)") > 0)
						$mode="Scanlines";
					else
						$mode="TextXY";
					break;
				case "TextXY":
					list($x,$y) = explode(",",$rgnLine);
	//				print "TxtX:".$x." - Txty:".$y."<br>";
					$mode="Scanlines";
					break;
				case "Scanlines":
					if ($img === false) break;
					if ($scanlines==0)
					{
						$scanlines = $rgnLine;
						if ($territories[$terrName]["type"]=='Sea')
						{
							$col=imagecolorallocate($img,197,223,234);
						}
						else
						{
							do {
								$r=rand(0,255); $g=rand(0,255); $b=rand(0,255);
							} while (in_array($r.$g.$b, $colors));
							$colors[]=$r.$g.$b;
							$col=imagecolorallocate($img,$r,$g,$b);
						}					
					}
					else
					{
						$scanlines= $scanlines -1;
						list($x,$y,$length) = explode(" ",$rgnLine);
	//					print "Scanline (".$scanlines."):".$x." ".$y." ".$length."<br>";				
						imageline ($img, $x, $y, $x + $length, $y, $col);
					}
					if ($scanlines==0)
					{
						$mode="UnitXY";
						if (array_key_exists($keys[$terrID]." (East Coast)",$territories)
							&& !isset($territories[$keys[$terrID]." (East Coast)"]['sx']))
						{
							$terrName=$keys[$terrID]." (East Coast)";
						}
						elseif (array_key_exists($keys[$terrID]." (North Coast)",$territories)
							&& !isset($territories[$keys[$terrID]." (North Coast)"]['sx']))
						{
							$terrName=$keys[$terrID]." (North Coast)";
						}
						elseif (array_key_exists($keys[$terrID]." (South Coast)",$territories)
							&& !isset($territories[$keys[$terrID]." (South Coast)"]['sx']))
						{
							$terrName=$keys[$terrID]." (South Coast)";
						}
						elseif (array_key_exists($keys[$terrID]." (West Coast)",$territories)
							&& !isset($territories[$keys[$terrID]." (West Coast)"]['sx']))
						{
							$terrName=$keys[$terrID]." (West Coast)";
						}
						else
						{
							$terrID = $terrID + 1;
							$terrName=$keys[$terrID];
						}
					}
				default:
					break;
			}
		}
*/	
		imagepng($img,'rp2wd_map.png');
		print '<img src="rp2wd_map.png">';
		
	}	

	/*
	$gamfile = preg_replace("/#.+/", "", $gamfile); // Remove comments;
	$lines = explode("\n", $gamfile);
	$searchstart=9;
	$keys = array_keys($powers);
	$powerIndex=0;
	echo "<br><hr><br>";
	print 'protected $countryUnits = array(<br>';

	foreach ($lines as $key => $value)
	{
		if ($value == "") continue;
		$searchstart=$searchstart -1;
		if ($searchstart > 0) continue;
		if ($powerIndex < count($keys))
		{
			print " '".$powers[$keys[$powerIndex]]."'=> array(";
			$line = explode(" ",$value);
			foreach ($line as $parameter)
			{
				if ($parameter == 'A')
					$unit = 'Army';
				elseif ($parameter == 'F')
					$unit = 'Fleet';
				else
				{
					$abbr = preg_replace("/\r|\n/s", "", $parameter);
					print "'".$territories[strtoupper($abbr)]["country"]."' => '".$unit."', ";
				}
			}
			print ")<br>";
			$powerIndex++;
			$searchstart=3;
		}
	}
	
	echo "<br><hr><br>";
	echo "Territory information: ('Terrname', 'Type', 'Supply', 'Owner', 'X', 'Y', 'SX', 'SY', 'Coast')<br>";
	foreach ($territories as $id=>$territory)
	{
		if (!(isset($territory["x"])))  $territory["x"]  = 0;
		if (!(isset($territory["y"])))  $territory["y"]  = 0;
		if (!(isset($territory["sx"]))) $territory["sx"] = 0;
		if (!(isset($territory["sy"]))) $territory["sy"] = 0;
		
		print "&nbsp&nbsparray('".$territory["country"]."', '".$territory["type"]."', '".$territory["supply"]."', '".
			$territory["hometerr"]."', ".(int)$territory["x"].", ".(int)$territory["y"].", ".$territory["sx"].", ".$territory["sy"]."),<br>";
	}

	echo '
		foreach($territoryRawData as $territoryRawRow)
		{
			list($name, $type, $supply, $country, $x, $y, $sx, $sy)=$territoryRawRow;
			if( $country==\'Neutral\' )
				$countryID=0;
			else
				$countryID=$this->countryID($country);
				
			new InstallTerritory($name, $type, $supply, $countryID, $x, $y, $sx, $sy);
		}
		unset($territoryRawData);';
			
	echo "<br><hr><br>Border information ('Terr1', 'Terr2', 'Fleets', 'Army'):<br>";
	$count = 0;
	$outputted = array();
	$datacopy = $data;
	foreach ($connections as $index => $border) {
		$count++;
		$fromTerr = $territories[strtoupper($border['fromTerr'])]['country'];
		$toTerr = $territories[strtoupper($border['toTerr'])]['country'];
		if ($border['coast1'] != "") $fromTerr .= " (" . $border['coast1'] . " Coast)";
		if ($border['coast2'] != "") $toTerr .= " (" . $border['coast2'] . " Coast)";

		if (in_array(array($fromTerr, $toTerr), $outputted)) continue; // Don't duplicate

		echo "  array('".$fromTerr."', '".$toTerr."', '".$datacopy[$index]["fleetsPass"]."', '".$datacopy[$index]["armysPass"]."'";
		echo ")";
		$outputted[] = array($fromTerr, $toTerr);
		if (count($connections) != $count) {
			echo ",<br>";
		} else {
			echo ";<br>";
		}
	}

	print '<img src="rp2wd_map.png">';
*/	
} else {
	print
		'<div class="hr"></div>
		<b>Realpolitic converter:<br></b>
		<form method="POST" action="'.$_SERVER['PHP_SELF'].'" enctype="multipart/form-data">
		<input type="hidden" name="MAX_FILE_SIZE" value="1000000">
		<input type="hidden" name="tab" value="Converter">
		<style type="text/css"> td { padding:2px; white-space: nowrap;} </style>
		<table>
			<tr><td>Country file <b>(.cnt)</b>:</td>              <td><input type="file" name="cntfile"></td> <td style=" width: 100%;"></td></tr>
			<tr><td>Territories / border file <b>(.map)</b>:</td> <td><input type="file" name="mapfile">    </td> <td style=" width: 100%;"></td></tr>
			<tr><td>Position file <b>(.rgn)</b>:            </td> <td><input type="file" name="rgnfile">    </td> 
				<td>smallmap-factor:</td> <td><input size="5" name="smallmapfactor" value="" /></td>
				<td>map-factor:     </td> <td><input size="5" name="mapfactor" value="" />     </td>              <td style=" width: 100%;"></td></tr>
			<tr><td>Starting positions file <b>(.gam)</b>:  </td> <td><input type="file" name="gamfile">    </td> <td style=" width: 100%;"></td></tr>
			<tr><td>Black&white map <b>(.png)</b>:          </td> <td><input type="file" name="bmpfile">    </td> 
				<td colspan="4">(convert bmp to png bevore processing)</td>                                                   <td style=" width: 100%;"></td></tr>
		</table>
		<input type="submit" name="SubmitRP" value="Submit Realpolitic files">
		</form>
		<div class="hr"></div>';
}
	
print '</div>';
libHTML::footer();

?>
