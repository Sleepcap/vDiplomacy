<?php
/*
    Copyright (C) 2004-2009 Kestas J. Kuliukas

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
 * The configuration object. This is the only file that will require_once modification by
 * end users.
 *
 * @package Base
 */
 
class Config
{

	/**
	 * This is the MySQL socket. It could be a network socket or a UNIX socket.
	 *
	 * eg '127.0.0.1:3306'
	 * or 'localhost'
	 * or 'mysql.myhost.com'
	 * or '/tmp/mysql.sock'
	 *
	 * @var string
	 */
	public static $database_socket='localhost';

	/**
	 * The user who will perform all database actions. You should
	 * tweak the user's permissions so they can only do the bare
	 * minimum they need to be able to do to update the webDiplomacy
	 * tables. Read the administrator documentation for more info.
	 *
	 * @var string
	 */
	public static $database_username='root';

	/**
	 * The password of the above user
	 *
	 * @var string
	 */
	public static $database_password='';

	/**
	 * The database name
	 *
	 * @var string
	 */
	public static $database_name='vDip';

	/**
	 * This is used to salt hashes for passwords, if it gets out it's not the end of the world.
	 *
	 * *This should be long ( ~30 charecters), random, contain lots of weird charecters, etc*
	 * If this isn't changed or is predictable it is a serious security risk!
	 *
	 * @var string
	 */
	public static $salt='';

	/**
	 * This is used for session keys and the captcha code, and can be changed from time
	 * to time without too much difficulty, but it's even more important that it isn't known!
	 *
	 * @var string
	 */
	public static $secret='';

	/**
	 * This is used to authenticate the cron process which will run the gamemaster script.
	 * If anyone can run the gamemaster script there may be problems (despite the locking),
	 * and it can increase load. Whatever this string is it means gamemaster needs to be run
	 * either by an admin, or by gamemaster.php?secret=[thissecret]
	 *
	 * @var string
	 */
	public static $gameMasterSecret='';

	/**
	 * This is used to authenticate the cron process which will run the gamemaster script.
	 * If anyone can run the gamemaster script there may be problems (despite the locking),
	 * and it can increase load. Whatever this string is it means gamemaster needs to be run
	 * either by an admin, or by gamemaster.php?secret=[thissecret]
	 *
	 * @var string
	 */
	public static $jsonSecret='';

	/*
	 * Impresum: 
	 * A default impresum needed for german websites or sites hosted in germany-
	 * Enter your contact information here.
	 */
	public static $impresum=array(
		'name'    => '',
		'street'  => '',
		'city'    => '',
		'country' => '',
		'email'   => ''		
	);


	/**
	 * The administrators e-mail; if a user experiences a problem they will be invited to contact this
	 * e-mail address. It's unlikely bots will experience the sort of problem resulting in your e-mail
	 * being displayed, but if your e-mail provider doesn't filter spam well you may want to be careful.
	 *
	 * @var string
	 */
	public static $adminEMail='admin@vDiplomacy.com';
	
 	/**
	 * The moderators e-mail; if users have been banned etc they will be directed to contact this e-mail 
	 * to contest it.
	 * 
	 * @var string
	 */
	public static $modEMail='moderators@yourdiplomacyserver.com';
	public static $modEMailServerIMAP='{mail.your-server.de:143}INBOX'; // Link to the server in PHP-imap_open - format
	public static $modEMailServerHTTP='https://webmail.your-server.de'; // URL-Link to the server
	public static $modEMailLogin='';
	public static $modEMailPassword='';
	public static $modEMailName='vDiplomacy Moderator Team';

	/**
	 * Site name to be shown at various places on the application and inside mails.
	 * 
	 * @var string
	 */
	public static $siteName='vDiplomacy';
   
	/**
	 * If you use the piwik-webanalyser define his path here. If not comment this out.
	 */		
	// public static $piwik='piwik/';
	// public static $piwik_auth = '';

	/**
	 * An array of variants available on the server (for future releases, not yet enabled)
	 * @var array
	 */
	public static $variants=array(
		 1=>'Classic',
//		 2=>'World',
//		 3=>'FleetRome',
//		 4=>'CustomStart',
//		 5=>'BuildAnywhere',
//		 6=>'SouthAmerica5',
//		 7=>'SouthAmerica4',
//		 8=>'Hundred',
//		 9=>'AncMed',
//		10=>'ClassicMilan',
//		11=>'Pure',
//		12=>'Colonial',
//		13=>'Imperium',
//		14=>'ClassicCrowded',
//		15=>'ClassicFvA',
//		16=>'SailHo2',
//		17=>'ClassicChaos',
//		18=>'ClassicSevenIslands',
//		19=>'Modern2',
//		20=>'Empire4',
//		21=>'Migraine',
//		22=>'Duo',
//		23=>'ClassicGvI',
//		24=>'SouthAmerica8',
//		25=>'ClassicGvR',
//		26=>'ClassicFGRT',
//		27=>'Sengoku5',
//		28=>'Classic1897',
//		29=>'Rinascimento',
//		30=>'ClassicFog',
//		31=>'Alacavre',
//		32=>'DutchRevolt',
//		33=>'Empire1on1',
//		34=>'Classic1880',
//		35=>'GreekDip',
//		36=>'Germany1648',
//		37=>'MateAgainstMate',
//		38=>'ClassicNoNeutrals',
//		39=>'Fubar',
//		40=>'ClassicOctopus',
//		41=>'Lepanto',
//		42=>'ClassicVS',
//		43=>'WhoControlsAmerica',
//		44=>'FantasyWorld',
//		45=>'Karibik',
//		46=>'BalkanWarsVI',
//		47=>'Hussite',
//		48=>'ClassicFGA',
//		49=>'ClassicIER',
//		50=>'ClassicGreyPress',
//		51=>'Haven',
//		52=>'WWIV',
//		53=>'ClassicEconomic',
//		54=>'ClassicChaoctopi',
//		55=>'TenSixtySix',
//		56=>'USofA',
//		57=>'KnownWorld_901',
//		58=>'TreatyOfVerdun',
//		59=>'YoungstownRedux',
//		60=>'ClassicPilot',
//		61=>'War2020',
//		62=>'ClassicEvT',
//		63=>'Viking',
//		64=>'ClassicTouchy',
//		65=>'RatWars',
//		66=>'Pirates',
//		67=>'Abstraction3',
//		68=>'Habelya',
//		69=>'AmericanConflict',
//		70=>'Zeus5',
//		71=>'Colonial1885',
//		72=>'Europe1939',
//		73=>'NorthSeaWars',
//		74=>'Maharajah',
//		75=>'CelticBritain',
//		76=>'Enlightenment',
//		77=>'GreatLakes',
//		78=>'AgeOfPericles',
//		79=>'AnarchyInTheUK',
//		80=>'Mars',
//		81=>'Imperial2',
//		82=>'DarkAges',
//		83=>'Africa',
//		84=>'ClassicCataclysm',
//		85=>'TenSixtySix_V2',
//		86=>'ClassicLayered',
//		87=>'WWII',
//		88=>'AberrationV',
//		89=>'HeptarchyIV',
//		90=>'ClassicAnkaraCrescent',
//		91=>'ColdWar',
//		92=>'YoungstownWWII',
//		93=>'Chromatic',
//		94=>'TenSixtySix_V3',
//		95=>'WWIVsealanes',
//		96=>'GobbleEarth',
//		97=>'Europe1600',
//		98=>'FirstCrusade',
//		99=>'AtlanticColonies',
//		100=>'Sengoku6',
//		101=>'Napoleonic',
//		102=>'WWIV_V6',
//		103=>'Balkans1860',
//		104=>'Iberian',
//		105=>'Divided_States',
//		106=>'Classic1913',
//		107=>'Renaissance1453',
//		108=>'Canton',
//		109=>'Machiavelli',
//		110=>'Edwardian',
//		111=>'VersaillesRedux',
//		112=>'ManifestDestiny',
//		113=>'EmpiresCoalitions',
//		114=>'Crusades1201',
//		115=>'MachiavelliTTR',
//		116=>'SpiceIslands',
//		117=>'AustrianSuccession',
//		118=>'Caucasia',
//		119=>'ClassicCroatia',
//		120=>'ClassicEgypt',
//		121=>'ClassicFlorence',
//		122=>'ClassicBritain',
//		123=>'ClassicBrazilian',
//		124=>'AfricaScramble',
//		125=>'Europe1908',
//		126=>'NorthAmerica1862',
//		127=>'WesternWorld_901',
//		128=>'ColdWarRedux',
//		129=>'World10',
//		130=>'Edwardian3',
//		131=>'EastIndies',
//		132=>'Chesspolitik',
//		133=>'Classic1898',
//		134=>'Classic1898Fog',
//		135=>'Hexagon',
//		136=>'A_Modern_Europe',
//		137=>'TiglathPileser',
//		138=>'MongolianEmpire',
//		141=>'Scottish_Clan_Wars',
//		145=>'WesternEurope1300',
//		155=>'Europa_Renovatio',
//		171=>'WorldAtWar1937',
//		171=>'WorldAtWar1937',
//		208=>'PunicWars',
//		1900=>'Baron1900',
		);

	/**
	 * A boolean controlling whether automatic gr calculations are enabled. Set to true for auto-GR calculation and false to require manual calculations via the modtool. Note that $grCategories must exist to work.
	 * @var boolean
	 */
	public static $grActive = false;

	/**
	 * An array of categories to use when calculating GhostRatings
	 * @var array
	 */
	public static $grCategories=array(
		/* A Category ID maps to an array of settings */
		0 => array(
			/*gives the name of the category*/
			"name" => "Overall",
			/*Different scoring systems are used for 1v1 and non-1v1*/
			"1v1" => "No",
			/*variantMods sets which variants to include*/
			"variants" => array(1,2,9,19,20,17),
			/*pressMods sets which press type to include*/
			"presses" => array('Regular','PublicPressOnly','NoPress','RulebookPress'),
			/*phases sets whether you want to include live games, non-live games, or both. The cutoff is at 1 hour phase lengths. 1 hour phases are considered non-live*/
			"phases" => array('Live', 'Nonlive'),
			/*scoring lets you choose what type of scoring sytems to include - only these three types are supported in the current code*/
			"scoring" => array('Winner-takes-all','Points-per-supply-center','Sum-of-squares')
		),
		1 => array(
			"name" => "Gunboat",
			"1v1" => "No",
			"variants" => array(1,2,9,19,20,17),
			"presses" => array('NoPress'),
			"phases" => array('Live', 'Nonlive'),
			"scoring" => array('Winner-takes-all','Points-per-supply-center','Sum-of-squares')
		),
		2 => array(
			"name" => "Live",
			"1v1" => "No",
			"variants" => array(1,2,9,19,20,17),
			"presses" => array('Regular','PublicPressOnly','NoPress','RulebookPress'),
			"phases" => array('Live'),
			"scoring" => array('Winner-takes-all','Points-per-supply-center','Sum-of-squares')
		),
		3 => array(
			"name" => "Full Press",
			"1v1" => "No",
			"variants" => array(1),
			"presses" => array('Regular'),
			"phases" => array('Nonlive'),
			"scoring" => array('Winner-takes-all','Sum-of-squares')
		),
		4 => array (
			"name" => "1v1 Overall",
			"1v1" => "Yes",
			"variants" => array(15,23)
		),
		5 => array (
			"name" => "FvA",
			"1v1" => "Yes",
			"variants" => array(15)
		),
		6 => array (
			"name" => "GvI",
			"1v1" => "Yes",
			"variants" => array(23)
		)
	);
	
	/**
	 * An array of modvalues to use when calculating GhostRatings. The lower the number the more weight it carries
	 * @var array
	 */
	public static $grVariantMods = array(1=>1, 2=>4, 9=>2, 19=>4, 20=>4, 15=>1, 23=>1, 17=>8);
	
	/**
	 * An array of modvalues to use when calculating GhostRatings. The lower the number the more weight it carries
	 * @var array
	 */
	public static $grPressMods = array('Regular'=>1,'PublicPressOnly'=>2,'NoPress'=>4,'RulebookPress'=>1);

	/**
	 * The API configuration. Whether to enable it or not, and restrict it to some variants or some gameIDs.
	 *
	 * @var array
	 */
//	public static $blockedVariants=array(
//		22, // 
//	);
	
	/**
	 * An array of variants that are blocked for guests on the server.
	 * If you browse the gamelist or variantpage as a guest you can't see the variants listed here.
	 * This is for adding variants that you do not want to be found by web-bots or random guests.
	 *
	 * @var array
	 */
//	public static $hiddenVariants=array(
//		62, // TenSixtySix
//	);
	
	/*
	 * Limit the maximum bet-size based on how many players can join
	 */
//	public static $limitBet = array (
//		2=>'1', 3=>'5', 4=>'10', 5=>'20', 6=>'30'
//	);
	
	
	// To give certain users without an admin-account the tools to edit variants on the server
//	public static $devs=array(
//		'dummy1' => array(
//			'Classic',
//			'AncMed'
//		)
//	);

	/**
	 * Messages to display when different flags are set via the admin control panel.
	 *
	 * If ServerOffline is set it will be displayed and the script will not start.
	 *
	 * @var array
	 */
	public static $serverMessages=array(
			'Notice'=>'Update done. Every game got 12 hours added. Thanks for your patience.',
 			'Panic'=>'Game processing has been paused and user registration has been disabled while a problem is resolved.',
			'Maintenance'=>"30 minutes downtime. Have a large update to do (and my internet connection is really slow)",
			'ServerOffline'=>'',
			'Notice'=>"",
		);

	/**
	 * The directory in which error logs are stored. If this returns false errors will not be logged.
	 * *Must not be accessible to the web server, as sensitive info is stored in this folder.*
	 *
	 * @return string
	 */
	public static function errorlogDirectory()
	{
//		return false;
		return ('logfiles');
	}

	/**
	 * Should every piece of every order entered be logged as it comes in? This helps solve
	 * problems when people think they submitted correct orders but may not have, but it
	 * can use up lots of disk space and waste resources every time orders are submitted.
	 *
	 * Every complaint about incorrect orders have been found via the order logs to be
	 * correctly received, so it's probably not worth enabling this unless you get many
	 * complaints.
	 *
	 * If this is set to false orders will not be logged, if it returns a writable directory
	 * orders will be logged there.
	 * *Must not be accessible to the web server, as sensitive info is stored in this folder.*
	 *
	 * @return string
	 */
	public static function orderlogDirectory()
	{
		return false;
		return '../orderlogs';
	}

	/**
	 * Where to log points before/after logs to, which log the points before/after games have ended.
	 * If false points are not logged.
	 *
	 * @var string
	 */
	public static $pointsLogFile=false; //'../pointslog.txt';

	/**
	 * An array of e-mail settings, to validate e-mails etc.
	 *
	 * @var array
	 */
	public static $mailerConfig = array(
			"From"=> "admin@vDiplomacy.com",
			/* The e-mail which mail is sent from. This should be a valid e-mail,
			or it may trip spam filters. */
			"FromName"=> "vDiplomacy",
			/* The name being mailed from. */
			"UseMail"=>false,
			/* Use the php mail() function. Either UseMail, UseSendmail or UseSMTP has to be TRUE,
				if you're using e-mail. */
			"UseSendmail"=>true,
			/* Use the sendmail binary, if this is false the variable below is ignored */
			"SendmailSettings"=> array(
					"Location"=>"/usr/sbin/sendmail"
					/* Location of the sendmail binary */
				),
			"UseSMTP"=> false,
			/* Use SMTP, if this is FALSE the variable below is ignored. */
			"SMTPSettings"=> array(
					"Host"=>"yourdiplomacyserver.com",
					"Port"=>"25",
					"SMTPAuth"=>false,
					/* If this is FALSE the two variables below are ignored */
					"Username"=>"webmaster",
					"Password"=>"password123"
				),
			"UseDebug" => true // If this is set to true mail will be output to the browser instead of sent, useful for debugging
		);

	/**
	 * Something to add after everything else has been printed off (except '</body></html>'), useful for
	 * things like Google Analytics, or web-rings
	 */
	public static function customFooter()
	{
		return '';
	}

	// ---
	// --- From here on down the default settings will probably be fine.
	// ---

	/**
	 * Enables full error and profiler output even when not viewing as admin. (This
	 * is set to true if viewing as admin)
	 * @var boolean
	 */
	public static $debug=false;

	/**
	 * The default locale for guest users.
	 *
	 * @var string
	 */
	public static $locale = 'English';

	/**
	 * Array of available locales
	 *
	 * @var string[]
	 */
	public static $availablelocales = array(
			'English' => 'English'
			);

	/**
	 * Different names given to the same locales, to allow automatic
	 * recognition of which locale to use.
	 *
	 * @var string[][]
	 */
	public static $localealiases = array(
		'English' => array('eng',
			'en_us',
			'en_US',
			'English',
			'en_US.ISO8859-1',
			'en_US.ISO8859-15',
			'en_US.US-ASCII',
			'en_US.UTF-8')
		);

	/**
	 * The number of minutes that gamemaster.php will detect that it hasn't been run for before it will
	 * mark itself in downtime mode.
	 */
	public static $downtimeTriggerMinutes=60000;


	// ---
	// --- The following settings are typically for Facebook webmasters only
	// ---

	/**
	 * The URL which static data, such as images, are stored at (usually only for Facebook or advanced users)
	 *
	 * eg http://static.webdiplomacy.net/
	 *
	 * @var string
	 */
	public static $facebookStaticURL='';

	/**
	 * The URL of the front end of the server (usually only for Facebook or advanced users)
	 *
	 * eg http://webdiplomacy.net/
	 *
	 * @var string
	 */
	public static $facebookServerURL='';

	/**
	 * The Facebook API key. If you're not on Facebook this will be ignored
	 *
	 * @var string
	 */
	public static $facebookAPIKey='';

	/**
	 * The Facebook secret. If you're not on Facebook this will be ignored
	 *
	 * @var string
	 */
	public static $facebookSecret='';

	/**
	 * The path to the Facebook API script (facebook.php)
	 *
	 * eg ../../facebook-client/
	 *
	 * @var string
	 */
	public static $facebookAPIPath='';

	/**
	 * The user ID of the Facebook user to send game notification messages from.
	 *
	 * This is provided to $facebook->set_user(user_id,secret)
	 *
	 * @var int
	 */
	public static $facebookNotificationFromUserID='';

	/**
	 * The authentication secret of the above Facebook user
	 *
	 * @var string
	 */
	public static $facebookNotificationFromUserSecret='';

	/**
	 * The Facebook debug value
	 *
	 * @var bool
	 */
	public static $facebookDebug=false;
}

?>
