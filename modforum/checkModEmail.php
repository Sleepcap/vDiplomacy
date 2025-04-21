<?php
/*
    Copyright (C) 2020 Oliver Auth

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

$imap = imap_open(Config::$modEMailServerIMAP, Config::$modEMailLogin, Config::$modEMailPassword);
$headers = imap_search($imap, 'UNSEEN');
if ($headers != false)
{
	$sortedMails = array_map( function($val) {
		global $imap; 
		return (object) [
			'header' => imap_headerinfo ( $imap , $val ),
			'val' => $val
		];
	}, $headers);
	usort(
		$sortedMails,
		function($mailA, $mailB) {return $mailA->header->udate == $mailB->header->udate ? 0 : $mailA->header->udate < $mailB->header->udate ? -1 : 1;}
	);

	$lastDate = $Misc->vDipLastMail;
	foreach ($sortedMails as $mail)
	{
		$subject = property_exists($mail->header, 'subject') && $mail->header->subject != '' ? mb_decode_mimeheader($mail->header->subject) :  "[No subject]";
		$fromName	= quoted_printable_decode($mail->header->fromaddress);
		$fromMail 	= quoted_printable_decode($mail->header->from[0]->mailbox.'@'.$mail->header->from[0]->host);
		$date    = quoted_printable_decode($mail->header->udate);
		$body    = quoted_printable_decode(imap_fetchbody($imap,$mail->val,1.1)); 

		if ($body == '') $body = quoted_printable_decode(imap_fetchbody($imap,$mail->val,1));
		
		if ($date > $Misc->vDipLastMail)
		{
			require_once('modforum/libMessage.php');

			ModForumMessage::storeModMail($fromMail, $fromName, $subject, $body, $date);
						
			$lastDate = max ($date, $lastDate);
			print 'There is a new Mail '.$DB->escape($date).' in the mod-team-inbox.<br>'.$DB->escape($subject).' (From: '.$DB->escape($fromMail).')'."<br />\n";
		}
		
	}
	$Misc->vDipLastMail = $DB->escape($lastDate);
	$Misc->write();
}
imap_close($imap);

?>
