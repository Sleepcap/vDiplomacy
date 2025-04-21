<?php
/*
Copyright (C) 2004-2011 Oliver Auth

This file is part of vDiplomacy.

webDiplomacy is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

webDiplomacy is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with webDiplomacy.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
* @package Base
* @subpackage Static
*/

require_once('header.php');

libHTML::starthtml();

if(!(isset($_REQUEST['variantID'])))
{
	print '<script type="text/javascript" src="contrib/tablekit/tablekit.js"></script>';
	print libHTML::pageTitle('webDiplomacy variants','A list of the variants available on this server, with credits and information on variant-specific rules.');
	$variantsOn=array();
	$variantsOff=array();

	foreach(glob('variants/*') as $variantDir)
	{
		if( file_exists($variantDir.'/variant.php') )
		{
			$variantDir=substr($variantDir,9);
			if( in_array($variantDir, Config::$variants) )
				$variantsOn[] = $variantDir;
			else
				$variantsOff[] = $variantDir;
		}
	}
	
	if( count($variantsOff) )
		print '<a name="top"></a><h4>Active variants:</h4>';
	
	print '<style type="text/css">
			.sortcol { cursor: pointer;
				padding-right: 20px;
				background-repeat: no-repeat;
				background-position: right center; }
			.sortasc {
				background-color: #DDFFAC;
				background-image: url(contrib/tablekit/up.gif); }
			.sortdesc {
				background-color: #B9DDFF;
				background-image: url(contrib/tablekit/down.gif); }
			.nosort { cursor: default;} 
		</style>';
		
	print '<TABLE class="sortable">
				<THEAD>
					<TH style="border: 1px solid #000" class="sortfirstasc">Name</TH>
					<TH style="border: 1px solid #000">Players</TH>
					<TH style="border: 1px solid #000">IAMap</TH>
					<TH style="border: 1px solid #000">Games finished</TH>
					<TH style="border: 1px solid #000">avg. Turns</TH>
					<TH style="border: 1px solid #000">Rating*</TH>
					<TH style="border: 1px solid #000">Active**</TH>
				</THEAD>
				<TFOOT>
					<tr style="border: 1px solid #666"><td colspan=6><b>**Rating</b> = ("players" x "games played") - <b>**Active</b> = Number of active games</td></tr>
				</TFOOT>';
			
	foreach( $variantsOn as $variantName )
	{

		list($variantID, $countryCount, $fullName) = $DB->sql_row('SELECT variantID, countryCount,fullName FROM wD_VariantInfo WHERE name="'.$variantName.'"');
		
		// don't show variants without a wD_VariantInfo (usually in development)
		if ($variantID == '') continue;
			
		if (isset(Config::$hiddenVariants) && in_array($variantID,Config::$hiddenVariants) && $User->type['Guest'])
			continue;
		
		// Don't show blocked variants (usually in development):
		if (isset(Config::$blockedVariants) && in_array($variantID,Config::$blockedVariants))
			continue;
			
		list($players)=$DB->sql_row('SELECT COUNT(*) FROM wD_Members m INNER JOIN wD_Games g ON (g.id = m.gameID) WHERE g.variantID='.$variantID.' AND g.phase = "Finished"');
		list($turns,$games) = $DB->sql_row('SELECT SUM(turn), COUNT(*) FROM wD_Games WHERE variantID='.$variantID.' AND phase = "Finished"');
		list($active) = $DB->sql_row('SELECT COUNT(*) FROM wD_Games WHERE variantID='.$variantID.' AND phase != "Finished" AND phase != "Pre-game"');

		print '<TR>
				<TD style="border: 1px solid #666"><A CLASS="light" href="variants.php?variantID='.$variantID.'">'.l_t($fullName).'</A></TD>
				<TD style="border: 1px solid #666">'.($games==0?$countryCount:round($players/$games,2)) .' players</TD>
				<TD style="border: 1px solid #666" align="center">'.((file_exists('variants/'.$variantName.'/interactiveMap')) ? '<img src="images/icons/tick.png"' : '-').'</TD>
				<TD style="border: 1px solid #666">'.$games.' game'.($games!=1?'s':'').'</TD>
				<TD style="border: 1px solid #666">'.($games==0?'0.00':number_format($turns/$games,2)).' turns</TD>
				<TD style="border: 1px solid #666">'.$players.'</TD>
				<TD style="border: 1px solid #666">'.$active.'</TD>
			</TR>';
	}
	print '</TABLE>';

/* Do not show the deactivated variants, it causes all sort of problems with unfinnished maps on the lab and
 * it's not really that interesting.
 
	if( count($variantsOff) )
	{
		print '<h4>Disabled variants</h4>';
		print '<p>Variants which are present but not activated.</p>';
		print '<ul>';
		foreach( $variantsOff as $variantName )
		{
			$Variant = libVariant::loadFromVariantName($variantName);
			if (isset(Config::$hiddenVariants) && in_array($Variant->id,Config::$hiddenVariants) && $User->type['Guest'])
				continue;
				
			print '<li>' . $Variant->name . '</a> (' . count($Variant->countries) . ' Players)</li>';
		}
		print '</ul>';
	}
*/

	print '<div class="hr"></div>';
}
else
{
	$id=intval($_REQUEST['variantID']);
	
	if (!(isset(Config::$variants[$id])) || (isset(Config::$hiddenVariants) && in_array($id,Config::$hiddenVariants) && $User->type['Guest']) )
		foreach (array_reverse(Config::$variants,true) as $id => $name);
		
	$Variant = libVariant::loadFromVariantID($id);
	print libHTML::pageTitle($Variant->fullName . ' (' . count($Variant->countries) . ' players)',$Variant->description);
	
	print "</div>";
	
	print '<div style="text-align:center"><span id="Image_'. $Variant->name . '"> <a href="';
		if (file_exists(libVariant::cacheDir($Variant->name).'/sampleMapLarge.png'))
			print libVariant::cacheDir($Variant->name).'/sampleMapLarge.png';
		else
			print 'map.php?variantID=' . $Variant->id. '&/devlog&largemap';	
	print '" target="_blank"> <img src="';
	if (file_exists(libVariant::cacheDir($Variant->name).'/sampleMap.png'))
		print libVariant::cacheDir($Variant->name).'/sampleMap.png';
	else
		print 'map.php?variantID=' . $Variant->id."&/devlog";
	print '" style="max-width: 730px;" alt="Open large map" title="The map for the '. $Variant->name .' Variant"></a></span> </div><br />';

	print '<div class="content content-follow-on">';
	
	print '<table>
		<td style="text-align:left">Search for games: 		
			<FORM style="display: inline" method="get" action="gamelistings.php#results">
				<input type="hidden" name="gamelistType" value="Search" />
				<input type="hidden" name="status" value="Pre-game" />
				<input type="hidden" name="variant" value="'.$Variant->name.'" />
				<input type="hidden" name="Submit" value="Search" />
				<input type="submit" value="New"/></form>							
			<form style="display: inline" action="gamelistings.php#results" method="POST">
				<input type="hidden" name="gamelistType" value="Search" />
				<input type="hidden" name="seeJoinable" value="yes" />
				<input type="hidden" name="variant" value="'.$Variant->name.'" />
				<input type="hidden" name="Submit" value="Search" />
				<input type="submit" value="Open"/></form>				
			<form style="display: inline" action="gamelistings.php#results" method="POST">
				<input type="hidden" name="gamelistType" value="Search" />
				<input type="hidden" name="status" value="Active" />
				<input type="hidden" name="variant" value="'.$Variant->name.'" />
				<input type="hidden" name="Submit" value="Search" />
				<input type="submit" value="Active" /></form>
			<form style="display: inline" action="gamelistings.php#results" method="POST">
				<input type="hidden" name="gamelistType" value="Search" />
				<input type="hidden" name="status" value="Finished" />
				<input type="hidden" name="variant" value="'.$Variant->name.'" />
				<input type="hidden" name="Submit" value="Search" />
				<input type="submit" value="Finished" /></form>
		</td> <td style="text-align:right">
			<form style="display: inline" action="stats.php" method="GET">
				<input type="hidden" name="variantID" value="'.$Variant->id.'" />
				<input type="submit" value="View statistics" /></form>			
			<form style="display: inline" action="dev.php" method="GET">
				<input type="hidden" name="variantID" value="'.$Variant->id.'" />
				<input type="hidden" name="tab" value="Map" />
				<input type="hidden" name="variantID" value="'.$Variant->id.'" />
				<input type="submit" value="Map info" /></form>			
			<form style="display: inline" action="dev.php" method="GET">
				<input type="hidden" name="variantID" value="'.$Variant->id.'" />
				<input type="hidden" name="tab" value="Files" />
				<input type="hidden" name="variantID" value="'.$Variant->id.'" />
				<input type="submit" value="View/Download code" /></form>
		</td>
	</table>';
			
	print '<br><div><strong>Variant Parameters';
	if ((isset($Variant->version)) || (isset($Variant->CodeVersion)))
	{
		print ' (';
		if (isset($Variant->version))
			print 'Version: '. $Variant->version.(isset($Variant->codeVersion)?' / ':'');
		if (isset($Variant->codeVersion))
			print 'Code: ' . $Variant->codeVersion;
		print ')';
	}
	print ':</strong>';
	
	print '<ul>';
	if (isset($Variant->homepage))
		print '<li><a href="'. $Variant->homepage .'">Variant homepage</a></li>';
	if (isset($Variant->author))
		print '<li> Created by: '. $Variant->author .'</li>';
	if (isset($Variant->adapter))
		print '<li> Adapted for webDiplomacy by: '. $Variant->adapter .'</li>';

	list($turns,$games) = $DB->sql_row('SELECT SUM(turn), COUNT(*) FROM wD_Games WHERE variantID='.$Variant->id.' AND phase = "Finished"');
	print '<li> Games finished: '. $games .' game'.($games!=1?'s':'').'</li>';
	print '<li> avg. Duration: '. ($games==0?'0.00':number_format($turns/$games,2)) .' turns</li>';

	print '<li> SCs required for solo win: ' . $Variant->supplyCenterTarget . ' (of '.$Variant->supplyCenterCount.')</li>';

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
	{
		print '<p><strong>Special rules/information:</strong></p>';
		print '<div>'.file_get_contents('variants/'. $Variant->name .'/rules.html').'</div>';
	}
	elseif (file_exists('variants/'. $Variant->name .'/rules.php'))
	{
		print '<p><strong>Special rules/information:</strong></p><div>';
		include_once('variants/'. $Variant->name .'/rules.php');
		print '</div>';
	}
}

print '</div>';
libHTML::footer();

?>