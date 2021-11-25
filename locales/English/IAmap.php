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
	<li class="formlisttitle">Territory-grey-out:</li>
	<li class="formlistfield">
		<input type="radio" name="userForm[terrGrey]" value="all" <?php if($User->terrGrey=='all') print "checked"; ?>>Always on
		<input type="radio" name="userForm[terrGrey]" value="selected"  <?php if($User->terrGrey=='selected')  print "checked"; ?>>Only for selected units
		<input type="radio" name="userForm[terrGrey]" value="off"  <?php if($User->terrGrey=='off')  print "checked"; ?>>Off
		</li>
	<li class="formlistdesc">
		If set to "always on", everything apart from your own units will be greyed out if you have to select a new unit for an order and territories which can
		not be accessed for a selected unit with a selected order will be greyed-out.<br>
		If set to "only for selected units" your own units won't be highlighted so you can see a clear map.
	</li>
	
	<li class="formlisttitle">Grey-out intensity:</li>
	<li class="formlistfield">
		<input type="text" name="userForm[greyOut]" size=3 maxlength=2 value="<?php print $User->greyOut ?>">%
	</li>
	<li class="formlistdesc">
		The intensety of the grey overlay in %. Input any number between 10% (light grey) and 90%(dark grey)
	</li>
	
	<li class="formlisttitle">Scrollbars on map:</li>
	<li class="formlistfield">
		<input type="radio" name="userForm[scrollbars]" value="Yes" <?php if($User->scrollbars=='Yes') print "checked"; ?>>Yes
		<input type="radio" name="userForm[scrollbars]" value="No"  <?php if($User->scrollbars=='No')  print "checked"; ?>>No
	</li>
	<li class="formlistdesc">
		Adds or removes scrollbars if the interactive map is activated, so large maps can be displayed on the whole screen or only in a small frame.
	</li>
	
	<li class="formlisttitle">Button size:</li>
	<li class="formlistfield">
		<input type="radio" name="userForm[buttonWidth]" value="auto" <?php if($User->buttonWidth=='auto') print "checked"; ?>>Automatic
		<input type="radio" name="userForm[buttonWidth]" value="large"  <?php if($User->buttonWidth=='large')  print "checked"; ?>>Large
		<input type="radio" name="userForm[buttonWidth]" value="small"  <?php if($User->buttonWidth=='small')  print "checked"; ?>>Small
	</li>
	<li class="formlistdesc">
		Choose the button size for the on-map order menu. "Automatic" chooses large buttons if the website is displayed in mobile view and small buttons otherwise. 
	</li>

	<li class="formlisttitle">Opt out:</li>
	<li class="formlistfield">
		<input type="radio" name="userForm[pointNClick]" value="Yes" <?php if($User->pointNClick=='Yes') print "checked"; ?>>Use the interactive map if possible
		<input type="radio" name="userForm[pointNClick]" value="No"  <?php if($User->pointNClick=='No')  print "checked"; ?>>No, thanks.
	</li>
	<li class="formlistdesc">
		Opt out of the interactive map.
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