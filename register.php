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
 * @package Base
 * @subpackage Forms
 */

require_once('header.php');
require_once("contrib/ayah/ayah.php");

$ayah = new AYAH();
if (array_key_exists('Submit', $_POST))
{
        // Use the AYAH object to see if the user passed or failed the game.
        $score = $ayah->scoreResult();

        if ($score)
        {
                // This happens if the user passes the game. In this case,
                // we're just displaying a congratulatory message.
                echo "Congratulations: you are a human!";
        }
        else
        {
		// This happens if the user does not pass the game.
                echo "Sorry, but we were not able to verify you as human. Please try again.";
        }
}

if ( $Misc->Panic )
{
	libHTML::notice(l_t('Registration disabled'),
		l_t("Registration has been temporarily disabled while we take care of an ".
		"unexpected problem. Please try again later, sorry for the inconvenience."));
}

// The user must be guest to register a new account
if( $User->type['User'] )
{
	libHTML::error(l_t("You're attempting to create a ".
		"new user account when you already have one. Please use ".
		"your existing user account."));
}

libHTML::starthtml();

$page = '';

try
{
	$page = 'userForm';

	// The user's e-mail is authenticated; he's not a robot and he has a real e-mail address
	// Let him through to the form, or process his form if he has one
	if ( isset($_REQUEST['userForm']) )
	{

		// If the form is accepted the script will end within here.
		// If it isn't accepted they will be shown back to the userForm page
		require_once(l_r('register/processUserForm.php'));
	}
	else
	{
		$page = 'firstUserForm';
	}
}
catch( Exception $e)
{
	print '<div class="content">';
	print '<p class="notice">'.$e->getMessage().'</p>';
	print '</div>';
}


switch($page)
{
	case 'firstUserForm':
	case 'userForm':
		print libHTML::pageTitle(l_t('Register a webDiplomacy account'),l_t('<strong>Enter your account settings</strong> -&gt; Pass anti-bot test -&gt; Play webDiplomacy!'));
}

switch($page)
{
	case 'firstUserForm':

		print "<p>".l_t("<p>Enter the username and password you want, and any of the optional details/settings, into the screen below to
			complete the registration process.")."</p>";

	case 'userForm':
		print '<form method="post"><ul class="formlist">';
		print $ayah->getPublisherHTML();
		require_once(l_r('locales/English/userRegister.php'));
		require_once(l_r('locales/English/user.php'));

		break;

}

print '</div>';
libHTML::footer();
?>

