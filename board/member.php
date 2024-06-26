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

defined('IN_CODE') or die('This script can not be run by itself.');

/**
 * Handles the functions which a user wants to perform when they view a game they're a member of;
 * logging on, finalizing, etc. Also loads orders ready to be updated.
 *
 * @package Board
 */
class userMember extends panelMember
{
	/**
	 * Load a Member object into a userMember object, load the orders, and lock them all for UPDATE
	 *
	 * @param Member $Member The Member object containing variables to build a userMember from
	 */
	public function __construct(Member $Member)
	{
		global $DB, $Game;

		parent::__construct($Member);

		$commit=true;

		if ( $this->status == 'Left' )
		{
			$this->setBackFromLeft();
		}
		elseif( (time() - $this->timeLoggedIn) > 3*60)
		{
			$DB->sql_put("UPDATE wD_Members SET timeLoggedIn = ".time()." WHERE id = ".$this->id);
			$this->timeLoggedIn=time();
			$this->missedPhases=0;
		}
		else
			$commit=false;

		if( $commit )
			$DB->sql_put("COMMIT");
	}

	/**
	 * Set that this user is no longer in civil disorder for this membership. Sets as playing, removes
	 * civildisorder record, sets their orderStatus depending on whether they
	 * have orderes to enter, puts them into the correct Game->Members->ByStatus list.
	 */
	protected function setBackFromLeft()
	{
		global $DB,$Game,$User;
		
		if ( $this->Game->Members->isTempBanned() )
		{
			throw new Exception("You are blocked from rejoining your games.");
		}

		unset($this->Game->Members->ByStatus[$this->status][$this->id]);
		$this->status = 'Playing';
		$this->Game->Members->ByStatus[$this->status][$this->id] = $this;

		/*
		 * Remove the CD mark from this person's record
		 * Someone could possible go into CD, be taken over, join another country, go CD again, then rejoin, so country has to be specified
		 */
		 // Was this a mod forced CD?
		$DB->sql_tabl("SELECT * FROM wD_CivilDisorders
					WHERE forcedByMod=0 
					AND gameID = ".$this->gameID."
					AND userID = ".$this->userID."
					AND countryID = ".$this->countryID);
					
		/* On vDip we do not record CDs if a player retakes his position
		if ($DB->affected() != 0) {
            $DB->sql_put("UPDATE wD_Users SET deletedCDs = deletedCDs + 1 where id=" .$this->userID);
		}
		// End of vDip patch */
		
		// vDip-Patch: Make sure to not delete take-Over information of older CDs 
		// (only a non-taken-over Civil Disorder can be retaken)
		$DB->sql_put("DELETE FROM wD_CivilDisorders
					WHERE gameID = ".$this->gameID."
					AND userID = ".$this->userID."
					AND countryID = ".$this->countryID."
					AND takenByUserID IS NULL
				");
				
		$this->orderStatus->Ready=false;

		$DB->sql_put(
				"UPDATE wD_Members
				SET status = 'Playing', ".( $this->orderStatus->updated ? "orderStatus='".$this->orderStatus."', " : '' )."
					timeLoggedIn = ".time()."
				WHERE id = ".$this->id
			);

		// Reset the min bet so that the game no longer appears in open games searches. 
		require_once(l_r('gamemaster/game.php'));
		$Variant=libVariant::loadFromGameID($this->gameID);
		$Game = $Variant->processGame($this->gameID);
		$Game->resetMinimumBet();
	}

	/**
	 * Toggle the value of a member's vote, e.g. Pause or Draw. Sets/unsets it in the database and in $this->votes[]
	 * Also detects if a vote has passed and will schedule it for processing if so.
	 *
	 * Will usually be called from board.php, with the vote form buttons provided in the gamepanel
	 *
	 * @param string $voteName The vote which is being toggled
	 */
	public function toggleVote($voteName)
	{
		global $DB;
		
		if ($this->Game->adminLock == 'Yes') return;
		if (strpos($this->Game->blockVotes,$voteName)!== false) return;			
			
		$voteText = $voteName;
		
		// Unpause is stored as Pause in the database
		if ( $voteName == 'Unpause' )
			$voteName = 'Pause';

		if(!in_array($voteName, Members::$votes))
			throw new Exception(l_t("Invalid vote"));

		$voteOn = in_array($voteName, $this->votes);
		if($voteOn)
			unset($this->votes[array_search($voteName, $this->votes)]);
		else
		{
			$this->votes[] = $voteName;
			
			$count=0;
			foreach($this->Game->Members->ByStatus['Playing'] as $Member)
				if (in_array($voteName ,$Member->votes))
					$count++;
			if ($count == 1 && !($this->Game->drawType == 'draw-votes-hidden' && $voteText == 'Draw'))
			{
				require_once l_r('lib/gamemessage.php');
				$msg = $this->country.' voted for a '.$voteText.'. ';
				if ($voteText == 'Draw')
					$msg .= 'If everyone votes Draw the game will end and the points are split equally among all the surviving players, regardless of how many supply centers each player has.';
				if ($voteText == 'Pause')
					$msg .= 'If everyone votes Pause the game stop and wait till everybody votes Unpause. Please consider backing this.';
				if ($voteText == 'Unpause')
					$msg .= 'If everyone votes Unpause the game will continoue. Please consider backing this.';
				if ($voteText == 'Cancel')
					$msg .= 'If everyone votes Cancel all points will be refunded and the game will be deleted from the database.';
				if ($voteText == 'Extend')
					$msg .= 'If 2/3 of the active players vote Extend the the current phase will be extend by 4 days. Please consider backing this. If the majority is not reached by "'.$this->Game->Variant->turnAsDate($this->Game->turn + 2).'" the votes will be cleared.';
				if ($voteText == 'Concede')
					$msg .= 'If everyone (but one) votes concede the game will end and the player _not_ voting Concede will get all the points. Everybody else will get a defeat.';			
				libGameMessage::send(0, 'GameMaster', $msg , $this->Game->id);
			}
		}

		// Keep a log that a vote was set in the game messages, so the vote time is recorded
		require_once(l_r('lib/gamemessage.php'));
		libGameMessage::send($this->countryID, $this->countryID, ($voteOn?'Un-':'').'Voted for '.$voteName, $this->gameID);
		$DB->sql_put("UPDATE wD_Members SET votes='".implode(',',$this->votes)."' WHERE id=".$this->id);
	}

	/**
	 * Register that you have viewed the messages from a certain countryID and
	 * no longer need notification of them
	 *
	 * @param string $seenCountryID The countryID who's messages were read
	 */
	public function seen($seenCountryID)
	{
		global $DB;

		foreach($this->newMessagesFrom as $i => $countryID)
			if ( $countryID == $seenCountryID )
			{
				unset($this->newMessagesFrom[$i]);
				break;
			}

		$DB->sql_put("UPDATE wD_Members
						SET newMessagesFrom = '".implode(',',$this->newMessagesFrom)."'
						WHERE id = ".$this->id);
	}
}
?>
