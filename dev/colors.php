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

/*
 * Show the variant-information with some edit possibilities for variant developers
 */

if ($edit == true && (isset($_REQUEST['submitColors'])) )
{
	foreach ($Variant->countries as $countryID => $name)
	{
		if (isset($_REQUEST['font'][$countryID]))
			$_REQUEST['font'][$countryID] = '#'.substr(preg_replace('/[^a-fA-F0-9]/', '', $_REQUEST['font'][$countryID] ),0,6);
		if (isset($_REQUEST['occupationBar'][$countryID]))
			$_REQUEST['occupationBar'][$countryID] = '#'.substr(preg_replace('/[^a-fA-F0-9]/', '', $_REQUEST['occupationBar'][$countryID] ),0,6);
	}
	
	copy('variants/'.Config::$variants[$variantID].'/classes/drawMap.php', 'variants/'.Config::$variants[$variantID].'/cache/'.date("ymd-His").'-col-drawmap.php');
	copy('variants/'.Config::$variants[$variantID].'/resources/style.css', 'variants/'.Config::$variants[$variantID].'/cache/'.date("ymd-His").'-col-style.css');

	array_unshift($Variant->countries , 'Neutral');

	$newDrawmap = array();
	$handle = fopen('variants/'.Config::$variants[$variantID].'/classes/drawMap.php', 'r');
	while (!(feof($handle)))
	{
		$line = rtrim(fgets($handle));
		$newDrawmap[]=$line;
		
		if (strpos($line,'protected $countryColors') == true)
		{
			foreach ($Variant->countries as $countryID => $name)
			{
				$color = substr(preg_replace('/[^a-fA-F0-9]/', '', $_REQUEST['drawMap'][$countryID]),0,6);
				$r=hexdec(substr($color,0,2)); if (strlen($r)<3) $r=' '.$r; if (strlen($r)<3) $r=' '.$r;
				$g=hexdec(substr($color,2,2)); if (strlen($g)<3) $g=' '.$g; if (strlen($g)<3) $g=' '.$g;
				$b=hexdec(substr($color,4,2)); if (strlen($b)<3) $b=' '.$b; if (strlen($b)<3) $b=' '.$b;
				$newDrawmap[]="\t\t".$countryID." => array(".$r.", ".$g.", ".$b."), // ".$name;
			}
			
			$newDrawmap[] = str_replace('),', ') ', array_pop($newDrawmap));
			$newDrawmap[] = "\t);";
			while (strpos($line,'array') == true) $line = rtrim(fgets($handle));
		}
	}
	fclose($handle);
	
	file_put_contents('variants/'.Config::$variants[$variantID].'/classes/drawMap.php', implode($newDrawmap,"\n"));	

	$handle = fopen('variants/'.Config::$variants[$variantID].'/resources/style.css', 'w');
	fwrite($handle, '@CHARSET "ISO-8859-1";'."\n\n");
	foreach ($Variant->countries as $id => $name)
		fwrite($handle, '.variant'.Config::$variants[$variantID].' .country'.($id).' { color: '.$_REQUEST['font'][($id)].' ! important; } /* '.$name.' */'."\n");
	fwrite($handle, "\n");
	foreach (array_slice($Variant->countries,1) as $id => $name)
		fwrite($handle, '.variant'.Config::$variants[$variantID].' .occupationBar'.($id + 1).' { background-color: '.$_REQUEST['occupationBar'][($id + 1)].'; } /* '.$name.' */'."\n");
	fclose($handle);
	array_shift($Variant->countries);
	
}

print '<b>Variant: '.$selectVariantForm.'</b>';

function InputForm($name, $value, $size=10)
{
	global $edit;
	if ($edit == true)
		return '<input type="text" name="'.$name.'" value="'.$value.'" size="'.$size.'">';
	return $value;
}

if ($variantID != 0)
{
	// Fake to copy the protected countryColors in a public countryColors variable...
	abstract class drawMap
	{
		public $smallmap;
		public $mapID;
		public $myCountryColors = array();
		function __construct($smallmap)
		{
			if (isset($this->countryColors))
				$this->myCountryColors = $this->countryColors;
		}
	}
	
	$MyDrawMap = $Variant->drawMap(false);

	array_unshift($Variant->countries , 'Neutral');
	
	$colorCode=array();
	
	$css = file('variants/'.Config::$variants[$variantID].'/resources/style.css', FILE_IGNORE_NEW_LINES);
		
	foreach ($Variant->countries as $countryID => $name)
	{
		if (isset($MyDrawMap->myCountryColors[$countryID]))
		{
			$r=dechex($MyDrawMap->myCountryColors[$countryID][0]); if (strlen($r)<2) $r='0'.$r;
			$g=dechex($MyDrawMap->myCountryColors[$countryID][1]); if (strlen($g)<2) $g='0'.$g;
			$b=dechex($MyDrawMap->myCountryColors[$countryID][2]); if (strlen($b)<2) $b='0'.$b;
			$colorCode['drawMap'][$countryID] = '#'.strtoupper($r.$g.$b);
		} else {
			$colorCode['drawMap'][$countryID] = '#A0A0A0';
		}
		
		$cssLines = array_values (preg_grep ( '/(country|occupationBar)'.$countryID.' .*(#[0-9a-fA-F][0-9a-fA-F][0-9a-fA-F][0-9a-fA-F][0-9a-fA-F][0-9a-fA-F])/', $css));
		$colorCode['font'][$countryID] = (isset($cssLines[0]) ? strtoupper(substr($cssLines[0], strpos($cssLines[0], '#'),7)) : "#000000");
		$colorCode['occupationBar'][$countryID] = ((isset($cssLines[1])  && $countryID != 0) ? strtoupper(substr($cssLines[1], strpos($cssLines[1], '#'),7)) : "#000000");
	}
	
    print '<style type="text/css"> td { padding:2px; font-weight: bold; white-space: nowrap;}</style>
			<div class="hr"></div>
			<form style="display: inline" method="get" name="countries">
			<input type="hidden" name="tab" value="Colors">
			<input type="hidden" name="variantID" value="'.$variantID.'">';

	print '<b>Text font overview:'.'</b>
		<table style="background-color:#ffffff;">';
	foreach ($Variant->countries as $id => $name)
		print '
			 <tr style="color:'.$colorCode['font'][($id)].'">
				<td>'.$name.':</td>
				<input type="hidden" name="drawMap['.$id.']" value="'.$colorCode['drawMap'][($id)].'">'.
				(($id > 0) ? '<input type="hidden" name="occupationBar['.$id.']" value="'.$colorCode['occupationBar'][($id)].'">' : '').'
				<td>'.InputForm('font['.$id.']', $colorCode['font'][($id)]).'</td>
				<td style="width: 100%; font-weight:normal;">This is how the country-text looks in the chat.</td>
			 </tr>
			';
	print '</table>';
	
	if ($edit == true)
		print '<input type="submit" class="form-submit" name="submitColors" value="Submit color changes" />';
	
	print '</form><div class="hr"></div>';
	
	print '<form style="display: inline" method="get" name="countries">
				<input type="hidden" name="tab" value="Colors">
				<input type="hidden" name="variantID" value="'.$variantID.'">';
			
	foreach ($Variant->countries as $id => $name)
	{
		print '<b>'.$name.':'.'</b>
			<table>
				<tr style="background-color:'.$colorCode['drawMap'][($id)].'">
					<td>Map-color:</td>
					<td>'.InputForm('drawMap['.$id.']', $colorCode['drawMap'][($id)]).'</td>
					<td style="width: 100%;"></td>
				</tr>'.
			(($id > 0) ? '
			 <tr style="background-color:'.$colorCode['occupationBar'][($id)].'">
				<td>OccupationBar:</td>
				<td>'.InputForm('occupationBar['.$id.']', $colorCode['occupationBar'][($id)]).'</td>
				<td style="width: 100%;"></td>
			 </tr>' : '').'
			 <tr style="font-weight:normal; background-color:#ffffff; color:'.$colorCode['font'][($id)].'">
				<td>Font-color:</td>
				<td>'.InputForm('font['.$id.']', $colorCode['font'][($id)]).'</td>
				<td style="font-weight:normal;"><b>'.$name.':</b> This is how the country-text looks in the chat.</td>
			 </tr>
			 </table>
			<div class="hr"></div>';
	}
	
	if ($edit == true)
		print '<input type="submit" class="form-submit" name="submitColors" value="Submit color changes" />';
	
	print '</form>';
}
print '</div>';
libHTML::footer();

?>
