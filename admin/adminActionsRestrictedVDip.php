<?php
/*
    Copyright (C) 20013 Oliver Auth

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

class adminActionsRestrictedVDip extends adminActionsSeniorMod
{
	public function __construct()
	{
		parent::__construct();

		$vDipActionsRestricted = array(
			'clearAdvancedAccessLogs' => array(
				'name' => 'Clear advanced access logs',
				'description' => 'Clears advanced access log table of logs older than 60 days.',
				'params' => array(),
			),
			'recalculateRatings' => array(
				'name' => 'Recalculate VDip-ratings',
				'description' => 'Recalculates the ratings for all users. You can enter how many month of rating-data you want to delete bevore the recalculation. '.
									'You might need to recall this function a few times, because of server-timeout-issues it will only recalculate 5000 games at once.',
				'params' => array('month'=>'Month'),
			),
			'delCache' => array(
				'name' => 'Clean the cache directory.',
				'description' => 'Delete the cache files older than the given date.',
				'params' => array('keepLarge'=>'File age (> 50 kB) (in days)',
									'keepSmall'=>'File age (files < 50 kB) (in days)')
			),
			'allReady' => array(
				'name' => 'Ready all orders.',
				'description' => 'Set the orderstatus of all countries to "Ready".',
				'params' => array('gameID'=>'GameID')
			),
			'delVariantGameCache' => array(
				'name' => 'Clear cache of a given variant.',
				'description' => 'Clear all cache files of all games from a given variant.',
				'params' => array('variantID'=>'VariantID')
			),
			'delInactiveVariants' => array(
				'name' => 'Remove all inactive Variants',
				'description' => 'Remove all games from inactive variants...',
				'params' => array(),
			),			
			'makeDevGold' => array(
				'name' => 'Dev: gold',
				'description' => 'Give gold developer marker',
				'params' => array('userID'=>'User ID'),
			),
			'makeDevSilver' => array(
				'name' => 'Dev: silver',
				'description' => 'Give silver developer marker',
				'params' => array('userID'=>'User ID'),
			),
			'makeDevBronze' => array(
				'name' => 'Dev: bronze',
				'description' => 'Give bronze developer marker',
				'params' => array('userID'=>'User ID'),
			),
			'exportGameData' => array(
				'name' => 'Export game data',
				'description' => 'Save all relevant data of a given game.',
				'params' => array('gameID'=>'Game ID'),
			),
			'createEMailValidationCode' => array(
				'name' => 'Create e-mail validation code',
				'description' => 'Create a validation code (e-mail token) for an e-mail address in case a
					user has trouble to receive the automated validation message.</br>
					Must be pasted to the correct URL (e.g. SERVER_NAME/register.php for registration, SERVER_NAME/usercp.php for settings).</br>
					Send this validation code only to the e-mail address you used for generation of the code!',
				'params' => array('eMail'=>'E-mail address')
			)
			
		);
		
		adminActions::$actions = array_merge(adminActions::$actions, $vDipActionsRestricted);
	}
	
	public function recalculateRatings(array $params)
	{

	global $DB,$Misc;
		
		if (!($Misc->Maintenance))
			return l_t('Maintenance mode off. Please turn Maintenance mode on to avoid problems');
		
		set_time_limit(0);
		include_once("lib/rating.php");
		
		$deleteMonths = $params['month'];
		
		if ($deleteMonths  != "") {
			$lastRating = strtotime("-".$deleteMonths." month");
			$DB->sql_put("DELETE r FROM wD_Ratings r
							INNER JOIN wD_Games g ON (g.id = r.gameID)
							WHERE r.ratingType='vDip' && 
								g.finishTime > '".$lastRating."'");
		}

		list ($lastRating) = $DB->sql_row("
				SELECT g.finishTime FROM wD_Games g
					LEFT JOIN wD_Ratings r ON (g.id = r.gameID)
				WHERE
					r.ratingType='vDip'
					&& g.phase = 'Finished'
				ORDER BY g.finishTime DESC LIMIT 1");
		
		$tabl = $DB->sql_tabl("SELECT id FROM wD_Games WHERE phase='Finished' AND finishTime > '".$lastRating."' ORDER BY finishTime ASC");
		$count = 0;
		while ( (list($gameID)=$DB->tabl_row($tabl)) && ($count<5000) )
		{
			libRating::updateRatings($gameID);
			$count++;
		}

		list ($lastRating) = $DB->sql_row("
				SELECT g.finishTime FROM wD_Games g
					LEFT JOIN wD_Ratings r ON (g.id = r.gameID)
				WHERE
					r.ratingType='vDip'
					&& g.phase = 'Finished'
				ORDER BY g.finishTime DESC LIMIT 1");

		list($gamesCount) = $DB->sql_row("SELECT COUNT(*) FROM `wD_Games` WHERE phase = 'Finished' && finishTime > '".$lastRating."'");
		if ($gamesCount == 0)
			return 'Recalculated the ratings for '.$count.' games. No more ratings to recalculate. All done.';
			
		return 'Recalculated the ratings for '.$count.' games. There are still '.$gamesCount.' games to reprocess. So please call this function again with an empty "month" entry.';
	}

	public function recalculateRatingsConfirm(array $params)
	{
		global $DB,$Misc;
		
		if (!($Misc->Maintenance))
			return l_t('Maintenance mode off. Please turn Maintenance mode on to avoid problems');
		
		$deleteMonths = $params['month'];
		if ($deleteMonths  == "") {
			list ($lastRating) = $DB->sql_row("
					SELECT g.finishTime FROM wD_Games g
						LEFT JOIN wD_Ratings r ON (g.id = r.gameID)
					WHERE
						r.ratingType='vDip'
						&& g.phase = 'Finished'
					ORDER BY g.finishTime DESC LIMIT 1");
			$lastRating = ($lastRating==""?0:$lastRating);
			list($gamesCount) = $DB->sql_row("SELECT COUNT(*) FROM `wD_Games` WHERE phase = 'Finished' && finishTime > ".$lastRating);
			if ($gamesCount == 0)
				$info = 'No more games to recalculate...';
			else
				$info = 'I will recalculate '.$gamesCount.' more games.';
		} else {
			$lastRating = strtotime("-".$deleteMonths." month");
			list($gamesCount) = $DB->sql_row("SELECT COUNT(*) FROM `wD_Games` WHERE phase = 'Finished' && finishTime > ".$lastRating);
			$info = 'I will delete rating-data from the last '.$deleteMonths.' month and start recalculation.<br>This will recalculate '.$gamesCount.' games.';
		}
		return $info;
	}

	public function clearAdvancedAccessLogs(array $params)
	{
		global $DB;

		list($i) = $DB->sql_row("SELECT COUNT(userID) FROM wD_AccessLogAdvanced WHERE DATEDIFF(CURRENT_DATE, request) > 60");
		$DB->sql_put("DELETE FROM wD_AccessLogAdvanced WHERE DATEDIFF(CURRENT_DATE, request) > 60");
		$DB->sql_put("OPTIMIZE TABLE wD_AccessLogAdvanced");
		return 'Old advanced access logs cleared; '.$i.' records deleted.';
	}

	public function RowAsString(array $row)
	{
		global $DB;
		
		$return = '';
		for($j=0; $j<count($row); $j++) 
		{
			$row[$j] = $DB->escape($row[$j]);
			
			if ($row[$j] == 'NULL' || substr($row[$j],0,1) == '@')
				$return .= $row[$j];
			else
				$return.= '"'.$row[$j].'"';
			
			if ($j<(count($row)-1)) { $return.= ','; }
		}
		return $return;
	}
	
	public function exportGameData(array $params)
	{
		global $DB, $User;
		$gameID = (int)$params['gameID'];

		// Export wD_Games
		$row = $DB->sql_row('SELECT * FROM wD_Games WHERE id='.$gameID);
		$row[1]='NULL';
		if ($row[4] == '') $row[4]= 'NULL';		// processTime
		$row[6]=$row[6]." (gameid=".$gameID.")";// name
		$row[9]= 'NULL';		                // password always empty
		if ($row[11] == '') $row[11]= 'NULL';	// pauseTimeRemaining
		if ($row[12] == '') $row[12]= 'NULL';	// minimumBet	
		$row[14]= 'No';			                // never anon
		$return = "INSERT INTO wD_Games VALUES (".$this->RowAsString($row).");\n";
		$return.= "SET @gameID = LAST_INSERT_ID();\n";

		// Export wD_Members
		$tabl = $DB->sql_tabl('SELECT * FROM wD_Members WHERE gameID='.$gameID);
		while($row = $DB->tabl_row($tabl))
		{
			$row[0] = 'NULL';
			$row[1]= $row[3] + 4;					// use UserID 5 and up
			$row[2]= '@gameID';						// gameID
			if ($row[11] == '') $row[11]= '""';		// votes
			if ($row[12] == '') $row[12]= 'NULL';	// pointsWon	
			if ($row[13] == '') $row[13]= 'NULL';	// gameMessagesSent	
			$return .= "INSERT INTO wD_Members VALUES (".$this->RowAsString($row).");\n";
		}
		
		// Export wD_Units
		$tabl = $DB->sql_tabl('SELECT * FROM wD_Units WHERE gameID='.$gameID);
		while($row = $DB->tabl_row($tabl))
		{
			$unitID = $row[0];
			$row[0] = 'NULL';
			$row[4] = '@gameID';			
			$return .= "INSERT INTO wD_Units VALUES (".$this->RowAsString($row)."); ";
			$return .= "SET @unit_".$unitID." = LAST_INSERT_ID();\n";
		}
		
		// Export wD_Orders
		$tabl = $DB->sql_tabl('SELECT * FROM wD_Orders WHERE gameID='.$gameID);
		while($row = $DB->tabl_row($tabl))
		{
			$row[0]= 'NULL';
			$row[1]= '@gameID';
			$row[4]= '@unit_'.$row[4];		
			if ($row[5] == '') $row[5]= 'NULL';				
			if ($row[6] == '') $row[6]= 'NULL';				
			if ($row[7] == '') $row[7]= 'NULL';				
			$return .= "INSERT INTO wD_Orders VALUES (".$this->RowAsString($row).");\n";
		}
		
		// Export wD_TerrStatus
		$tabl = $DB->sql_tabl('SELECT * FROM wD_TerrStatus WHERE gameID='.$gameID);
		while($row = $DB->tabl_row($tabl))
		{
			$row[0]= 'NULL';
			$row[4]= '@gameID';													// gameID
			if ($row[2] == '') $row[2]= 'NULL';									// occupiedFromTerrID
			if ($row[5] != '') $row[5]= '@unit_'.$row[5]; else $row[5]='NULL';	// occupyingUnitID
			if ($row[6] != '') $row[6]= '@unit_'.$row[6]; else $row[6]='NULL';	// retreatingUnitID
			$return .= "INSERT INTO wD_TerrStatus VALUES (".$this->RowAsString($row).");\n";
		}
		
		// Export wD_TerrStatusArchive
		$tabl = $DB->sql_tabl('SELECT * FROM wD_TerrStatusArchive WHERE gameID='.$gameID);
		while($row = $DB->tabl_row($tabl))
		{
			$row[3]= '@gameID';
			$return .= "INSERT INTO wD_TerrStatusArchive VALUES (".$this->RowAsString($row).");\n";
		}
		
		// Export wD_MovesArchive
		$tabl = $DB->sql_tabl('SELECT * FROM wD_MovesArchive WHERE gameID='.$gameID);
		while($row = $DB->tabl_row($tabl))
		{
			$row[1] = '@gameID';
			if ($row[4] == '') $row[4]= 'NULL';				//
			if ($row[8] == '') $row[8]= 'NULL';				//
			if ($row[9] == '') $row[9]= 'NULL';				//
			$return .= "INSERT INTO wD_MovesArchive VALUES (".$this->RowAsString($row).");\n";
		}
		
		//save file
		$filename = libCache::dirID('users',$User->id).'/backup-'.$gameID.'-'.time().'.sql';
		$handle = fopen($filename,'w+');
		fwrite($handle,$return);
		fclose($handle);
		
		return "Gamedata exported. (<a href='".$filename."'>Click here for download</a>)";
		
	}
	
	private function makeDevType(array $params, $type='') {
		global $DB;

		$userID = (int)$params['userID'];

		$DB->sql_put("UPDATE wD_Users SET type = CONCAT_WS(',',type,'Dev".$type."') WHERE id = ".$userID);

		return 'User ID '.$userID.' given donator status.';
	}
	public function makeDevGold(array $params)
	{
		return $this->makeDevType($params,'Gold');
	}
	public function makeDevSilver(array $params)
	{
		return $this->makeDevType($params,'Silver');
	}
	public function makeDevBronze(array $params)
	{
		return $this->makeDevType($params,'Bronze');
	}
	
	public function delInactiveVariants(array $params)
	{
		global $DB;
		$DB->sql_put("DELETE wD_Members
						FROM wD_Members
						INNER JOIN wD_Games ON (wD_Games.id = wD_Members.gameID)
						WHERE wD_Games.variantID NOT IN (".implode(',',array_keys(Config::$variants)).")");		
		$DB->sql_put("DELETE FROM wD_Games WHERE variantID NOT IN (".implode(',',array_keys(Config::$variants)).")");
		return 'Removed all data of all inactive variants.';		
	}
	public function delInactiveVariantsConfirm(array $params)
	{
		return 'Do you really want to remove all data of all inactive variants (can\'t be undone)?';
	}

	public function delVariantGameCache(array $params)
	{
		global $DB;
		$variantID = (int)$params['variantID'];
		$Variant = libVariant::loadFromVariantID($variantID);
		$tabl=$DB->sql_tabl("SELECT id FROM wD_Games WHERE variantID = ".$variantID );
		$count = 0;
		while( list($gameID) = $DB->tabl_row($tabl) )
		{
			$gamesDir = libCache::dirID('games',$gameID);
			$this->del_cache($gamesDir, '0 days');
			$count++;
		}
		$VariantCache=opendir('variants/'.$Variant->name.'/cache');
		while (false !== ($file=readdir($VariantCache)))
			if($file[0]!=".") unlink ('variants/'.$Variant->name.'/cache/'.$file);
		return 'Cleared all cache data for the '.$Variant->name.'-variant ('.$count.' games).';
	}
	public function delVariantGameCacheConfirm(array $params)
	{
		global $DB;
		$variantID = (int)$params['variantID'];
		$Variant = libVariant::loadFromVariantID($variantID);
		list($runningGamesCount)=$DB->sql_row("SELECT count(*) FROM wD_Games WHERE variantID = ".$variantID );
		$tabl=$DB->sql_tabl("SELECT id FROM wD_Games WHERE variantID = ".$variantID );
		
		return 'Do you want to clear all cache data for the '.$Variant->name.'-variant ('.$runningGamesCount.' games)?';
	}
	
	public function allReady(array $params)
	{
		global $DB;
		$gameID = (int)$params['gameID'];
		$DB->sql_put("UPDATE wD_Members SET orderStatus = 'Ready',
			missedPhases=IF(missedPhases > 0 , missedPhases - 1, 0)
			WHERE gameID = ".$gameID);		
		return 'Orderstatus of all countries set to "Ready".';
	}
	public function allReadyConfirm(array $params)
	{
		$gameID = (int)$params['gameID'];
		return 'Are you sure you want to change the orderstatus of all countries to "Ready"';
	}
	
	public function delcache(array $params)
	{
		$keepLarge = '-'.(int)$params['keepLarge'].' days';
		$this->del_cache('cache', $keepLarge, 50);
		$keepSmall = '-'.(int)$params['keepSmall'].' days';
		$this->del_cache('cache', $keepSmall, 0);
		return 'Deleted files bigger 50k older than '.(int)$params['keepLarge'].' days, all other '.(int)$params['keepSmall'].' days.';
	}
	public function delcacheConfirm(array $params)
	{
		$keepSmall = (int)$params['keepSmall'];
		$keepLarge = (int)$params['keepLarge'];
		return 'Are you sure you want to delete files <ol><li>Bigger 50k older than '.$keepLarge.' days?</li><li>All other '.$keepSmall.' days?</li></ol>';
	}

	function del_cache($dirname, $keep, $filesize = 0) 
	{
		if(is_dir($dirname))
			$dir_handle=opendir($dirname); 
		while (false !== ($file=readdir($dir_handle)))
		{
			if($file!="." && $file!="..") 
			{ 
				if(!is_dir($dirname."/".$file))
				{
					if (filesize($dirname."/".$file) > $filesize * 1024)
					{
						if ((filemtime($dirname."/".$file)) < (strtotime($keep)))
						{
							unlink ($dirname."/".$file);
						}
					}
				}
				else
				{
					$this->del_cache($dirname."/".$file, $keep, $filesize);
				}
			} 
			
		} 
		closedir($dir_handle); 
		$files = @scandir($dirname);
		if (count($files) < 3) rmdir($dirname); 
	}
	
	public function createEMailValidationCode(array $params)
	{
		require_once('lib/auth.php');
		require_once('objects/user.php');
		
		$email = $params['eMail'];
		
		// Check that email is valid

		if( User::findEmail($email) )
			return l_t("The e-mail address '%s' is already in use.",$email);

		if ( !libAuth::validate_email($email) )
			return l_t("The e-mail address '%s' seems to be invalid.",$email);
		
		$URL = '[URL]';

		// %7C = | , but some webmail clients think that | is the end of the link
		$emailToken = substr(md5(Config::$secret.$email),0,5).'%7C'.urlencode($email);

		return $URL.'?emailToken='.$emailToken;
	}	
}
?>
