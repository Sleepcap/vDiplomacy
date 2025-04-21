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

require_once(l_r('gamemaster/member.php'));

/**
 * A class to handle manipulating a game's members, sending messages out to them,
 * allocating them points, letting them join, getting stats for them, etc.
 *
 * @package GameMaster
 */
class processMembers extends Members
{
	/**
	 * Load a processMember, overrides Members->loadMember which loads a Member
	 *
	 * @param array $row The database record array hash for this member
	 * @return processMember
	 */
	protected function loadMember(array $row)
	{
		return $this->Game->Variant->processMember($row);
	}

	/**
	 * Send message about the game moving forward
	 */
	function notifyGameProgressed()
	{
		$this->sendToPlaying('No',l_t("Game progressed to %s, %s",$this->Game->phase,$this->Game->datetxt($this->Game->turn)));
	}

	/**
	 * Send message about the game being extended due to NMRs from certain members.
	 */
	function notifyGameExtended()
	{
		$this->sendToPlaying('No', l_t("Game phase extended due to missing orders by at least one country."));
	}

	/**
	 * Send message about the game being paused
	 */
	function notifyPaused()
	{
		$this->sendToPlaying('No',l_t("Game has been paused."));
	}

	/**
	 * Send message about the game being unpaused
	 */
	function notifyUnpaused()
	{
		$this->sendToPlaying('No',l_t("Game has been unpaused."));
	}

	/**
	 * Send message about the game phase being extended
	 */
	function notifyExtended()
	{
		require_once "lib/gamemessage.php";
		$msg= "Per 2/3 majority vote the gamephase got extended by 4 days.\n(Voters: ";
		foreach($this->ByStatus['Playing'] as $Member)
			if (in_array('Extend',$Member->votes))
				$msg.= $Member->country . ' / ';
		$msg=rtrim($msg,' /') . ")"; 
		libGameMessage::send(0, 'GameMaster', $msg , $this->Game->id);		
		$this->sendToPlaying('No',"The gamephase got extended by 4 days.");
	}
	
	/**
	 * Clear all extend votes from each Member for the next phase
	 */
	function clearExtendVotes()
	{
		global $DB;
		list($clearTurn) = $DB->sql_row('
			SELECT turn + 2 FROM wD_GameMessages WHERE
				message LIKE "%voted for a Extend%" AND fromCountryID = 0 AND gameID = '.$this->Game->id.'
				ORDER BY turn DESC LIMIT 1');
		
		if ($clearTurn != $this->Game->turn) return;
			
		$extVoteSet=false;
		foreach($this->ByStatus['Playing'] as $Member)
		{
			if (in_array('Extend',$Member->votes))
			{
				$extVoteSet=true;
				unset($Member->votes[array_search('Extend', $Member->votes)]);
				$DB->sql_put("UPDATE wD_Members SET votes='".implode(',',$Member->votes)."' WHERE id=".$Member->id);	
			}
		}
		if ($extVoteSet)
		{
			require_once "lib/gamemessage.php";
			libGameMessage::send(0, 'GameMaster', 'Extend-request didn\'t reach 2/3 majority. All extend-votes cleared.' , $this->Game->id);
		}
	}
	
	/**
	 * Count the units and supply centers of the members in this game, and refresh the
	 * Member objects and update the member records.
	 */
	function countUnitsSCs()
	{
		global $DB;

		// Reset
		foreach($this->ByCountryID as $countryID=>$Member)
		{
			$Member->unitNo = 0;
			$Member->supplyCenterNo = 0;
		}

		// Get unit numbers
		$tabl = $DB->sql_tabl("SELECT COUNT(id), countryID FROM wD_Units
						WHERE gameID = ".$this->Game->id."
						GROUP BY countryID
						HAVING COUNT(id) > 0");
		while ( list($unitNo, $countryID) = $DB->tabl_row($tabl) )
		{
			$this->ByCountryID[$countryID]->unitNo = $unitNo;
		}

		// Get supply center numbers
		$tabl = $DB->sql_tabl("SELECT COUNT(ts.terrID), ts.countryID
						FROM wD_TerrStatus ts
						INNER JOIN wD_Territories t ON ( ts.terrID = t.id )
						WHERE t.supply='Yes' AND ( NOT ts.countryID = 0 )
							AND ts.gameID = ".$this->Game->id."
							AND t.mapID=".$this->Game->Variant->mapID."
						GROUP BY ts.countryID
						HAVING COUNT(ts.terrID) > 0");
		$supplyCenters = array();
		while ( list($countSupplyCenters, $countryID) = $DB->tabl_row($tabl) )
		{
			$this->ByCountryID[$countryID]->supplyCenterNo = $countSupplyCenters;
		}

		// Put into member records
		foreach($this->ByCountryID as $countryID=>$Member)
		{
			$DB->sql_put("UPDATE wD_Members SET
					supplyCenterNo = ".$Member->supplyCenterNo.",
					unitNo = ".$Member->unitNo."
				WHERE gameID = ".$this->Game->id." AND countryID = ".$countryID);
		}
	}

	/**
	 * Returns true if any member has a different number of supply centers than units. Used to
	 * detect whether a builds phase is needed.
	 *
	 * @return boolean
	 */
	function checkForUnitSCDifference()
	{
		foreach($this->ByID as $Member)
			if( $Member->supplyCenterNo != $Member->unitNo )
				return true;

		return false;
	}

	/**
	 * Set players who have missed too many phases to be Left (which doesn't mean they get their
	 * points, they can still rejoin.
	 *
	 * @return boolean True if one or more have just left, false if no-one has just left
	 */
	function findSetLeft()
	{
		$left=false;
		// Ignore Live games for the 2-player games.
		$ignore = ( (count($this->Game->Variant->countries) > 2) ? 1 : 0);

		// Eliminate players who've left
		foreach($this->ByStatus['Playing'] as $Member)
		{
			assert($Member->missedPhases >= 0 and $Member->missedPhases <= 2);

			if($Member->missedPhases == 2)
			{
				$left=true;
				$Member->setLeft($ignore);
			}
		}

		return $left;
	}

	/**
	 * Set players who have lost all their SCs and units to be defeated, which will also
	 * return their points if needed.
	 *
	 * @return boolean True if someone has just been set to defeated, false otherwise
	 */
	function findSetDefeated()
	{
		$defeated=false;

		// Eliminate defeated players
		foreach($this->ByID as $Member)
		{
			/*
			 * Players who have 'Left' can be set to 'Defeated', but 'Defeated' players
			 * cannot become 'Left'
			 */
			if( $Member->status != 'Left' &&  $Member->status != 'Playing' ) continue;

			// The player is defeated if they have no supply centers or units
			if( 0 == $Member->supplyCenterNo and 0 == $Member->unitNo )
			{
				$defeated=true;
				$Member->setDefeated($this->Game->Scoring->pointsForDefeat($Member));
			}
		}

		return $defeated;
	}

	/**
	 * Check to see if there's only one player left, or one player has the winning number of supply centers. If so
	 * the game is declared game-over and points are distributed
	 *
	 * @return processMember The winning Member, or false if no winner
	 */
	function checkForWinner()
	{
		global $DB;

		/*
		 * See if only one person is left over
		 * If more than one is left over see if any of them have the winning number of more supply centers
		 */

		/*
		 * Dealing with refunds for players under 100 points:
		 * Defeated players immidiately get all their points back
		 * Players that have left don't get their points back, since they may come back yet
		 * Once the game is over the players which have left need to be given their refund, if
		 * they need one
		 */

		$countPlaying = count($this->ByStatus['Playing']);

		// Is the game over? Is there only 1/0 players left?
		if ( $countPlaying < 2 )
		{
			/*
			 * Defeated players have already been reimbursed, but players that
			 * Left but were not defeated have not been, so they may be under 100
			 * points but not have been refunded.
			 */

			if ( $countPlaying == 1 )
			{
				/*
				 * The winner isn't set to a winner yet, this would cause difficulties
				 * while counting the active SCs and seeing who is still playing. All
				 * member status changes occur within Members->pointsDistributePot()
				 */
				foreach($this->ByStatus['Playing'] as $Member);
				return $Member;
			}
			elseif ( $countPlaying == 0 )
				$this->Game->setAbandoned(); // Throws exception
		}
		else
		{
			// If there is a diplpmacy-phase check for a retreating-phase and wait another round for the retreats to finish.
			if ($this->Game->phase == 'Diplomacy')
			{
				list($retreating) = $DB->sql_row("SELECT COUNT(retreatingUnitID) FROM wD_TerrStatus WHERE gameID=".$this->Game->id);
				if($retreating)
					return false;
			}

			// If more than one is left over see if any of them have supplyCenterTarget or more supply centers
			foreach($this->ByStatus['Playing'] as $Member)
			{
				if ( $this->Game->targetSCs > 0 )
				{
					if ( $this->Game->targetSCs <= $Member->supplyCenterNo )
					{
						return $this->check_for_Winner_that_works_with_same_SC_count();
					}
				}
				elseif ( $this->Game->Variant->supplyCenterTarget <= $Member->supplyCenterNo )
				{
					return $this->check_for_Winner_that_works_with_same_SC_count();
				}
				// The players which have lost go into 'Survived' mode when the other player is set to Won
			}
		}
		
		// Do an additional check if we reached maxTurns:
		if (($this->Game->turn == ($this->Game->maxTurns - 1)) && ($this->Game->maxTurns > 0))
			return $this->check_for_Winner_that_works_with_same_SC_count();
		
		return false;
	}

	/**
	 * Set members to drawn, giving points to those still around and supplements to those who had left.
	 */
	function setDrawn()
	{
		$this->prepareLog();
		assert(count($this->ByStatus['Playing']) > 0);

		// Calculate the points each player gets.
		// These are pre-calculated because if they aren't the pot has to be decreased, and active
		// supply-centers recalculated as each member gets their winnings. This was the final pot of the
		// game can be preserved in the game record
        $points = array();

		foreach($this->ByStatus['Playing'] as $Member)
			$points[$Member->countryID] = $this->Game->Scoring->pointsForDraw($Member);

		foreach($this->ByStatus['Left'] as $Member)
			$Member->setResigned( );

		foreach($this->ByStatus['Playing'] as $Member)
			$Member->setDrawn( $points[$Member->countryID] );
		$this->writeLog();
	}

	/**
	 * Set members to defeated, giving points to those still around and supplements to those who had left.
	 */
	function setConcede()
	{
		$this->prepareLog();
		assert(count($this->ByStatus['Playing']) > 0);

		foreach($this->ByStatus['Left'] as $Member)
			$Member->setResigned();

		foreach($this->ByStatus['Playing'] as $Member)
		{
			if (in_array('Concede',$Member->votes))
				$Member->setDefeated($this->Game->Scoring->pointsForDefeat($Member));
		}

		$this->writeLog();
	}

	/**
	 * Set members to cancelled due to not enough players, giving all their bets back
	 */
	function setNotEnoughPlayers()
	{
		$this->prepareLog();
		/*
		 * Not-enough-player games are completely removed like
		 * mid-game-abandoned games, except bets are given back
		 */

		foreach($this->ByID as $Member)
		{
			// All members must be playing, since we're pre-game
			$Member->setNotEnoughPlayers();
		}
		$this->writeLog();
	}

	/**
	 * Notify members still playing/left that the game has been abandoned, and give
	 * supplements where needed.
	 */
	function setAbandoned()
	{
		$this->prepareLog();
		/*
		 * Abandoned games are completely removed, but first reimbursements
		 * need to be given to users which left. (They haven't got their
		 * reimbursements yet because they may have rejoined the game up
		 * until now)
		 */

		foreach($this->ByID as $Member)
		{
			// Ignore defeated players
			if($Member->status!='Left'&&$Member->status!='Playing')
				continue;

			$Member->setAbandoned();
		}
		$this->writeLog();
	}

	/**
	 * Set members to cancelled giving their bets back
	 */
	function setCancelled()
	{
		$this->prepareLog();
		foreach($this->ByID as $Member)
		{
			$Member->setCancelled(  );

			//TODO: Technically this should look up CD players which were taken over and repay
			// their bet too, but such a scenario will be very rare
		}
		$this->writeLog();
	}

	private function pointsInfoLog()
	{
		$log=array('pot'=>$this->Game->pot);

		if( !is_array($this->ByCountryID) )
		foreach($this->ByID as $Member)
		{
			$mLog=$Member->pointsInfoLog();
			$mLog['countryID']=$Member->countryID;

			foreach($mLog as $name=>$value)
				$log[$Member->userID.'-'.$name]=$value;
		}

		return $log;
	}

	private $logBefore;
	private function prepareLog()
	{
		if( !isset(Config::$pointsLogFile) || !Config::$pointsLogFile )
			return;

		$this->logBefore = $this->pointsInfoLog();
	}

	private function writeLog()
	{
		if( !isset(Config::$pointsLogFile) || !Config::$pointsLogFile )
			return;

		assert(is_array($this->logBefore));

		$before=$this->logBefore;
		$after=$this->pointsInfoLog();
		$log=array('gameID'=>$this->Game->id);

		foreach($before as $name=>$value)
			$log[$name]=array('before'=>$value,'after'=>$after[$name]);

		if( !file_put_contents(Config::$pointsLogFile, libTime::text().":\n".print_r($log,true)."\n-----\n\n", FILE_APPEND) )
			trigger_error("Couldn't write points log to log file");
	}

	/**
	 * Set this member to have won. Calculates the share of the pot everyone gets and sets them all as
	 * Won, Survived, or Resigned, giving points accordingly.
	 *
	 * @param Member $Winner The processMember which won
	 */
	function setWon(Member $Winner)
	{
		$this->prepareLog();

		// Calculate the points each player gets.
		// These are pre-calculated because if they aren't the pot has to be decreased, and active
		// supply-centers recalculated as each member gets their winnings. This was the final pot of the
		// game can be preserved in the game record
        $points = array();

		foreach($this->ByStatus['Left'] as $Member)
			$points[$Member->countryID] = $this->Game->Scoring->pointsForSurvive($Member);
		foreach($this->ByStatus['Playing'] as $Member)
				$points[$Member->countryID] = $this->Game->Scoring->pointsForSurvive($Member);
        $points[$Winner->countryID] = $this->Game->Scoring->pointsForWin($Winner);

		foreach($points as $countryID=>$pointsWon)
		{
			$Member = $this->ByCountryID[$countryID];

			// Now the actual status is set 'Playing'->'Survived'/'Won', 'Left'->'Resigned'
			if($Member->id == $Winner->id)
				$Winner->setWon($pointsWon);
			elseif($Member->status == 'Playing')
				$Member->setSurvived($pointsWon);
			elseif($Member->status == 'Left')
				$Member->setResigned();
			else
				trigger_error(l_t("Invalid member status type for points distribution."));
		}

		$this->writeLog();

		// Members, messages, points all sent and finished
	}

	/**
	 * Returns true if more than 1/3rd of active players are NMR. (No Moves Recieved)
	 *
	 * @return boolean
	 */
	function isOverNMRLimit()
	{
		$countPlaying=0;
		$countNMR=0;

		foreach($this->ByStatus['Playing'] as $MemberPlaying)
		{
			$countPlaying++;

			if( !( $MemberPlaying->orderStatus->Saved || $MemberPlaying->None ) )
				$countNMR++;
		}

		if( ($countNMR/$countPlaying) >= (1/3) )
			return true;
		else
			return false;
	}


	/**
	 * Allow the user to join a game. The User must have enough points, the Game must be
	 * locked for UPDATE, if the game is private a password must be supplied, if there is
	 * a civil disorder player the member ID of the civil disorder member must be
	 * supplied, there mustn't be too many users in the game, the user mustn't join the
	 * same game twice, etc, etc.
	 *
	 * If successful the user is redirected to the game they just joined. If unsuccessful
	 * an exception is thrown.
	 *
	 * @param string[optional] $password The optional password supplied to enter the game
	 * @param string[optional] $countryID The countryID to be taken (filtered)
	 */
	function join($password="", $countryID=-1)
	{
		global $DB, $User;

		$countryID=(int)$countryID;

		// If we're not locked for UPDATE we can't keep things consistant
		assert($this->Game->lockMode == UPDATE);

		if ( $this->Game->private and md5($password) != $this->Game->password and $password != $this->Game->password )
			throw new Exception(l_t("The invite code you supplied is incorrect, please try again."));

		if ( !$this->Game->isJoinable() )
			throw new Exception(l_t("You cannot join this game."));

		if ( !($this->Game->minimumReliabilityRating <= $User->reliabilityRating) )
			throw new Exception(l_t("Your Reliability Rating of %s%% is not high enough to join this game, which is restricted to %s%% RR and above.",
				$User->reliabilityRating, $this->Game->minimumReliabilityRating));

		if ( $User->userIsTempBanned() )
			throw new Exception("You are blocked from joining new games.");

		// Check for additional requirements:
		require_once(l_r('lib/reliability.php'));		 
		if ( $this->Game->minPhases > $User->phaseCount)
			throw new Exception("You did not play enough phases to join this game. (Required:".$this->Game->minPhases." / You:".$User->phaseCount.")");

		// Handle RL-relations
		require_once ("lib/relations.php");			
		if ($message = libRelations::checkRelationsGame($User, $this->Game))
			throw new Exception($message);
		
		// Check for reliability-rating:		
 		require_once(l_r('lib/reliability.php'));		 		
 		if ( $this->Game->phase == 'Pre-game' && libReliability::userGameLimitRestriction($User, $this->Game))
			throw new Exception('You are blocked from joining new games due to game limits.');

		// Check if there is a block against a player
		list($muted) = $DB->sql_row("SELECT count(*) FROM wD_Members AS m
									LEFT JOIN wD_BlockUser AS f ON ( m.userID = f.userID )
									LEFT JOIN wD_BlockUser AS t ON ( m.userID = t.blockUserID )
								WHERE m.gameID = ".$this->Game->id." AND (f.blockUserID =".$User->id." OR t.userID =".$User->id.")");
		if ($muted > 0)
			throw new Exception("You can't join. A player in this game has you blocked or you blocked a player in this game");
				
		// We can join, the only question is how?

		if ( $this->Game->phase == 'Pre-game' )
		{
		
			// Check if there is a player with no countryID => Game wants random countrydistribution.
			foreach ($this->ByUserID as $MemberCheck)
				if ($MemberCheck->countryID == 0)
					$countryID = -1;
		
			// Creates the Member record, the member object, and records the bet
			if( $countryID!=-1 )
			{
				if (isset($this->ByCountryID[$countryID]))
					throw new Exception("You cannot join this game as ".$this->Game->Variant->countries[$countryID -1]." someone else was faster.");
				processMember::create($User->id, $this->Game->minimumBet,$countryID);
			}
			else
				processMember::create($User->id, $this->Game->minimumBet);

			$M = $this->ByUserID[$User->id];
			if ($this->Game->isMemberInfoHidden() )
				$this->sendExcept($M,'No', l_t('Someone has joined the game.'));
			else
				$this->sendExcept($M,'No',l_t('%s has joined the game.',$User->username));
			$M->send('No','No',l_t('You have joined! Good luck'));

			if( count($this->ByUserID) == count($this->Game->Variant->countries) )
			{
				// Ready to start
				$this->Game->resetMinimumBet();
			}
			
		}
		else
		{
			// Taking over from CD: Valid countryID to take over? Got enough points?
			if ( 0>=$countryID || count($this->Game->Variant->countries)<$countryID )
				throw new Exception(l_t("You haven't specified which countryID you want to take over."));

			$CD = $this->ByCountryID[$countryID];

			if ( $CD->status != 'Left' )
				throw new Exception(l_t('The player selected is not in civil disorder.'));

			$bet = $CD->pointsValueInTakeover();
			if ( $User->points < $bet )
				throw new Exception(l_t("You do not have enough points to take over that countryID."));

			$CD->setTakenOver(); // Refund its points if required, and send it a message
			
			// vDip: Record CD as taken over by new user
			$DB->sql_put("UPDATE wD_CivilDisorders
					SET takenByUserID = ".$User->id.", takenAtTime = ".time()."
					WHERE gameID = ".$CD->gameID."
						AND userID = ".$CD->userID."
						AND countryID = ".$CD->countryID."
			");

			// Start updating the member record and object
			list($orderCount) = $DB->sql_row("SELECT COUNT(id) FROM wD_Orders
					WHERE gameID = ".$CD->gameID."
						AND countryID = ".$CD->countryID);

			$DB->sql_put("UPDATE wD_Members
					SET userID = ".$User->id.", status='Playing', orderStatus=REPLACE(orderStatus,'Ready',''), orderStatusChanged=UNIX_TIMESTAMP(),
						timeLoggedIn = ".time()."
						, votes=''
						, excusedMissedTurns=".$this->Game->excusedMissedTurns
					." WHERE id = ".$CD->id);
			$DB->sql_put('DELETE FROM wD_WatchedGames WHERE userID='.$User->id. ' AND gameID='.$this->Game->id);

			unset($this->ByUserID[$CD->userID]);
			unset($this->ByStatus['Left'][$CD->id]);

			$playerLeftID=$CD->userID;
			$CD->userID = $User->id;
			$CD->status = 'Playing';
			$CD->orderStatus->Ready=false;
			$CD->points = $User->points;
			$CD->reliabilityRating = $User->reliabilityRating;
			
			$this->ByUserID[$CD->userID] = $CD;
			$this->ByStatus['Playing'][$CD->id] = $CD;

			$CD->makeBet($bet);
			$this->Game->resetMinimumBet();

			$CDCountryName=$this->Game->Variant->countries[$CD->countryID-1];

			if ( $this->Game->isMemberInfoHidden() )
//				$this->sendExcept($CD,'No',l_t('Someone has taken over %s.',$CDCountryName));
			{
				require_once "lib/gamemessage.php";
				$msg = 'Someone has taken over '.$CDCountryName.' replacing "<a href="profile.php?userID='.$playerLeftID.'">'.$CD->username.'</a>". Reconsider your alliances.';
				libGameMessage::send(0, 'GameMaster', $msg , $this->Game->id);
				$this->sendExcept($CD,'No','Someone has taken over '.$CDCountryName.'.');
			}
			else
			{
				require_once "lib/gamemessage.php";
				$msg = $User->username.' has taken over '.$CDCountryName.' replacing "<a href="profile.php?userID='.$playerLeftID.'">'.$CD->username.'</a>". Reconsider your alliances.';
				libGameMessage::send(0, 'GameMaster', $msg, $this->Game->id);
				$this->sendExcept($CD,'No',$User->username.' has taken over '.$CDCountryName.'.');
			}
			$CD->send('No','No','You took over '.$CDCountryName.'! Good luck');
		}

		// Recalculate CC and IP matches if a new player joins...
		$this->updateCCIP();
		
		$this->Game->gamelog(l_t('New member joined'));

		$this->joinedRedirect();
	}

	/**
	 * Redirect to a game after joining it. Script ends here.
	 */
	function joinedRedirect()
	{
		$page = $this->Game->usePointAndClickUI() ? "beta/" : "board.php";

		header('refresh: 3; url='.$page.'?gameID='.$this->Game->id);
		$message = '<p class="notice">'.l_t('You are being redirected to %s. Good luck!','<a href="'.$page.'?gameID='.$this->Game->id.'">'.$this->Game->name.'</a>').'</p>';

		libHTML::notice(l_t("Joined %s",$this->Game->name), $message);
	}

	/**
	 * Register a turn and updates the phase count for each active member (playing of left) with orders.
	 */
	function registerTurn()
	{
		global $DB;

		// Don't count games against bots
		if( $this->Game->playerTypes == 'MemberVsBots' ) return;

		// enter a turn for each active player with orders
		$DB->sql_put("INSERT INTO wD_TurnDate (gameID, userID, countryID, turn, turnDateTime, isInReliabilityPeriod)
				SELECT m.gameID,m.userID,m.countryID,".$this->Game->turn.",".time().", 1
				FROM wD_Members m
				WHERE m.gameID = ".$this->Game->id."
					AND ( m.status='Playing' OR m.status='Left' )
					AND EXISTS(SELECT o.id FROM wD_Orders o WHERE o.gameID = m.gameID AND o.countryID = m.countryID)");

		// increment the turn count (turn counts are decremented after 1 year in /gamemaster.php)
		// vdip: readded phaseCount increment (the old simple increment is sufficient and 
		//	does also avoid overwriting records of the past)
		$DB->sql_put("UPDATE wD_Users u
				INNER JOIN wD_Members m ON m.userID = u.id
				SET u.yearlyPhaseCount = u.yearlyPhaseCount + 1, u.isPhasesDirty = 1,
				u.phaseCount = u.phaseCount + 1
				WHERE m.gameID = ".$this->Game->id."
					AND ( m.status='Playing' OR m.status='Left' )
					AND EXISTS(SELECT o.id FROM wD_Orders o WHERE o.gameID = m.gameID AND o.countryID = m.countryID)");
	}

	/**
	 * Add a missed phase / turn to all members who are NMRing. Reset those of
	 * members who have not NMRed.
	 *
	 * @param array $nmrs A list of member ids including the NMRs of the current turn
	 */
	function registerNMRs($nmrs)
	{
		global $DB;

		foreach( $this->ByID as $Member )
		{
			if( in_array($Member->id, $nmrs) )
			{
				$Member->missedPhases++;
			}
			else
			{
				$Member->missedPhases = 0;
			}

			$DB->sql_put("UPDATE wD_Members m
					SET m.missedPhases = ".$Member->missedPhases."
					WHERE m.id = ".$Member->id);
		}
	}

	private $activeNMRs = false;
	/**
	 * Check if any active NMRs (i.e. NMRs by members with status 'playing')
	 * were detected during NMR handling.
	 *
	 * @return boolean Returns true, if there is at least one NMR by an active member.
	 */
	function withActiveNMRs()
	{
		return $this->activeNMRs;
	}

	/**
	 * Handle NMRs and check, if further sanctions due to unexcused NMRs have
	 * to be imposed.
	 * 
	 * vDip: Do also check if members can earn back an excuse.
	 */
	function handleNMRs()
	{
		global $DB;

		// Bot games don't factor into NMR calcs
		if( $this->Game->playerTypes == "MemberVsBots" ) return;

		// Check if there is at least one active NMR and for that case reduce the excuses of all active members with NMRs and set members with no excuses as left.
		$this->activeNMRs = false;
		$needReset = 0;

		foreach( $this->ByStatus['Playing'] as $Member )
		{
 			if( $Member->missedPhases == 0 ) { 
				$Member->checkExcuseEarnBack();
				continue; // no NMR
			} 

			$this->activeNMRs = true; // there is at least one active NMR

			if( $Member->excusedMissedTurns > 0 ) { $Member->removeExcuse(); }
			else
			{
				$Member->setLeft();
				$needReset = 1;
			}
		}

		// If anyone is removed from the game the minimum bet needs to be reset so someone else can take over the position.
		if ($needReset == 1)
		{
			$Variant=libVariant::loadFromGameID($this->Game->id);
			$Game = $Variant->processGame($this->Game->id);
			$Game->resetMinimumBet();
		}

		/*
		 * For all player with status left, that NMRed this turn, the NMR is always counted as unexcused. An unexcused turn might impose temp bans as further sanctions.
		 */
		foreach( $this->ByStatus['Left'] as $Member )
		{
			if( $Member->missedPhases == 0 ) { continue; } // no NMR

			// Check if the NMR got an excuse and count the number of unexcused NMRs during the last year for the member to decide what to do.
			list( $systemExcused, $modExcused, $samePeriodExcused ) = $DB->sql_row("SELECT systemExcused, modExcused, samePeriodExcused FROM wD_MissedTurns
				WHERE gameID = ".$this->Game->id." AND userID = ".$Member->userID." ORDER BY turnDateTime DESC LIMIT 1");

			list( $yearlyCount ) = $DB->sql_row("SELECT COUNT(1) FROM wD_MissedTurns
				WHERE userID = ".$Member->userID." AND turnDateTime > ".time()." - (3600 * 24 * 365)
				AND liveGame = 0 AND systemExcused = 0 AND modExcused = 0 AND samePeriodExcused = 0");

			list( $liveMonthlyCount ) = $DB->sql_row("SELECT COUNT(1) FROM wD_MissedTurns
				WHERE userID = ".$Member->userID." AND turnDateTime > ".time()." - (3600 * 24 * 28)
				AND liveGame = 1 AND systemExcused = 0 AND modExcused = 0 AND samePeriodExcused = 0");

			if( $systemExcused || $modExcused ) continue; // excused miss (though a left member should not be able to get an excused miss unless mod interaction)

			$memberMsg = l_t("You have missed a deadline and have no excuses left.");

			list( $phaseMinutes ) = $DB->sql_row("SELECT phaseMinutes FROM wD_Games WHERE id = ".$this->Game->id);

			// Check, if there was at last one other unexcused NMR during the last 72 hours. In this case, this NMR will not be sanctionized.
			if( $samePeriodExcused )
			{
				$Member->send('No','No',$memberMsg." ".l_t("Due to other unexcused missed deadlines in the past 72 hours, this will only affect your Reliability Rating."));
			}

			else if ($phaseMinutes < 61)
			{
				/*
				 * This NMR might be sanctionized according to the monthly missed turn count for live games
				 *
				 * misses:
				 *
				 * 0-2: warning
				 * 3+: 1-day temp ban
				 */
				$memberMsg.=" ".l_t("You missed %s ".(($liveMonthlyCount == 1)?"deadline":"deadlines"). " without an excuse during live games this past month.",$liveMonthlyCount);

				if( $liveMonthlyCount <= 2 )
				{
					$Member->send('No','No',$memberMsg." ".l_t("%s more ". ((3-$liveMonthlyCount == 1)?"miss":"misses"). " will impose a 1 day ban on you.", 3-$liveMonthlyCount));
				}

				else
				{
					User::tempBanUser($Member->userID, 1, 'System', FALSE);
					$Member->send('No','No',$memberMsg." ".l_t("Due to your unreliable behavior in live games you will be prevented from joining games for a day."));
				}
			}

			else
			{
				/*
				 * This NMR might be sanctionized according to the yearly missed
				 * turn count and the following table:
				 *
				 * misses:
				 *
				 * up to 3: warning
				 * 4: 1-day temp ban
				 * 5: 3-day
				 * 6: 7-day
				 * 7: 14-day
				 * 8: 30-days
				 * 9 or more: infinite (1 year)
				 * 
				 * vDip:
				 * Sanctions defined in lib/reliability.php
				 */
				$memberMsg.=" ".l_t("You missed %s ".(($yearlyCount == 1)?"deadline":"deadlines"). " without an excuse during this year.",$yearlyCount);
				
				$User = new User($Member->userID);
				$integrity = $User->getIntegrityRating();
				require_once('lib/reliability.php');
				
				if( $integrity-libReliability::$maxRatingForSanction > 0)
				{
					$Member->send('No','No',$memberMsg." ".l_t("%s more ". (($integrity-libReliability::$maxRatingForSanction == 1)?"miss":"misses"). " will impose a temporary ban and game limit on you.", $integrity-libReliability::$maxRatingForSanction));
				} 
				else 
				{
					// get sanction
					$sanction = libReliability::getCurrentSanction($User);
					
					$days = $sanction['tempBan'];
					$gLi = $sanction['gameLimit'];
							
					if($days > 0)
					{
						User::tempBanUser($Member->userID, $days,'System', FALSE);
						$memberMsg .= " ".l_t("You are temporarily banned from joining, rejoining, or making games for %s "
							. (($days==1)?"day":"days").".", $days);
					}
					if($gLi < 50)
					{
						$memberMsg .= " ".l_t("You received a game limit of up to %s ".(($gLi==1)?"game":"games").".", $gLi);
					}
					$memberMsg .= " ".l_t("Be more reliable!");
					
					$Member->send('No','No',$memberMsg);	
				}

//				elseif( $yearlyCount >= 9)
//				{
//					User::tempBanUser($Member->userID, 365, 'System', FALSE);
//					$Member->send('No','No',$memberMsg." ".l_t("Due to your unreliable behavior you will be prevented from joining games for a year. "
//					. "Contact the Mods to lift the ban."));
//				} 
//
//				else 
//				{
//					$days = 0;
//					switch($yearlyCount)
//					{
//						case 4: $days = 1; break;
//						case 5: $days = 3; break;
//						case 6: $days = 7; break;
//						case 7: $days = 14; break;
//						case 8: $days = 30; break;
//					}
//					
//					User::tempBanUser($Member->userID, $days,'System', FALSE);
//					$Member->send('No','No',$memberMsg." ".l_t("You are temporarily banned from joining, rejoining, or making games for %s "
//							. (($days==1)?"day":"days")	. ". Be more reliable!", $days));	
//				}
					
			}
		}
	}

	function processSummary()
	{
		$a=array(
			'ready'=>($this->isReady()?'true':'false'),
			'members'=>array()
		);

		foreach($this->ByID as $Member)
			$a['members'][] = $Member->processStatus();

		return $a;
	}
	
	/**
	 * Check the previous turns if more than one players reach the target SCs at the same turn.
	 *
	 * @return processMember The winning Member.
	 */
	function check_for_Winner_that_works_with_same_SC_count()
	{
		$winners=array();
		$maxSC=0;
		foreach($this->ByStatus['Playing'] as $Member)
		{
			if ( $Member->supplyCenterNo > $maxSC )
			{
				$maxSC=$Member->supplyCenterNo;
				$winners=array();
			}	
			if ( (count($winners)==0) or ($Member->supplyCenterNo == $maxSC) )
				$winners[]=$Member->countryID;
		}
		if (count($winners) > 1)
		{
			global $DB;
			for ($turn=$this->Game->turn; $turn>-1; $turn--)
			{
				$sql='SELECT ts.countryID, COUNT(*) AS ct FROM wD_TerrStatusArchive ts 
						JOIN wD_Territories as t ON (t.id = ts.terrID AND t.mapID='.$this->Game->Variant->mapID.')
					WHERE t.supply="Yes" AND ts.turn='.$turn.' AND ts.gameID='.$this->Game->id.'
						AND ts.countryID IN ('.implode(', ', $winners).')
					GROUP BY ts.countryID 
					HAVING ct = (
						SELECT COUNT(*) AS ct2 FROM wD_TerrStatusArchive ts2
							JOIN wD_Territories as t2 ON (t2.id = ts2.terrID AND t2.mapID='.$this->Game->Variant->mapID.')
						WHERE t2.supply="Yes" AND ts2.turn='.$turn.' AND ts2.gameID='.$this->Game->id.'
							AND ts2.countryID IN ('.implode(', ', $winners).')
						GROUP BY ts2.countryID ORDER BY ct2 DESC LIMIT 1)';
				$tabl = $DB->sql_tabl($sql);
				$winners=array();
				while( list($countryID, $sc) = $DB->tabl_row($tabl) )
					$winners[]=$countryID;
				// Exit loop if only one winner is left...
				if (count($winners) == 1)
					$turn=0;
			}
		}
		// Still no winner found:
		if (count($winners) > 1)
			$winners[0]=$winners[rand(0,count($winners)-1)];
			
		return $this->ByCountryID[$winners[0]];		
	}
}
?>
