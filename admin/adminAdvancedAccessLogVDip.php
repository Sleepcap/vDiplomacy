<?php

defined('IN_CODE') or die('This script can not be run by itself.');

$userIDs = $gameIDs = $checkIPs = $checkIPsLong = '';

if ( isset($_REQUEST['userIDs']))
{
	foreach (explode(',',$_REQUEST['userIDs']) as $userID)
	{
		$userID=(int)$userID;
		if ($userID != 0)
			$allUserIDs[] = $userID;
	}
	if (isset($allUserIDs))
		$userIDs = implode (',',$allUserIDs);	
}

if ( isset($_REQUEST['gameIDs']))
{
	foreach (explode(',',$_REQUEST['gameIDs']) as $gameID)
	{
		$gameID=(int)$gameID;
		if ($gameID != 0)
			$allGameIDs[] = $gameID;
	}
	if (isset($allGameIDs))
		$gameIDs = implode (',',$allGameIDs);	
}	

if ( isset($_REQUEST['checkIPs']))
{
	foreach (explode(',',$_REQUEST['checkIPs']) as $checkIP)
	{
		$checkIPlong=ip2long($checkIP);
		if ($checkIPlong != 0)
		{
			$allIPs[] = $checkIP;
			$allIPsLong[] = $checkIPlong;
		}
	}
	if (isset($allIPs))
	{
		$checkIPs = implode (',',$allIPs);
		$checkIPsLong = implode (',',$allIPsLong);
	}
}

/* Print some information how to use the Advanced-search-tool
 */
 
print '<br><button class="modToolsCollapsible">How to use the Advanced-login-search</button>
<div class="modToolsContent">
	<p class="modTools">This tool will print a list of all logins of 1 or more users, in one or more games or from one or more IPs. Each field can be empty
	to search for all users, games or IPs. The more parameters you provide, the more precise will the result.</p>

	<ui class="modTools"> Explaining Paramaters:
		<li>User IDs: This is a comma separated lists (or empty) of all userIDs you want to check.</li>
		<li>GameIDs: This is a comma separated lists (or empty) of all GameIDs you want to check.</li>
		<li>IPs: This is a comma separated lists (or empty) of all IPs you want to check.</li>
	</ui>
</div>';


/**
 * Print a form for selecting which users to check
 */
print '<FORM method="get" action="admincp.php">
			<INPUT type="hidden" name="tab" value="AccessLog" />
			<HR>
			<STRONG>Request complete login-information:</STRONG>
			<TABLE>
				<TR><TD>User IDs:</TD> <TD><INPUT type="text" name="userIDs"  value="'.$userIDs .'" size="50" /></TD></TR>
				<TR><TD>GameIDs: </TD> <TD><INPUT type="text" name="gameIDs"  value="'.$gameIDs .'" size="50" /></TD></TR>
				<TR><TD>IPs:     </TD> <TD><INPUT type="text" name="checkIPs" value="'.$checkIPs.'" size="50" /></TD></TR>
				<TR><TD><input type="submit" name="Submit" class="form-submit" value="Check" /></TD></TR>		
			</TABLE>
		<HR></FORM>';


if ($userIDs.$checkIPsLong.$gameIDs != '')
{
	global $DB;
	/*
					WHERE ac.userID '.
					(isset($userIDs)?' IN ('.$userIDs.') ':' != 0 ').
					(isset($checkIPsLong)? ' AND ac.ip IN ('.$checkIPsLong.') ' : '').
					(isset($gameIDs)?      ' AND m.gameID IN ('.$gameIDs.') ' : '').'
	*/
	$sql = 'SELECT ac.userID, u.username, ac.request, ac.ip, ac.action, m.gameID, m.countryID
				FROM wD_AccessLogAdvanced ac
				LEFT JOIN wD_Members m ON (ac.memberID = m.id)
				LEFT JOIN wD_Users u ON (u.id = ac.userID)				
					WHERE ac.userID '.
					($userIDs      != ''?              ' IN ('.$userIDs.') '      : ' != 0 ').
					($checkIPsLong != ''? ' AND ac.ip    IN ('.$checkIPsLong.') ' : ''      ).
					($gameIDs      != ''? ' AND m.gameID IN ('.$gameIDs.') '      : ''      ).'
				ORDER BY request ASC';
	
	$tabl = $DB->sql_tabl($sql);

	$timetable = array();
	$lastkey = 0;
	$lastday = '';

	while ( list($userID, $username, $time, $ip, $action, $gameID, $countryID) = $DB->tabl_row($tabl) )
	{
		if ($gameID != '')
			$game_users[$gameID][] = $userID;	
		$ip_users[$ip][] = $userID;
		
		if ($gameID != 0 && !isset($countries[$gameID]))
		{
			$Variant=libVariant::loadFromGameID($gameID);
			$countries[$gameID] = $Variant->countries;
		}
		
		$day = substr($time, 5,5);
		if ($day == $lastday)
			$day = '';
		else
			$lastday = $day;
			
		if ($lastkey != 0
			&& $timetable[$lastkey]['IP']        == $ip
			&& $timetable[$lastkey]['userID']    == $userID
			&& $timetable[$lastkey]['action']    == $action
			&& $timetable[$lastkey]['gameID']    == $gameID
			&& $timetable[$lastkey]['countryID'] == $countryID)
		{
			$timetable[$lastkey]['timeEnd'] = substr($time,11,5);
		}
		elseif ( !($action == 'Board' && $countryID == 0) )
		{
			$lastkey++;
			$timetable[$lastkey]=array(
				'day'      => $day,
				'timeStart'=> substr($time,11,5),
				'timeEnd'  => '',
				'IP'       => $ip,
				'userID'   => $userID,
				'username' => $username,
				'action'   => $action,
				'gameID'   => $gameID,
				'countryID'=> $countryID
			);
		}
	}

	// Exit if there are no matches found....
	if (!(isset($ip_users)))
	{
		print "<strong>No matches found.</strong>";
		return;
	}
	
	asort ($ip_users);
	print '<BR><STRONG>More than one user for one IPs used:</STRONG>
			<TABLE class="modTools">
			<THEAD>
				<TH class="modTools">IP</TH>
				<TH class="modTools">count</TH>
				<TH class="modTools">username(s)</TH>
			</THEAD>';
	foreach ($ip_users as $ip=>$ipuser)
	{
		$ipuser= array_unique($ipuser);
		if (count($ipuser) > 1)
		{
			print '<tr><td>'.long2ip($ip).'</td><td>'.count($ipuser).'</td><td>';
			foreach ($ipuser as $ipuserID)
			{
				$CheckUser = new User($ipuserID);
				print '<a href="profile.php?userID='.$CheckUser->id.'">'.$CheckUser->username.'</a> ';
			}
			print '</td></tr>';
		}
	}
	print '</TABLE>';
	
	asort ($game_users);
	print '<BR><STRONG>More than one user in the same game:</STRONG>
				<TABLE class="modTools">
				<THEAD>
					<TH class="modTools">GameID</TH>
					<TH class="modTools">count</TH>
					<TH class="modTools">username(s)</TH>
				</THEAD>';
	foreach ($game_users as $game=>$gameuser)
	{
		$gameuser= array_unique($gameuser);
		if (count($gameuser) > 1)
		{
			print '<tr><td><A href="board.php?gameID='.$game.'">'.$game.'</A></td><td>'.count($gameuser).'</td><td>';
			foreach ($gameuser as $gameuserID)
			{
				$CheckUser = new User($gameuserID);
				print '<a href="profile.php?userID='.$CheckUser->id.'">'.$CheckUser->username.'</a> ';
			}
			print '</td></tr>';
		}
	}
	print '</TABLE>';

	print '<BR><STRONG>Timetable:</STRONG>
				<TABLE class= "modTools">
				<THEAD>
					<TH class="modTools">day</TH>
					<TH class="modTools">time</TH>
					<TH class="modTools">ip</TH>
					<TH class="modTools">username</TH>
					<TH class="modTools">gameID</TH>
					<TH class="modTools">country</TH>
				</THEAD>';
		
	foreach ($timetable as $row)
	{
		print '<TR>
			<TD class="modTools">'.$row['day'].'</TD>
			<TD class="modTools">'.$row['timeStart'].($row['timeEnd'] != '' ? ' -> '.$row['timeEnd']:'').'</TD>
			<TD class="modTools">'.long2ip($row['IP']).'</TD>
			<TD class="modTools"><A href="profile.php?userID='.$row['userID'].'">'.$row['username'].'</A></TD>';
		if ($row['action'] == 'Board')
		{
			print '<TD class="modTools"><A href="board.php?gameID='.$row['gameID'].'">'.$row['gameID'].'</A></TD>';
			if ($row['countryID'] != 0)
				print '<TD class="modTools">'.$countries[($row['gameID'])][($row['countryID'] - 1)].'</TD>';
			else
				print '<TD></TD>';
		}
		else
			print '<TD  class= "modTools" colSpan=2>'.$row['action'].'</TD>';
		print '</TR>';
	}		
	print '</TABLE>';
}
?>

