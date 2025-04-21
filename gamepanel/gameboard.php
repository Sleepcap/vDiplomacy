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

require_once(l_r('gamepanel/game.php'));
require_once(l_r('objects/group.php'));

/**
 * This class displays the game panel within a board context. It displays more info
 * and gives different functionality (e.g. voting)
 *
 * @package GamePanel
 */
class panelGameBoard extends panelGame
{
	function mapHTML() 
	{
		global $User;

		$mapTurn = (($this->phase=='Pre-game'||$this->phase=='Diplomacy') ? $this->turn-1 : $this->turn);
		$smallmapLink = 'map.php?gameID='.$this->id.'&turn='.$mapTurn .($User->getOptions()->value['showMoves'] == 'No'? '&hideMoves':'');
		$betaLink = 'beta?gameID='.$this->id;
		$largemapLink = $smallmapLink.'&mapType=large'.($User->getOptions()->value['showMoves']=='No'?'&hideMoves':'');

		if( $this->Variant->mapID != 1 )
			$betaLink = $largemapLink;
		
		$staticFilename = Game::mapFilename($this->id, $mapTurn, 'small');

		if( file_exists($staticFilename) && $User->getOptions()->value['showMoves'] == 'Yes' )
			$smallmapLink = STATICSRV.$staticFilename.'?nocache='.rand(0,99999);

		if ($User->colorCorrect != 'Off')
		{
			$staticFilename = str_replace(".map","-".$User->colorCorrect.".map",$staticFilename);
			$smallmapLink .= '&colorCorrect='.$User->colorCorrect;
			$largemapLink .= '&colorCorrect='.$User->colorCorrect;
		}

		if ($User->showCountryNamesMap == 'Yes')
		{
			$staticFilename = str_replace(".map","-names.map",$staticFilename);
			$smallmapLink .= '&countryNames';
			$largemapLink .= '&countryNames';
		}
		

/*		$map = '
		<div id="mapstore">
			<img id="mapImage" src="'.$smallmapLink.'" alt=" " title="'.l_t('The small map for the current phase. If you are starting a new turn this will show the last turn\'s orders').'" style="width: auto; max-width: 100%;" onclick="window.open(\''.$betaLink.'\');" />
			<p class="lightgrey" style="text-align:center">
				<a class="mapnav" href="#" onClick="loadMap('.$this->id.','.$mapTurn.',-1); return false;">
					<img id="Start" src="'.l_s('images/historyicons/Start_disabled.png').'" alt="'.l_t('Start').'" title="'.l_t('View the map from the first turn').'" />
				</a>
				<a class="mapnav" href="#" onClick="loadMapStep('.$this->id.','.$mapTurn.',-1); return false;">
					<img id="Backward" src="'.l_s('images/historyicons/Backward_disabled.png').'" alt="'.l_t('Backward').'" title="'.l_t('View the map from the previous turn').'" />
				</a>
				<a class="mapnav" href="#" onClick="toggleMoves('.$this->id.','.$mapTurn.'); return false;">
					<img id="NoMoves" src="images/historyicons/'.($User->options->value['showMoves'] == 'No'? 'show':'hide').'moves.png" alt="NoMoves" title="Toggle movement lines" />
				</a>
				<a id="LargeMapLink" class="mapnav" href="'.$largemapLink.'" target="_blank" class="light">
					<img src="'.l_s('images/historyicons/external.png').'" alt="'.l_t('Open large map').'" title="'.l_t('This button will open the large map in a new window. The large map shows all the moves, and is useful when the small map isn\'t clear enough.').'" /></a></span>
				<a class="mapnav" href="#" onClick="loadMapStep('.$this->id.','.$mapTurn.',1); return false;">
				<img id="Forward" src="'.l_s('images/historyicons/Forward_disabled.png').'" alt="'.l_t('Forward').'" title="'.l_t('View the map from the next turn').'" />
				</a>
				<a class="mapnav" href="#" onClick="loadMap('.$this->id.','.$mapTurn.','.$mapTurn.'); return false;">
				<img id="End" src="'.l_s('images/historyicons/End_disabled.png').'" alt="'.l_t('End').'" title="'.l_t('View the map from the most recent turn').'" />
				</a>'.
				($this->Members->isJoined() ? '<a class="mapnav" href="#" onClick="togglePreview('.$this->id.','.$mapTurn.'); return false;"><img id="Preview" src="images/historyicons/Preview.png" alt="PreviewMoves" title="Show server side stored orders on the map" /></a>' : '').'
			</p>
			<p id="History" class="lightgrey"></p>
		</div>';
		
/*
			<div class="sitesection">
				<section>
					<div class="boxhandle" title="hide/show section"></div><h2 class="boxtitle">Show Map</h2>
				</section>
			</div>
*/

		$map = '
			<div id="mapstore" class="boxdetail center">
				
				<div class="map">
					<img id="mapImage" src="'.$smallmapLink.'" alt=" " title="'.l_t('The small map for the current phase. If you are starting a new turn this will show the last turn\'s orders').'" />
				</div>
				<div class="maptools" style="display: block;">
					<div class="maphistory">
						<div class="button" onClick="loadMap('.$this->id.','.$mapTurn.',-1); return false;">
							<img id="Start" src="'.l_s('images/historyicons/Start_disabled.png').'" alt="'.l_t('Start').'" title="'.l_t('View the map from the first turn').'" />
						</div>
						<div class="button" onClick="loadMapStep('.$this->id.','.$mapTurn.',-1); return false;">
							<img id="Backward" src="'.l_s('images/historyicons/Backward_disabled.png').'" alt="'.l_t('Backward').'" title="'.l_t('View the map from the previous turn').'" />
						</div>
						<div class="button" onClick="loadMapStep('.$this->id.','.$mapTurn.',1); return false;">
							<img id="Forward" src="'.l_s('images/historyicons/Forward_disabled.png').'" alt="'.l_t('Forward').'" title="'.l_t('View the map from the next turn').'" />
						</div>
						<div class="button" onClick="loadMap('.$this->id.','.$mapTurn.','.$mapTurn.'); return false;">
							<img id="End" src="'.l_s('images/historyicons/End_disabled.png').'" alt="'.l_t('End').'" title="'.l_t('View the map from the most recent turn').'" />
						</div>
					</div>
					<div class="button" onClick="toggleMoves('.$this->id.','.$mapTurn.'); return false;">
						<img id="NoMoves" src="images/historyicons/'.($User->options->value['showMoves'] == 'No'? 'show':'hide').'moves.png" alt="NoMoves" title="Toggle movement lines" /> Toggle moves
					</div>
					'.($this->Members->isJoined() ? '
					<div class="button" href="#" onClick="togglePreview('.$this->id.','.$mapTurn.'); return false;">
						<img id="Preview" src="images/historyicons/Preview.png" alt="PreviewMoves" title="Show server side stored orders on the map" /> Preview
					</div>' : '').'
					
					<a id="LargeMapLink" href="'.$largemapLink.'" target="_blank" class="light"> <div class="button">
						<img src="images/historyicons/bigmap.png"> Big map
					</div> </a>
				</div>
				<div id="History" class="lightgrey"></div>
			</div>			
		';

		if ($User->phaseCount < 30 && $this->phase != 'Pre-game')
			$map .= '<p style="text-align:center">Tip: Failed orders are usually only displayed on the largemap (<a href="'.$largemapLink.'" class="light"><img src="'.l_s('images/historyicons/external.png').'"></a>).</p>';
		
		if ($User->colorCorrect != 'Off')
			$map .= '<script type="text/javascript">var colorCorrect="&colorCorrect='.$User->colorCorrect.'";</script>';

		if ($User->showCountryNamesMap != 'No')
			$map .= '<script type="text/javascript">var showCountryNamesMap=true;</script>';
			
		$this->mapJS($mapTurn);

		return $map;
	}

	protected function mapJS($mapTurn)
	{

		libHTML::$footerScript[] = 'turnToText='.$this->Variant->turnAsDateJS()."
		mapArrows($mapTurn,$mapTurn);
		";
		libHTML::$footerIncludes[] = l_j('mapUI.js');
	}

	function links()
	{
		$buf = '';

		if ( $this->phase != 'Pre-game')
		{
			$buf .= '<div class="bar archiveBar"> '.
				$this->archiveBar().
				$this->sandboxBar().
				$this->pointAndClickBar().
				'</div> ';
		}

		$buf .= parent::links();

		return $buf;
	}

	function pausedInfo()
	{
		$buf = parent::pausedInfo();

		if( is_null($this->pauseTimeRemaining) )
			$remaining = $this->getCurPhaseMinutes()*60;
		else
			$remaining = $this->pauseTimeRemaining;

		return $buf.' ('.l_t('%s left on unpause',libTime::timeLengthText($remaining)).')';
	}

	/**
	 * The main board-only functionality; the votes form for members to vote with.
	 * Finds allowed votes, takes what the member has voted for, and the votes which
	 * are passed, and gives the list of votes which can be voted/cancelled in a
	 * form, which board.php processes.
	 * @return string
	 */
/*	function votes()
	{
		global $User;
		if ( ( $this->phase == 'Pre-game' || $this->phase == 'Finished' ) || !isset($this->Members->ByUserID[$User->id]) ) return '';

		if ($this->adminLock == 'Yes')
			return '';

		$vAllowed = Members::$votes;
		$vSet = $this->Members->ByUserID[$User->id]->votes;

		$vCancel=array();
		$vVote=array();

		foreach($vAllowed as $vote)
		{
			// Set when the option to vote concede is allowed. Restrict it to games set via the config. 
			if ($vote == 'Concede')
			{
				if ( (empty(Config::$concedeVariants)) || (in_array($this->variantID, Config::$concedeVariants)) )
				{
					if(in_array($vote, $vSet))
						$vCancel[]=$vote;
					else
						$vVote[]=$vote;
				}
			}
			else
			{
				if(in_array($vote, $vSet))
					$vCancel[]=$vote;
				else 
					$vVote[]=$vote;
			}			
		}

		// archiveBar class to make the text visible in dark mode
		$buf = '<div style="margin: 0 auto; text-align:center; padding-top:5px; padding-bottom:5px;">
			<a href="modforum.php?fromGameID='.$this->id.'">Need help?</a> - <a href="board.php?gameID='.$this->id.'&lodgeSuspicion=on">Lodge cheating suspicion</a></div>';
		
		$buf .= '<div class="bar membersList memberVotePanel"><a name="votebar"></a>
		<table><tr class="member">
			<td class="memberLeftSide">
				<strong>'.l_t('Votes:').'</strong>
			</td>
			<td class="memberRightSide">
				'.$this->showVoteForm($vVote, $vCancel).'
			</td>
			</tr>
		</table>';

		// vDip vote-Buttons do not need a table:
		$buf = '<div class="bar membersList memberVotePanel"><a name="votebar"></a>'.$this->showVoteForm($vVote, $vCancel);
			
		return $buf . '</div>';
	}

	/**
	 * The form that lets users lodge suspicions of other players. TODO: Move to group.php
	 */
	function lodgeSuspicionForm()
	{
		global $User;

		if( !$this->Members->isJoined() ) return "";

		$buf = '<div class="bar memberVotePanel memberSuspectPanel" style="font-size:90%; font-weight:normal !important; text-align:left">
			<form action="group.php" method="post">
			'.libAuth::formTokenHTML().'
			<input type="hidden" name="gameID" value="'.$this->id.'" /><br />
			<input type="hidden" name="gameID" value="'.$this->id.'" /><br />
			<strong>Countries:</strong> <em>Please select the countries / users which you believe are metagaming / multi-accounting.</em><br />
			<div style="text-align:center">';
		foreach($this->Members->ByCountryID as $countryID=>$Member)
		{
			if( $Member->userID == $User->id ) continue;

			if ($this->anon == 'No' || !$Member->isNameHidden() )
				$buf .= '<nobr><input type="checkbox" name="countryIsSuspected'.$countryID.'" /> ' . $Member->profile_link() . ', </nobr>';
			else
				$buf .= '<nobr><input type="checkbox" name="countryIsSuspected'.$countryID.'" /> ' . $Member->memberNameCountry() . ', </nobr>';
		}
		$buf .= '</div>
			<br />
			<strong>Explanation:</strong> <em>Below please enter a detailed explanation of why you believe the selected countries are meta/multi gaming.</em><br />
			<textarea name="explanation" rows=5></textarea><br /><br />
			<strong>Strength:</strong> '.Group::getSelectWeighting('user', '', 50).' <em>Choose from WEAK to STRONG, to select how strongly you suspect these users. This will determine whether mods urgently investigate or just take note of a possible link for future investigations.</em><br />
		';
		$buf .= '<br /><strong>Note:</strong> Strong/mid-strength accusations will be followed up by the mod team, and will be discussed all involved. Do not submit without a genuine suspicion of meta/multi-gaming.<br />Other accusation strengths will be looked into as time permits, and combined with other accusations to detect possible links. Thanks for helping to keep the server fun to play on!<br /><br />
			<input class="form-submit" type="Submit" name="Submit" value="Submit cheating suspicion" /> ';
		$buf .= '</form>';
		require_once('objects/group.php');
		require_once('objects/groupUser.php');
		
		$buf .= '<div>';
		$buf .= GroupUserToUserLinks::loadFromGame($this)->outputTable();
		$buf .= '</div>';

		$groupUsers = Group::getUsers("gr.isActive = 1 AND gr.gameID = ".$this->id);
		$buf .= Group::outputUserTable_static($groupUsers, null, null);
		$buf .= '</div></div>';

		return $buf;
	}

	/**
	 * Returns the actual form, given the votes which can be voted for, and votes which can
	 * be cancelled.
	 *
	 * @param array $vVote Allowed votes
	 * @param array $vCancel Votes which can be cancelled
	 * @return string
	 */
/*
	function showVoteForm($vVote, $vCancel)
	{
		$buf = '<form onsubmit="return confirm(\''. l_t("Are you sure you want to cast this vote?").'\');" action="board.php?gameID='.$this->id.'#votebar" method="post">';
		$buf .= '<input type="hidden" name="formTicket" value="'.libHTML::formTicket().'" />';

        $buf .= '<div class="memberUserDetail">';

		foreach($vVote as $vote)
		{
			if (strpos($this->blockVotes,$vote)!== false) continue;			
			
			if ( $vote == 'Pause' && $this->processStatus == 'Paused' )
				$vote = 'Unpause';

			$buf .= '<input type="submit" class="form-submit" name="'.$vote.'" value="'.l_t($vote).'" /> ';
		}
		$buf .= '</div></form>';
		$buf .= '<form onsubmit="return confirm(\''. l_t("Are you sure you want to withdraw this vote?").'\');" action="board.php?gameID='.$this->id.'#votebar" method="post">';
		$buf .= '<input type="hidden" name="formTicket" value="'.libHTML::formTicket().'" />';

		if( $vCancel )
		{
			$buf .= '<div class="memberGameDetail">'.l_t('Cancel:').' ';
			foreach($vCancel as $vote)
			{
				if ( $vote == 'Pause' && $this->processStatus == 'Paused' )
					$vote = 'Unpause';

				$buf .= '<input type="submit" class="form-submit" name="'.$vote.'" value="'.l_t($vote).'" /> ';
			}

			$buf .= '</div>';
		}
		
		$buf .= '</form>';
		
		$buf .= '<img id = "modBtnVote" height="16" width="16" src="images/icons/help.png" alt="Help" title="Help" style="padding: 8px;"/>
		<div id="voteModal" class="modal">
			<div class="modal-content">
				<span class="close1">&times;</span>
				<p><strong>Draw Vote: </strong></br>
					If all players vote draw, the game will be drawn. ';
		switch ($this->potType) 
		{
			case 'Points-per-supply-center':
					$buf .= 'This game is scored using points per supply center. In a draw, points are split evenly among all players remaining.';
					break;
			case 'Winner-takes-all':
					$buf .= 'This game is scored using draw size scoring. In a draw, points are split evenly among all players remaining.';
					break;             
			case 'Unranked':
					$buf .= 'This game is unranked. In a draw, all points are returned to their previous owners.';
					break;             
			case 'Sum-of-squares':
					$buf .= 'This game is scored using sum of squares. In a draw, points are split among remaining players based upon how many supply centers they have.';
					break;             
			default:
					trigger_error("Unknown pot type '".$this->potType."'");
					break;
		}
		switch ($this->drawType) 
		{
			case 'draw-votes-public':
				$buf .= ' Draw votes are publicly displayed in this game.';
				break;
			case 'draw-votes-hidden':
				$buf .= ' Draw votes are not publicly known in this game.';
				break;
			default:
				trigger_error("Unknown draw type '".$this->drawType."'");
				break;
		}
		$buf.= '</p>';
		
		if( $this->processStatus == 'Paused' )
		{
			$buf .= '<p><strong>Unpause Vote: </strong></br>
						If all players vote unpause, the game will be unpaused. If a game is stuck paused, message the mods via the <a href="modforum.php">Mod forum</a>.
					</p>';
		}
		else
		{
			$buf .= '<p><strong>Pause Vote: </strong></br>
						If all players vote pause, the game will be paused until all players vote unpause. If you need a game paused'. ($this->pressType == 'NoPress' ? '' : ' due to an emergency').', click on the Need Help? link just above this icon to contact the mods.
					</p>';
		}
		
		$buf .= '<p><strong>Cancel Vote: </strong></br>
					If all players vote cancel, the game will be cancelled. All points will be refunded, and the game will be deleted. Cancels are typically used in the first year or two of a game with missing players.
				</p>';

		if ($this->playerTypes == 'MemberVsBots')
		{
			$buf .= '<p><strong>Bot Voting: </strong></br>
				A vote to Pause or Cancel will immediately Pause or Cancel the game.
			</p>';
		}
		else if ($this->playerTypes == 'Mixed')
		{
			$buf .= '<p><strong>Bot Voting: </strong></br>
				The bots in this game do not get a pause or unpause vote, pausing and unpausing only counts human votes. <br><br>
				If a bot is winning a game and has gained supply centers in the last 4 turns, it will stop the game from being drawn or cancelled. Otherwise bot games can be drawn or cancelled anytime.  
			</p>';
		}
		
		$buf .='
			</div>
		</div>';
		
		$buf .= '<script>
		var modal1 = document.getElementById("voteModal");
		var btn1 = document.getElementById("modBtnVote");
		var span1 = document.getElementsByClassName("close1")[0];
		btn1.onclick = function() { modal1.style.display = "block"; }
		span1.onclick = function() { modal1.style.display = "none"; }
		window.onclick = function(event) {
		  if (event.target == modal1) { modal1.style.display = "none"; }
		}
		</script>';

    $buf .= '<div style="clear:both"></div>';

		return $buf;
	}
*/	
	function votes()
	{
		global $User;
		if ( ( $this->phase == 'Pre-game' || $this->phase == 'Finished' ) ||
			!isset($this->Members->ByUserID[$User->id]) ||
			$this->Members->ByUserID[$User->id]->status != 'Playing')
			return '';
		
		if ($this->adminLock == 'Yes') return '';

		$vAllowed = Members::$votes;
		$vSet = $this->Members->ByUserID[$User->id]->votes;
		$vPassed = $this->Members->votesPassed();

		$vCancel=array();
		$vVote=array();
		
		foreach($vAllowed as $vote) $votesCast[$vote]=array();
			
		foreach($this->Members->ByStatus['Playing'] as $Member)
			foreach ($Member->votes as $vote)
				$votesCast[$vote][] = $Member->country;
		
		$buf = '<div class="bar membersList memberVotePanel"><a name="votebar"></a><div class="votes">';
		$buf .= '<form action="board.php?gameID='.$this->id.'#votebar" method="post">';
		$buf .= '<input type="hidden" name="formTicket" value="'.libHTML::formTicket().'" />';
		
		foreach($vAllowed as $vote)
		{
			if (strpos($this->blockVotes,$vote)!== false) continue;

			if(in_array($vote, $vSet))
			{
				if(!in_array($vote, $vPassed))
					$buf .= $this->voteHTML($vote, $votesCast[$vote], true);
			}
			else
				$buf .= $this->voteHTML($vote, $votesCast[$vote], false);
		}
		$buf .= '</form>';
		
		$buf .= '<img id = "modBtnVote" height="16" width="16" src="images/icons/help.png" alt="Help" title="Help" style="padding: 8px;"/>
		<div id="voteModal" class="modal">
			<div class="modal-content">
				<span class="close1">&times;</span>
				<p><strong>Draw Vote: </strong></br>
					If all players vote draw, the game will be drawn. ';
		switch ($this->potType) 
		{
			case 'Points-per-supply-center':
					$buf .= 'This game is scored using points per supply center. In a draw, points are split evenly among all players remaining.';
					break;
			case 'Winner-takes-all':
					$buf .= 'This game is scored using draw size scoring. In a draw, points are split evenly among all players remaining.';
					break;             
			case 'Unranked':
					$buf .= 'This game is unranked. In a draw, all points are returned to their previous owners.';
					break;             
			case 'Sum-of-squares':
					$buf .= 'This game is scored using sum of squares. In a draw, points are split among remaining players based upon how many supply centers they have.';
					break;             
			default:
					trigger_error("Unknown pot type '".$this->potType."'");
					break;
		}
		switch ($this->drawType) 
		{
			case 'draw-votes-public':
				$buf .= ' Draw votes are publicly displayed in this game.';
				break;
			case 'draw-votes-hidden':
				$buf .= ' Draw votes are not publicly known in this game.';
				break;
			default:
				trigger_error("Unknown draw type '".$this->drawType."'");
				break;
		}
		$buf.= '</p>';
		
		if( $this->processStatus == 'Paused' )
		{
			$buf .= '<p><strong>Unpause Vote: </strong></br>
						If all players vote unpause, the game will be unpaused. If a game is stuck paused, email the mods at webdipmod@gmail.com for help.
					</p>';
		}
		else
		{
			$buf .= '<p><strong>Pause Vote: </strong></br>
						If all players vote pause, the game will be paused until all players vote unpause. If you need a game paused'. ($this->pressType == 'NoPress' ? '' : ' due to an emergency').', click on the Need Help? link just above this icon to contact the mods.
					</p>';
		}
		
		$buf .= '<p><strong>Cancel Vote: </strong></br>
					If all players vote cancel, the game will be cancelled. All points will be refunded, and the game will be deleted. Cancels are typically used in the first year or two of a game with missing players.
				</p>';

		if ($this->playerTypes <> 'Members')
		{
			$buf .= '<p><strong>Bot Voting: </strong></br>
				The bots in this game do not get a pause or unpause vote, pausing and unpausing only counts human votes. <br><br>
				If a bot is winning a game and has gained supply centers in the last 4 turns, it will stop the game from being drawn or cancelled. Otherwise bot games can be drawn or cancelled anytime.  
			</p>';
		}
		
		$buf .='
			</div>
		</div>';
		
		$buf .= '<script>
		var modal1 = document.getElementById("voteModal");
		var btn1 = document.getElementById("modBtnVote");
		var span1 = document.getElementsByClassName("close1")[0];
		btn1.onclick = function() { modal1.style.display = "block"; }
		span1.onclick = function() { modal1.style.display = "none"; }
		window.onclick = function(event) {
		  if (event.target == modal1) { modal1.style.display = "none"; }
		}
		</script>';

		$buf .= '<div style="clear:both"></div></div><div class="hr"></div>';

			
		return $buf;
	}

	function voteHTML($vote, $votesActiveBy, $voteActive)
	{
		
		global $User;
		
		if ( $vote == 'Pause' && $this->processStatus == 'Paused' ) $vote = 'Unpause';

		if ($voteActive){
			$style    = "button setvote";
			$question = l_t("Are you sure you want to withdraw your ".$vote." vote?");
		} else {
			$style    = "button";
			$question = l_t("Are you sure you want to cast this ".$vote." vote?");
		}

		$voteCountries = implode (", ", $votesActiveBy);
		$voteCountries = str_replace($this->Members->ByUserID[$User->id]->country, "You", $voteCountries);
		if ( $vote == 'Draw' && $this->drawType == 'draw-votes-hidden' ) {
			$voteCountries = "(draw votes are hidden)";
		}
		
		$voteActiveImage = (($voteCountries != '' && $voteCountries != '(draw votes are hidden)')  ? ' <img src="images/icons/alert.png">' : '');
		$buttonTitle     = ($voteCountries != '' ? 'title = "Voted: '.$voteCountries.'"' : ''); ;
		
		$buf = '<button class="'.$style.'" name="'.$vote.'" onclick="return confirm(\''. $question.'\');" '
				.$buttonTitle.'/> <img src="images/icons/vote_'.strtolower($vote).'.png"> '.$vote.$voteActiveImage.'</button>';

		return $buf;
	}

	
	public function __construct($gameData)
	{
		parent::__construct($gameData);
	}

	/**
	 * No open bar from within an open game
	 * @return string Nothing
	 */
	public function openBar()
	{
		return '';
	}

	/**
	 * The vital game header info, but with an occupation bar and presented to fit at the
	 * top of a game board.
	 *
	 * @return string
	 */
	public function contentHeader()
	{
		global $User;

		$buf = '<a name="gamePanel"></a>';
		$buf .= $this->header();

		$buf .= '<div class="panelBarGraphTop occupationBar">'.$this->Members->occupationBar().'</div>';

		return '<div class="variant'.$this->Variant->name.'">'.$buf.'</div>';
	}

	/**
	 * A modified header, which will also print the info about the member which has joined if applicable,
	 * for use at the top of a game board.
	 * @return string
	 */
	function header()
	{
		global $User;
		libHTML::$alternate=2;
		$buf = '<div class="titleBar">
				'.$this->titleBar(true).'
			</div>';
		
		$buf .= $this->description();

		$noticeBar = $this->gameNoticeBar();
		if ( $noticeBar )
		{
			$buf .= '
				<div class="bar gameNoticeBar barAlt'.libHTML::alternate().'">
					'.$noticeBar.'
				</div>';
		}

		if ( $this->Members->isJoined() && $this->phase != 'Pre-game' )
		{
			$buf .= '<div class="membersList">'.$this->Members->ByUserID[$User->id]->memberHeaderBar().'</div>';
		}

		return $buf;
	}

	/**
	 * A summary which is header-less, since it is displayed at the top of board.
	 * @return string
	 */
	function summary()
	{
		print '
		<div class="gamePanel variant'.$this->Variant->name.'">
			'.($this->Members->isJoined()?$this->votes():'').'
			'.$this->members().'
			'.$this->links().'
			<div class="bar lastBar"> </div>
		</div>';
	}
}
?>
