<?php
/*
    Copyright (C) 2013 Oliver Auth

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

?>
	<li class="formlisttitle">Sort order for units:</li>
	<li class="formlistfield">
		Unit type:<select name="userForm[unitOrder]">
			<option value='Mixed'<?php if($User->unitOrder=='Mixed') print "selected"; ?>>Mixed</option>
			<option value='FA'   <?php if($User->unitOrder=='FA')    print "selected"; ?>>Fleets -> Armies</option>
			<option value='AF'   <?php if($User->unitOrder=='AF')    print "selected"; ?>>Armies -> Fleets</option>
		</select> - 
		Sort by: <select name="userForm[sortOrder]">
			<option value='BuildOrder'<?php if($User->sortOrder=='BuildOrder') print "selected"; ?>>Build Order</option>
			<option value='TerrName'  <?php if($User->sortOrder=='TerrName')   print "selected"; ?>>Territory Name</option>
			<option value='NorthSouth'<?php if($User->sortOrder=='NorthSouth') print "selected"; ?>>North -> South</option>
			<option value='EastWest'  <?php if($User->sortOrder=='EastWest')   print "selected"; ?>>East -> West</option>
		</select>
	</li>
	<li class="formlistdesc">
		How should the units be sorted in the command overview.
	</li>
	
	<li class="formlisttitle">Show countryname:</li>
	<li class="formlistfield">
		<strong>In global chat:</strong>
		<input type="radio" name="userForm[showCountryNames]" value="Yes" <?php if($User->showCountryNames=='Yes') print "checked"; ?>>Yes
		<input type="radio" name="userForm[showCountryNames]" value="No"  <?php if($User->showCountryNames=='No')  print "checked"; ?>>No
	</li>
	<li class="formlistfield">
		<strong>On the map:</strong>
		<input type="radio" name="userForm[showCountryNamesMap]" value="Yes" <?php if($User->showCountryNamesMap=='Yes') print "checked"; ?>>Yes
		<input type="radio" name="userForm[showCountryNamesMap]" value="No"  <?php if($User->showCountryNamesMap=='No')  print "checked"; ?>>No
	</li>
	<li class="formlistdesc">
		Instead of colored chatmessages print the countryname in front of the text and use only black text.
		Print the countryname on the map.
	</li>

	<li class="formlisttitle">Site style:</li>
	<li class="formlistfield">
		<input type="radio" name="userForm[cssStyle]" value="vDip"   <?php if($User->cssStyle=='vDip')    print "checked"; ?>>vDip
		<input type="radio" name="userForm[cssStyle]" value="webDip" <?php if($User->cssStyle=='webDip')  print "checked"; ?>>webDip
	</li>
	<li class="formlistdesc">
		Choose the site-style / colors of vDiplomacy. You can use the default vDip-colors or the original webDip-colors. <b>You might need to update twice for this to take effect.</b>
	</li>

	<li class="formlisttitle">RSS ID:</li>
	<li class="formlistfield">
<?php
	list($rssID) = $DB->sql_row("SELECT rssID FROM wD_Users WHERE id=".$User->id);
	if ($rssID == '')
		print "You don't have a valid RSS ID.";
	else
		print "Your current RSS id: <b>".$rssID."</b>";
?>
		<input type="submit" class="form-submit notice" name="rssButton" value="Generate new"> <input type="submit" class="form-submit notice" name="rssButton" value="Delete"><br>
	</li>
<?php
	$rssLink = 'http://'.$_SERVER['HTTP_HOST'].str_replace("usercp", "rss", $_SERVER['PHP_SELF']).'?rssID='.$rssID;
	if ($rssID != '') print '
		<li class="formlistfield">
			You can access your personal RSS feed with the following link:
			<ul><li><b>
			<a href="'.$rssLink.'">'.$rssLink.'</a>
			</b></li></ul>
		</li>';
?>
	<li class="formlistdesc">
		To access your personal RSS-feed you need to generate a separate RSS id. Make sure no one has access to this ID. If in doubt generate a new one.
	</li>
	
<?php
/*
 * This is done in PHP because Eclipse complains about HTML syntax errors otherwise
 * because the starting <form><ul> is elsewhere
 */
print '</ul>

<div class="hr"></div>

<input type="submit" class="form-submit notice" value="Update">';

?>