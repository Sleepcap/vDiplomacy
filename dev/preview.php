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

if ($variantID != 0)
{
	print '<div class="hr"></div>';
	$id=$variantID;
	
	if (!(isset(Config::$variants[$id])) || (isset(Config::$hiddenVariants) && in_array($id,Config::$hiddenVariants) && $User->type['Guest']) )
		foreach (array_reverse(Config::$variants,true) as $id => $name);
		
	$Variant = libVariant::loadFromVariantID($id);
	print '</div>
		<div style="text-align:center">
			<span id="Image_'. $Variant->name . '">
				<a href="map.php?variantID=' . $Variant->id. '&largemap&/devmode" target="_blank">
					<img src="map.php?variantID=' . $Variant->id.'&/devmode"
					style="max-width: 730px;"
					alt="Open large map"
					title="The map for the '. $Variant->name .' Variant" />
				</a>
			</span>
		</div>
		<div class="content">
			<strong>Variant Parameters'
				.(((isset($Variant->version)) || (isset($Variant->CodeVersion))) ? 
					'('
					.(isset($Variant->version) ? 'Version: '. $Variant->version.(isset($Variant->codeVersion) ? ' / ':'') : '')
					.(isset($Variant->codeVersion) ? 'Code: '.$Variant->codeVersion : '').
				')' : '').
			':</strong>';
	
	print '<ul>'
		.(isset($Variant->homepage) ? '<li><a href="'.$Variant->homepage.'">Variant homepage</a></li>' : '')
		.(isset($Variant->author)   ? '<li>Created by: '.$Variant->author.'</li>' : '')
		.(isset($Variant->adapter)  ? '<li>Adapted for webDiplomacy by: '. $Variant->adapter .'</li>' : '')
		.'<li>SCs required for solo win: ' . $Variant->supplyCenterTarget . ' (of '.$Variant->supplyCenterCount.')</li>';

	$count=array('Sea'=>0,'Land'=>0,'Coast'=>0,'All'=>0);
	$tabl = $DB->sql_tabl(
		'SELECT TYPE,count(TYPE) FROM wD_Territories t
			WHERE EXISTS (SELECT * FROM wD_Borders b WHERE b.fromTerrID = t.id && b.mapID = t.mapID) 
			&& t.mapID ='.$Variant->mapID.' && t.name NOT LIKE "% Coast)%" 
		GROUP BY TYPE');
	while(list($type,$counter) = $DB->tabl_row($tabl))
	{
		$count[$type]=$counter;
		$count['All']+=$counter;
	}	
	print '<li> Territories: '.$count['All'].' (Land='.$count['Land'].'; Coast='.$count['Coast'].'; Sea='.$count['Sea'].')</li>';

	if (!file_exists('variants/'. $Variant->name .'/rules.html'))
		print '<li>Standard Diplomacy Rules Apply</li>';
	print '</ul>';

	if (file_exists('variants/'. $Variant->name .'/rules.html'))
		print '<p><strong>Special rules/information:</strong></p>
				<div>'.file_get_contents('variants/'. $Variant->name .'/rules.html').'</div>';
	
}
print '</div>';
libHTML::footer();

?>
