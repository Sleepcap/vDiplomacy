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

class libReliability
{
	/**
	 * The sanctions applied with a certain integrity rating.
	 * Sanctions apply if rating is key of array or below.
	 * 
	 * integrityRating => array() of sanctions
	 */
	static public $sanctions = array(
		-4 => array('tempBan'=> 1, 'gameLimit'=>5),
		-5 => array('tempBan'=> 3, 'gameLimit'=>4),
		-6 => array('tempBan'=> 3, 'gameLimit'=>3),
		-7 => array('tempBan'=> 7, 'gameLimit'=>2),
		-8 => array('tempBan'=> 7, 'gameLimit'=>1)
	);
	
	static public $maxRatingForSanction = -4;
	
	static public function getCurrentSanction($User)
	{
		$integrity = $User->getIntegrityRating();
		// detect current $sanctions
		$userSanction = array('tempBan'=>0, 'gameLimit'=>999);
		foreach(self::$sanctions as $limit=>$sanction){
			if($integrity <= $limit){
				if(isset($sanction['tempBan']))
					$userSanction['tempBan'] = $sanction['tempBan'];
				if(isset($sanction['gameLimit']))
					$userSanction['gameLimit'] = $sanction['gameLimit'];
			} else {
				break; // no sanctions for current integrity left
			}
		}
		
		return $userSanction;
	}
	
	static public function gameLimits($User)
	{
		$gLp = $gLi = 999;
		
		if ($User->phaseCount < 100) {$gLp = 7;}
		if ($User->phaseCount < 50)  {$gLp = 4;}
		if ($User->phaseCount < 20)  {$gLp = 2;}
		
		$userSanction = self::getCurrentSanction($User);
		
		return min($gLp,$userSanction['gameLimit']);		
	}
	
	/**
	 * Return how many games a user can join.
	 * @return $maxgames as integer or 9999 as no restrictions...
	 */
	static public function maxGames($User)
	{
		global $DB;

		$gL = self::gameLimits($User);
		if ($gL > 100) return 999;
			
		// count all games substracting 2-player variants and private games
		$tabl = $DB->sql_tabl("SELECT variantID FROM wD_Members m, wD_Games g WHERE m.userID=".$User->id." and m.status!='Defeated' and m.gameID=g.id and g.phase!='Finished' and g.password IS NULL");
		
		require_once('lib/variant.php');
		$totalGames = 0;
		while( list($variantID) = $DB->tabl_row($tabl) )
		{
			$Variant = libVariant::loadFromVariantID($variantID);
			if( count($Variant->countries) != 2 ) $totalGames++;
		}
		
		$mG = $gL - $totalGames;
		if ($mG < 0) { $mG = 0; }
		
		return $mG;
	}
	
	/**
	 * Print a CD notive if the users ability to join games is limited.
	 */
	static public function printCDNotice($User)
	{
		if ( self::maxGames($User) < 50 )
			print '<p class="notice">Game-Restrictions in effect.</p>
				<p class="notice">You can join or create '.self::maxGames($User).' additional games.<br>
				Read more about this <a href="reliability.php">here</a>.<br><br></p>';
	}
	
	/**
	 * Check if the users reliability is high enough to join/create more games
	 * @return true or error message	 
	 */
	static public function isAtGameLimit($User)
	{
		global $DB;
		
		// A player can't join new games, as long as he has active CountrySwiches.
		list($openSwitches)=$DB->sql_row('SELECT COUNT(*) FROM wD_CountrySwitch WHERE (status = "Send" OR status = "Active") AND fromID='.$User->id);
		if ($openSwitches > 0)
			return "<p><b>NOTICE:</b></p><p>You can't join or create new games, as you have active CountrySwitches at the moment.</p>";

		$maxGames = self::maxGames($User);
		
		if ($maxGames == 0)
		{
			if ( self::gameLimits($User) == 2 && $User->phaseCount < 20) 
				return "<p>You're taking on too many games at once for a new member.<br>
					Please relax and enjoy the games that you are currently in before joining/creating a new one.<br>
					You need to play at least <strong>20 phases</strong>, before you can join more than 2 games.<br>
					2-player variants and private games are not affected by this restriction.</p>
					<p>Read more about this <a href='reliability.php'>here</a>.</p>";
			if ( self::gameLimits($User) == 4 && $User->phaseCount < 50) 
				return "<p>You're taking on too many games at once for a new member.<br>
					Please relax and enjoy the games that you are currently in before joining/creating a new one.<br>
					You need to play at least <strong>50 phases</strong>, before you can join more than 4 games.<br>
					2-player variants and private games  are not affected by this restriction.</p>
					<p>Read more about this <a href='reliability.php'>here</a>.</p>";
			if ( self::gameLimits($User) == 7 && $User->phaseCount < 100) 
				return "<p>You're taking on too many games at once for a new member.<br>
					Please relax and enjoy the games that you are currently in before joining/creating a new one.<br>
					You need to play at least <strong>100 phases</strong>, before you can join more than 7 games.<br>
					2-player variants and private games  are not affected by this restriction.</p>
					<p>Read more about this <a href='reliability.php'>here</a>.</p>";
					
			return "<p>You cannot join or create a new game, because you seem to be having trouble keeping up with the orders in the ones you already have.</p>
				<p>Read more about this <a href='reliability.php'>here</a>.</p>";
		}
		
		return false;
	}
	
	/**
	 * Check if a given user is prevented from joining a game due to game limits.
	 * 
	 * Users are prevented from joining new games if at game limit.
	 * 2-player variants and private games excluded.
	 */
	static public function userGameLimitRestriction($User, $Game){
		
		return !$Game->private && count($Game->Variant->countries)>2 && libReliability::isAtGameLimit($User);
				
	}

}

?>
