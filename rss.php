<?php
/*
    Copyright (C) 2018 Oliver Auth

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

/**
 * @package rss
 */
define('IN_CODE', 1);

// No need to load all the vDip overhead if there is no valid user or RSS ID.
if( !isset($_REQUEST['rssID']) ) die('rssID not provided.');

require_once('header.php');

$rssID = $DB->escape($_REQUEST['rssID']);
if ( !($rssID) ) die('rssID not provided.');

header("Content-Type: application/rss+xml; charset=ISO-8859-1");

list($userID, $name)= $DB->sql_row('SELECT id, username FROM wD_Users WHERE rssID="'.$rssID.'"');

$rssLink = "http://".str_replace("rss.php","",$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF']);
$rssfeed = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
$rssfeed .= "<rss version=\"2.0\">\n";
$rssfeed .= "  <channel>\n";
$rssfeed .= "    <title>".$name."'s VDiplomacy RSS feed</title>\n";
$rssfeed .= "    <link>http://".$_SERVER['SERVER_NAME']."</link>\n";
$rssfeed .= "    <description>This is the personal vDip RSS-feed of ".$name.".</description>\n";
$rssfeed .= "    <language>en-us</language>\n";
$rssfeed .= "    <copyright>Copyright (C) 2018 vDiplomacy.com</copyright>\n";
 
$tabl=$DB->sql_tabl('SELECT type, text, linkName, linkID, timeSent FROM wD_Notices
	WHERE toUserID = "'.$userID.'" AND substring(linkName,1,3) <> "To:"
	ORDER BY timeSent DESC LIMIT 50');
	
while(list($type, $text, $linkName, $linkID, $timeSent) = $DB->tabl_row($tabl))
{
	if ($type == 'Game')
		$link = $rssLink."/board.php?gameID=".$linkID;
	else
		$link = $rssLink."/profile.php?userID=".$linkID."#message";
		
	$text = html_entity_decode ($text, ENT_QUOTES | ENT_XHTML | ENT_HTML5, 'ISO-8859-1');
	$text = strip_tags ($text);
	$text = str_replace("&","-",$text);
	
	$rssfeed .= "    <item>\n";
	$rssfeed .= "      <title>".$linkName."</title>\n";
	$rssfeed .= "      <description>".$text."</description>\n";
	// remove guid entry as it is not unique (timeSent can be duplicate)
	// $rssfeed .= "      <guid>".$link."time".$timeSent."</guid>\n";
	$rssfeed .= "      <link>".$link."</link>\n";
	$rssfeed .= "      <pubDate>".date("D, d M Y H:i:s O", $timeSent)."</pubDate>\n";
	$rssfeed .= "    </item>\n";
}
 
$rssfeed .= "  </channel>\n";
$rssfeed .= "</rss>\n";

echo $rssfeed;
?>
