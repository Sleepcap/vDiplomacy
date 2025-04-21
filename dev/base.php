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

if (($variantID != 0) && !(file_exists('variants/'.Config::$variants[$variantID].'/install.php'))) 
	$edit = false;
	
if ($edit == true)
{
	// Save changes to the "base"-values:
	if (isset($_REQUEST['addCountry']))
		$_REQUEST['country'][]='';
	
	if ((isset($_REQUEST['deleteCountry'])) && (count($_REQUEST['country']) > 2))
		array_pop($_REQUEST['country']);
	
	if (isset($_REQUEST['submitBase']) || (isset($_REQUEST['country'])) || (isset($_REQUEST['EditToDo'])) )
	{
		
		copy('variants/'.Config::$variants[$variantID].'/variant.php', 'variants/'.Config::$variants[$variantID].'/cache/'.date("ymd-His").'-base-variant.php');

		// Validate the input:
		$fullName    = (isset($_REQUEST['fullName'])    ? substr(preg_replace('/[^a-zA-Z0-9\(\)\=\& \-\?\.,]/', '',    $_REQUEST['fullName']),0,150)    : '');
		$description = (isset($_REQUEST['description']) ? substr(preg_replace('/[^a-zA-Z0-9\(\)\=\& \-\?\.,]/', '',    $_REQUEST['description']),0,150) : '');
		$author      = (isset($_REQUEST['author'])      ? substr(preg_replace('/[^a-zA-Z0-9\(\)\=\& \-\?\.,]/', '',    $_REQUEST['author']),0,150)      : '');
		$adapter     = (isset($_REQUEST['adapter'])     ? substr(preg_replace('/[^a-zA-Z0-9\(\)\=\& \-\?\.,]/', '',    $_REQUEST['adapter']),0,150)     : '');
		$version     = (isset($_REQUEST['version'])     ? substr(preg_replace('/[^a-zA-Z0-9\(\)\=\& \-\?\.,]/', '',    $_REQUEST['version']),0,10)      : '');
		$codeVersion = (isset($_REQUEST['codeVersion']) ? substr(preg_replace('/[^0-9\.]/',                     '',    $_REQUEST['codeVersion']),0,10)  : '');
		$homepage    = (isset($_REQUEST['homepage'])    ? substr(preg_replace('/[^a-zA-Z0-9\:\/\-\=\(\)\& \?\.,]/','', $_REQUEST['homepage']),0,150)    : '');
		
		if (isset($_REQUEST['country']))
		{
			foreach ($_REQUEST['country'] as $id => $name)
				$_REQUEST['country'][$id] = substr(preg_replace('/[^a-zA-Z0-9- ]/','',$name),0,20);
			// delete the old pregame-unit-assigning
			$newName = Config::$variants[$variantID]; $newID=$variantID; $newYear=0;
			require_once('dev/newVariantDefaults.php');
			file_put_contents ('variants/'.Config::$variants[$variantID].'/classes/adjudicatorPreGame.php', $adjucatorPreGameTxt);		
		}
		
		if (isset($_REQUEST['EditToDo']))
		{
			$toDo=$_REQUEST['EditToDo'];
			if ($toDo == '')
			{
				if (file_exists('variants/'.Config::$variants[$variantID].'/toDo.txt'))
					unlink ('variants/'.Config::$variants[$variantID].'/toDo.txt');
			}
			else
			{
				$toDo = str_ireplace("<END-TA-DO-NOT-EDIT>", "</textarea>", $toDo);
				$toDo = stripslashes($toDo);
				file_put_contents('variants/'.Config::$variants[$variantID].'/toDo.txt', $toDo);	
			}
		}
				
		$newVariant = array();
		$handle = fopen('variants/'.Config::$variants[$variantID].'/variant.php', 'r');
		while (!(feof($handle)))
		{
            $line = rtrim(fgets($handle));

			// Skip all variables and replace them by the new ones.
			if (strpos($line,"public \$name") > 0 && isset($_REQUEST['submitBase']))
			{
                $newVariant[] = $line;
                
				do {
					$line = rtrim(fgets($handle));
				} while ((strpos($line,"public") > 0)  != '' && !(feof($handle)));
					
				$newVariant[] = "\tpublic \$fullName    ='".$fullName   ."';";
				$newVariant[] = "\tpublic \$description ='".$description."';";
				$newVariant[] = "\tpublic \$author      ='".$author     ."';";
				$newVariant[] = "\tpublic \$adapter     ='".$adapter    ."';";
				$newVariant[] = "\tpublic \$version     ='".$version    ."';";
				$newVariant[] = "\tpublic \$codeVersion ='".$codeVersion."';";
				$newVariant[] = "\tpublic \$homepage    ='".$homepage   ."';";
				$newVariant[] = $line;
			}
			elseif (strpos($line,"public \$countries") > 0 && (isset($_REQUEST['country'])))
			{
				$newVariant[] =  "\tpublic \$countries=array('".implode("','",$_REQUEST['country'])."');";
			}
			else
			{
				$newVariant[] = $line;
			}
	
		}
		fclose($handle);
		
		file_put_contents('variants/'.Config::$variants[$variantID].'/variant.php', implode($newVariant,"\n"));	
		
		unset($Variant);
		$Variant = libVariant::loadFromVariantID($variantID);
		libVariant::setGlobals($Variant);

	}
}

print '<b>Variant: '.$selectVariantForm.'</b>';

function InputForm($name, $value, $size=60)
{
	global $edit;
	if ($edit == true)
		return '<input type="text" name="'.$name.'" value="'.$value.'" size="'.$size.'">';
	return $value;
}

if ($variantID != 0)
{
	$Variant = libVariant::loadFromVariantID($variantID);
	libVariant::setGlobals($Variant);

	$toDo = ( (file_exists('variants/'.Config::$variants[$variantID].'/toDo.txt')) ? file_get_contents('variants/'.Config::$variants[$variantID].'/toDo.txt') : '');
	
	print '
		<div class="hr"></div>
		<style type="text/css"> td { padding:2px; font-weight: bold; white-space: nowrap;} </style>
		<table>
			<tr><td>Id:   </td> <td>'.$Variant->id.'   </td> <td style=" width: 100%;"></td></tr>
			<tr><td>MapID:</td> <td>'.$Variant->mapID.'</td> <td style=" width: 100%;"></td></tr>
			<tr><td>Name: </td> <td>'.$Variant->name.' </td> <td style=" width: 100%;"></td></tr>
			'.(isset($Variant->codeVersion) ? '<tr><td>CodeVersion: </td> <td>'.$Variant->codeVersion.' </td> <td style=" width: 100%;"></td></tr>' : '' ).'
			<tr><td>Starting year:  </td> <td>'.$Variant->turnAsDate(0).     '</td> <td style=" width: 100%;"></td></tr>
			<tr><td>SCs for victory:</td> <td>'.$Variant->supplyCenterTarget.'</td> <td style=" width: 100%;"></td></tr>
		</table>
		<div class="hr"></div>'
		.($edit == true ? '
			<b>ToDo:</b><span id="EditToDoButton"> (<a href="#" onclick="$(\'ToDoNoteBox\').show(); $(\'ToDoNoteText\').hide(); $(\'EditToDoButton\').hide(); return false;">Edit</a>)</span>
			<table>
				<td>
					<span id="ToDoNoteText" style="font-weight: normal;">'.str_ireplace("\n", "<br />", $toDo).'</span>
					<span id="ToDoNoteBox" style="display:none;">
						<form method="post" style="display:inline;">
							<textarea name="EditToDo" style="width:100%;height:200px">'.str_ireplace("</textarea>", "<END-TA-DO-NOT-EDIT>", $toDo).'</textarea>
							<table><td align="right"><input type="Submit" value="Submit" /></td></table>
							
						</form>				
					</span>
				</td>
			</table>
			<div class="hr"></div>' : '').'
		<form style="display: inline" method="get" name="basevalues">
			<input type="hidden" name="tab" value="Base">
			<input type="hidden" name="variantID" value="'.$variantID.'">'.
			(isset($Variant->codeVersion) ? '<input type="hidden" name="codeVersion" value="'.$Variant->codeVersion.'">' : '').'
			<table>
				<tr><td>FullName:   </td> <td>'.InputForm('fullName',   (isset($Variant->fullName)   ? $Variant->fullName   : '')).'</td> <td style=" width: 100%;"></td></tr>
				<tr><td>Description:</td> <td>'.InputForm('description',(isset($Variant->description)? $Variant->description: '')).'</td> <td style=" width: 100%;"></td></tr>
				<tr><td>Author:     </td> <td>'.InputForm('author',     (isset($Variant->author)     ? $Variant->author     : '')).'</td> <td style=" width: 100%;"></td></tr>
				<tr><td>Adapter:    </td> <td>'.InputForm('adapter',    (isset($Variant->adapter)    ? $Variant->adapter    : '')).'</td> <td style=" width: 100%;"></td></tr>
				<tr><td>Version:    </td> <td>'.InputForm('version',    (isset($Variant->version)    ? $Variant->version    : '')).'</td> <td style=" width: 100%;"></td></tr>
				<tr><td>Homepage:   </td> <td>'.InputForm('homepage',   (isset($Variant->homepage)   ? $Variant->homepage   : '')).'</td> <td style=" width: 100%;"></td></tr>
			</table>
			'.($edit==true ? '<b><input type="submit" class="form-submit" name="submitBase" value="Submit base changes" /></b>' : '').'
		</form>
		<div class="hr"></div>';
		
	if ($edit == true)
	{
		print '<b>If you add at least one country remember to set a color in the "Colors"-tab, or drawmap will throw an error.<br>
			If you rename a country make sure to redo the starting-units.</b><br>
			<form style="display: inline" method="get" name="countryOptions">';
		foreach ($Variant->countries as $id => $name)
			print '<input type="hidden" name="country['.($id + 1).']" value="'.$name. '">';
		print '<input type="hidden" name="tab" value="Base">
			<input type="hidden" name="variantID" value="'.$variantID.'">
			<input type="submit" class="form-submit" name="addCountry" value="add Country" />';
		if (count($Variant->countries) > 2)
			print ' / <input type="submit" class="form-submit" name="deleteCountry" value="delete Country" />';
		print '</form>
			<div class="hr"></div>';
	}
	
	print '<form style="display: inline" method="get" name="countries">
		<input type="hidden" name="tab" value="Base">
		<input type="hidden" name="variantID" value="'.$variantID.'">
		<table>';
	foreach ($Variant->countries as $id => $name)
		print '<tr><td>countryID: '.($id + 1).' =></td> <td>'.InputForm('country['.($id + 1).']', $name, 30).'</td> <td style=" width: 100%;"></td></tr>';
	print '</table>
		'.($edit==true ? '<input type="submit" class="form-submit" name="submitCountryNames" value="Submit country-name changes" />':'').'
		</form>';
	
}
print '</div>';
libHTML::footer();

?>
