<?php

$userID=0; 
$reason = '';

if ( isset($_REQUEST['userID']) && $gameID == 0 )
{
	$userID=(int)$_REQUEST['userID'];

	if ( isset($_REQUEST['reason']))
		$reason = htmlentities( $_REQUEST['reason'], ENT_NOQUOTES, 'UTF-8');
}

/**
 * Print a form for selecting which game to check, and which users to check against
 */
print '<div class="hr"></div><FORM method="get" action="admincp.php">
			<STRONG>User ID: </STRONG><INPUT type="text" name="userID" value="'.($userID!=0?$userID:'').'" length="10" />
			+ Reason: <INPUT type="text" name="reason" value="'.$reason.'" length="80" />
			<input type="submit" name="Submit" class="form-submit" value="Check" /></form>';

if ($userID != 0 && $reason != '')
{
	$DB->sql_put("INSERT INTO wD_AdminLog ( name, userID, time, details, params )
					VALUES ( 'CheckPMs', ".$User->id.", ".time().", '".$reason."', 'UserID:".$userID."' )");

	$notices=array();
	
	$tabl=$DB->sql_tabl("SELECT *
		FROM wD_Notices WHERE toUserID=".$userID."
		AND type='PM'
		ORDER BY timeSent DESC");
		
	while($hash=$DB->tabl_hash($tabl))
		$notices[] = new notice($hash);

	foreach($notices as $pm)
		print $pm->html();
	
}

?>