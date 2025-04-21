<?php
/*
    Copyright (C) 2004-2010 Kestas J. Kuliukas

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

/**
 * Hall of fame; the top 100 webDip scorers
 *
 * @package Base
 * @subpackage Game
 */

require_once('header.php');

libHTML::starthtml();

print libHTML::pageTitle(l_t('Hall of fame'),l_t('The vDiplomacy hall of fame; the 100 highest ranking players on the site.'));


print '<button class="SearchCollapsible">All Time Points</button>';
print'<div class="advancedSearchContent">';

if ( $User->type['User'] && $User->vpoints > 1000 )
{

	list($position) = $DB->sql_row("SELECT COUNT(id)+1 FROM wD_Users WHERE vpoints > ".$User->vpoints);

//	$players = $Misc->RankingPlayers;
	list($players) = $DB->sql_row("SELECT COUNT(id)+1 FROM wD_Users WHERE vpoints > 1000");
	
	print '<p class = "hof">'.l_t('You are ranked %s out of %s players with over 1000%s','<a href="#me" class="light">#'.$position.'</a>',$players,libHTML::vpoints()).
		l_t('. For more stats on your ranking, visit <a class="light" href="profile.php?userID='.$User->id.'">your profile</a>.').'</p>';
}

$i=1;
$crashed = $DB->sql_tabl("SELECT id, username, vpoints FROM wD_Users order BY vpoints DESC LIMIT 100 ");

print "<TABLE class='hof'>";
print "<tr>";
print '<th class= "hof">Points/Rank</th>';
print '<th class= "hof">User</th>';
print "</tr>";

$showMe = 1;
while ( list($id, $username, $points) = $DB->tabl_row($crashed) )
{
	print ' <tr class="hof">
			<td class="hof"> '.number_format($points).' '.libHTML::vpoints().' - #'.$i.' </td>';
	if ($User->username == $username)
	{
		print '<td class="hof"><a class="hof-self" href="profile.php?userID='.$id.'">'.$username.'</a></td> ';
		$showMe = 0;
	}
	else
	{
		print '<td class="hof"><a href="profile.php?userID='.$id.'">'.$username.'</a></td> ';
	}
	print'	</tr>';
	$i++;
}

if ( $User->type['User'] && $User->vpoints > 1000 && $showMe == 1 )
{
	print ' <tr class="hof"><td class="hof">...</td><td class="hof">...</td></tr>';
	print ' <tr class="hof">
			<td class="hof"> '.number_format($User->vpoints).' '.libHTML::vpoints().' - <a name="me"></a>#'.$position.' </td>
			<td class="hof" style="color:red;"><strong><em>'.$User->username.'</em></strong></td>
			</tr>';
}

print '</table>';
print '</div></br></br>';

print '<button class="SearchCollapsible">All Time Points (Last 6 Months)</button>';
print'<div class="advancedSearchContent"></br>';

$sixMonths = time() - 15552000;

if ( $User->type['User'] && $User->vpoints > 1000 && $User->timeLastSessionEnded > $sixMonths)
{
	list($position) = $DB->sql_row("SELECT COUNT(id)+1 FROM wD_Users WHERE vpoints > ".$User->vpoints." AND timeLastSessionEnded > ".$sixMonths);

	list($playersSixMonths) = $DB->sql_row("SELECT COUNT(1) FROM wD_Users WHERE vpoints > 1000  AND timeLastSessionEnded > ".$sixMonths);
	
	print '<p class = "hof">'.l_t('You are ranked %s out of %s players with over 1000%s who have been active in the last six months','<a href="#me" class="light">#'.$position.'</a>',$playersSixMonths,libHTML::vpoints()).
		l_t('. For more stats on your ranking visit <a class="light" href="profile.php?userID='.$User->id.'">your profile</a>.').'</p>';
}

$i=1;

$crashed = $DB->sql_tabl("SELECT id, username, vpoints FROM wD_Users WHERE timeLastSessionEnded > ".$sixMonths." order BY vpoints DESC LIMIT 100 ");

print "<TABLE class='hof'>";
print "<tr>";
print '<th class= "hof">Points/Rank</th>';
print '<th class= "hof">User</th>';
print "</tr>";

$showMe = 1;
while ( list($id, $username, $points) = $DB->tabl_row($crashed) )
{
	print ' <tr class="hof">
			<td class="hof"> '.number_format($points).' '.libHTML::vpoints().' - #'.$i.' </td>';
	if ($User->username == $username)
	{
		print '<td class="hof"><a class="hof-self" href="profile.php?userID='.$id.'">'.$username.'</a></td> ';
		$showMe = 0;
	}
	else
	{
		print '<td class="hof"><a href="profile.php?userID='.$id.'">'.$username.'</a></td> ';
	}
	print'	</tr>';
	$i++;
}
if ( $User->type['User'] && $User->vpoints > 1000 &&  $User->timeLastSessionEnded > $sixMonths and $showMe == 1 )
{
	print ' <tr class="hof"><td class="hof">...</td><td class="hof">...</td></tr>';
	print ' <tr class="hof">
			<td class="hof"> '.number_format($User->vpoints).' '.libHTML::vpoints().' - <a name="me"></a>#'.$position.' </td>
			<td class="hof" style="color:red;"><strong><em>'.$User->username.'</em></strong></td>
			</tr>';
}

print '</table>';
print '</div>';

print '</div>';
?>

<script>
var coll = document.getElementsByClassName("SearchCollapsible");
var searchCounter;

for (searchCounter = 0; searchCounter < coll.length; searchCounter++) {
  coll[searchCounter].addEventListener("click", function() {
    this.classList.toggle("active");
    var content = this.nextElementSibling;
		if (content.style.display === "block") { content.style.display = "none"; }
		else { content.style.display = "block"; }
  });
}
</script>

<?php
libHTML::footer();
?>
