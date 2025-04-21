<?php
/*
    Copyright (C) 2004-2010 Kestas J. Kuliukas & Yuri Hryniv aka Flame

	This file is part of webDiplomacy.

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

defined('IN_CODE') or die('This script can not be run by itself.');

/**
 * A graph of the turn by turn share of supply centers.
 *
 * @package Board
 */

print '<h3>Graph</h3>';
print '<br />'.l_t('Occupied territory by game years as a percentage:<br /><br />');

$scCountsByTurn=array();
for($i=1;$i<$Game->turn+1;$i=$i+2)
{
	$tabl=$DB->sql_tabl("SELECT ts.countryID, COUNT(ts.countryID) FROM wD_TerrStatusArchive ts INNER JOIN wD_Territories t ON ( t.id=ts.terrID AND t.supply='Yes' AND t.coastParentID=t.id AND t.mapID=".$Variant->mapID." ) WHERE ts.gameID=".$Game->id." AND ts.turn=".$i." GROUP BY ts.countryID");
	$scCountsByCountryID=array();
	while(list($countryID,$scCount)=$DB->tabl_row($tabl))
		if (($countryID <= count($Variant->countries) && $countryID <> 0))
			$scCountsByCountryID[$countryID]=$scCount;

	$scCountsByTurn[$i]=$scCountsByCountryID;
}

foreach( $scCountsByTurn as $turn=>$scCountsByCountryID)
{
	$turnSCTotal=0;
	foreach($scCountsByCountryID as $countryID=>$scCount)
		$turnSCTotal+=$scCount;

	if( $turnSCTotal==0 )
	{
		unset($scCountsByTurn[$turn]);
		break;
	}

	$percentLeft=100;
	foreach($scCountsByCountryID as $countryID=>$scCount)
	{
		$percent=100.0*($scCount/$turnSCTotal);
		$percentLeft-=$percent;

		$scCountsByTurn[$turn][$countryID] = $percent;
	}

	// if there is any percents left distribute even between all countries still playing.
	if ($percentLeft > 0)
		foreach($scCountsByCountryID as $countryID=>$scCount)
			$scCountsByTurn[$turn][$countryID] += ($percentLeft / count($scCountsByCountryID));
}

$scRatiosByTurn=$scCountsByTurn;
unset($scCountsByTurn);

if( $Game->phase != 'Finished' ){
	print l_t('Only available for finished games..');
	return;
}

if( count($scRatiosByTurn)<2 ) {
	print l_t('Too few moves to plot.');
	return;
}

print '<table border=0 cellspacing=0 cellpadding=0>
		<tr>
			<td class="mapshadow" bgcolor="#D8D8D8" width="100%" style="padding: 0px;">
			<div class="variant'.$Variant->name.' boardGraph" style="width:auto">';

foreach ($scRatiosByTurn as $turn=>$scRatiosByCountryID)
{
	print '<div class="boardGraphTurn" style="width:auto">';
	foreach($scRatiosByCountryID as $countryID=>$scRatio)
	{
		$countryName=mb_strtoupper(substr($Variant->countries[$countryID-1],0,2));
		print '<div class="boardGraphTurnCountry occupationBar'.$countryID.'" '.
			'style="text-align:center; font-size:10pt; font-weight:bold; overflow:hidden;'.
			'float:left;width:'.$scRatio.'%">'.$countryName.'~'.floor($scRatio).'%</div>';
	}
	print '<div style="clear:both"></div>';
	print '</div>';
}

print '</div>';
print '</table>';


?>