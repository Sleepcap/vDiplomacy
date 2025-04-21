<?php
/*
    Copyright (C) 2014 Oliver Auth

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

if ((isset($_REQUEST['howto'])) && $_REQUEST['howto'] == 'CountrySwap') { 

	print libHTML::pageTitle(l_t('HowTo - Country swap for sitters'),l_t('So something unexpected has come up and you wont be able to access your games for a while. You need to find a sitter.')); ?>

	<p class="intro"><strong>Step 1 - </strong>Advertise for help in the forum, by posting your request in the "Advertise for sitters here!" thread.
	No need to add details, like this:</p>
	
	<div align="center"><img src="images/howto/CountrySwap_1.png" alt=""
		title="Advertise for help in the forum" /></div>

	<p class="intro">Whoever will be willing to sit some of your games will send you a Private Message.
	You can discuss any details via PM later, so do not mention or link the games you're asking for.
	Especially, do not add links of anonymous games because this will make your post anonymous as well, making to send you a PM an impossible thing.
	At this point, someone could think that posting a reply on the thread itself might be a good idea, but this will make everybody know who is
	sitting in which Anon game, therefore the anonimity of that game would be broken.<br>

	Remember that, in order to keep the anonimity of the games, the crucial thing is to keep secret your games, not you.<br>

	Just post a general request like the one shown in the picture and wait for someone to PM you.</p>

	<div class="hr" ></div>
	
	<p class="intro"><strong>Step 2 - </strong>You've received a positive response, someone will help you. First find their User ID on their profile:</p>

	<div align="center"><img src="images/howto/CountrySwap_2.png" alt=""
		title="find a User ID on a profile" /></div>

	<div class="hr" ></div>
	
	<p class="intro"><strong>Step 3 - </strong>Go to your settings page:</p>

	<div align="center"><img src="images/howto/NewUserSettings.png" alt=""
		title="Go to your settings page" /></div>

	<div class="hr" ></div>
	
	<p class="intro"><strong>Step 4 - </strong>Select the CountrySwitch tab:</p>

	<div align="center"><img src="images/howto/CountrySwap_3.png" alt=""
		title="Select the CountrySwitch tab" /></div>

	<div class="hr" ></div>
	
	<p class="intro"><strong>Step 5 - </strong>Select the game you wish to send - enter the Sitter's User ID - and click submit:</p>

	<div align="center"><img src="images/howto/CountrySwap_4.png" alt=""
		title="enter the Sitter's User ID" /></div>

	<div class="hr" ></div>
	
	<p class="intro"><strong>Step 6 - </strong>If the player is able to join the game then a request is sent to them:</p>

	<div align="center"><img src="images/howto/CountrySwap_5.png" alt=""
		title="a request is sent" /></div>

	<div class="hr" ></div>
	
	<p class="intro"><strong>Step 7 - </strong>The sitter will receive a notification that you have 'sent' them a game:</p>

	<div align="center"><img src="images/howto/CountrySwap_6.png" alt=""
		title="receive a notification" /></div>

	<div class="hr" ></div>
	
	<p class="intro"><strong>Step 8 - </strong>The sitter will need to accept the game to sit:</p>

	<div align="center"><img src="images/howto/CountrySwap_7.png" alt=""
		title="accept the game" /></div>

	<div class="hr" ></div>
	
	<p class="intro"><strong>Step 9 - </strong>When you return all you need to do is to enter the country switch tool and claim back your game:</p>

	<div align="center"><img src="images/howto/CountrySwap_8.png" alt=""
		title="claim back your game" /></div>

	<div class="hr" ></div>
	
	<p class="intro">You now know how to find sitters and swap games with them. Make sure you thank them, and if you have time to offer your help to others in need.</p>

	<div class="hr" ></div>

<?php } elseif ((isset($_REQUEST['howto'])) && $_REQUEST['howto'] == 'AnonPost') { 

	print libHTML::pageTitle(l_t('HowTo - Post anonymously in the forum.'),l_t("Here's how to advertise for an anonymous game.")); ?>

	<p class="intro">You've made a game and you want to advertise for more players. Or a Country has gone CD in your game and you want to advertise for someone
	who takes it over. However the game is an anonymous game, and if you advertise other players will know who you are. Here's how to advertise for an anonymous game:
	</p>
	
	<div class="hr" ></div>
	
	<p class="intro"><strong>Step 1 - </strong>Open the game you want to advertise for. Check to make sure:
	<ol><li>The game is <strong>anon</strong>.</li><li>You have already <strong>joined that game</strong>.</li></ol></p>

	<div align="center"><img src="images/howto/AnonPost_1.png" alt=""
		title="Open the game" /></div>

	<div class="hr" ></div>
	
	<p class="intro"><strong>Step 2 - </strong>Copy the gameID=### section of the url :</p>

	<div align="center"><img src="images/howto/AnonPost_2.png" alt=""
		title="Copy the gameID" /></div>

	<div class="hr" ></div>
	
	<p class="intro"><strong>Step 3 - </strong>Paste the gameID into the forum and your post will become anonymous.</p>

	<div align="center"><img src="images/howto/AnonPost_3.png" alt=""
		title="Paste the gameID" /></div>

	<div class="hr" ></div>
	
	<p class="intro">Please note that anonymous posts are allowed only for advertise your new games and to advertise CD-Countries in your anon games.
	We have two different threads active in the forum (one for live, and another for non-live games) for these player-requests, so please don't open your own thread. </p>
	
	<p class="intro">Moderators can see who has written anonymous posts, so do not abuse this function.</p>
	
	<div class="hr" ></div>
<?php } else { 

	print libHTML::pageTitle(l_t('HowTos'),l_t('HowTos for some of the features that this site offers to you the player.')); ?>

	<p class="intro">
	<a href="howto.php?howto=CountrySwap">1. Country swap for sitters.</a> <br>
	<a href="howto.php?howto=AnonPost">2. Post anonymously in the forum.</a> 
	</p>
<?php } 
?>
