<?php

defined('IN_CODE') or die('This script can not be run by itself.');

/**
 * @package Base
 * @subpackage Static
 */

global $User;

if ($User->phaseCount < 100 &&  $User->getIntegrityRating() > libReliability::$maxRatingForSanction) {
?>
	<p class="intro">
	New members of this site have some light restrictions on how many games they can join or create at once.
	You need to play at least <strong>20 phases</strong>, before you can join more than 2 games, 
	<strong>50 phases</strong>, before you can join more than 4 games and at least <strong>100 phases</strong>,
	before you can join more than 7 games at once. 2-player variants and private games are not affected by this restriction.
	</p>
	
	<p class="intro">
	You can see your total phases played and more useful information in the reliability section of 
	<a href="profile.php?userID=<?php print $User->id; ?>">your stats page</a>.</p>

	<p class="intro">
	The restrictions are in place to ensure all new members do not jump into more games then they can handle.
	Diplomacy games can take up a lot of time so please test out several games before committing to multiple.
	</p>
<?php
} else {
	if ($User->getIntegrityRating()  <= libReliability::$maxRatingForSanction)
		print '<p class="intro">
			Your ability to join or create a new game is limited, because you seem to be having 
			trouble keeping up with the orders in the ones you already have.
			</p>';
?>
	<p class="intro">
	On this site we ask that all players respect their fellow players. Part of this respect includes
	entering orders every turn in your games. Diplomacy is a game of communication, trust (and mistrust),
	and because games usually take a long time to finish it's very important that you play the best you can
	and don't ruin the game halfway. 
	</p>

	<p class="intro">
	While playing a losing position might not be as fun as a winning one, it is still your responsibility to
	other members to continue to play. Even if you cannot win a game there are still plenty of ways to make the
	game fun. For example: you may choose to hurt the country that sealed your defeat, help someone secure a solo
	so that you can get a survive instead of a defeat, or use the rest of the game to practice manipulating other
	players on the board.
	</p>
<?php
}
?>
	

<p class="intro">
PLEASE NOTE: If you fail to submit orders for your country and used up all the excuses you have for the misses in
the game the country is
sent into "Civil disorder" which allows any site member to take over your position to ensure that the game's
integrity is not negatively impacted.
</p>

<p class="intro">
If you have too many unexcused missed turns within a year you will be temporary banned for a short period 
and you will be restricted in the
number of games you can play (2-player variants and private games excluded). This is to ensure that all players
take care not to take on more games then they can keep up with, and to ensure that players who do not respect their
fellow members are not given the chance to ruin multiple games.
</p>

<p class="intro">
If you were unreliable and due to that received some game limits and do not want to wait for a year until
the record has lapsed you can increase the number of games you can join by taking over 
<a href="gamelistings.php?page-games=1&gamelistType=Joinable">open spots in ongoing games</a>.
Each take-over will compensate one unexcused miss and will so reduce the game limits you experience.
</p>

<?php
	print '<p class="intro">You can read more about the specific sanctions for unexcused misses	
				<a href="profile.php?detail=civilDisorders&userID='.$User->id.'">here</a>.</p>';
?>
