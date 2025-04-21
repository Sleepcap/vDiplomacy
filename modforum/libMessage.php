<?php

class ModForumMessage
{
	public static function splitWords($text) {
		return $text;
		$words = explode(' ', $text);
		$text=array();
		foreach($words as $word)
		{
			if ( strlen($word) >= 20 )
			{
				$text[] = substr($word,0,20);
				$text[] = substr($word,20,strlen($word));
			}
			else
				$text[] = $word;
		}
		return implode(' ', $text);
	}

	static public function linkify($message)
	{
		$message=self::splitWords($message);

		$patterns = array(
				'/gameID[:= _]?([0-9]+)/i',
				'/userID[:= _]?([0-9]+)/i',
				'#(modforum.php.*viewthread[:= _]?)([0-9]+)#i',
				'#/forum.php.*threadID[:= _]?([0-9]+)#i',
				'/((?:[^a-z0-9])|(?:^))([0-9]+) ?(?:(?:D)|(?:points))((?:[^a-z])|(?:$))/i',
			);
		$replacements = array(
				'<a href="board.php?gameID=\1" class="light">gameID=\1</a>',
				'<a href="profile.php?userID=\1" class="light">userID=\1</a>',
				'<a href="modforum.php?viewthread=\2#\2" class="light">\1\2</a>',
				'/forum.php?<a href="forum.php?threadID=\1#\1" class="light">threadID=\1</a>',
				'\1\2'.libHTML::points().'\3'
			);

		return preg_replace($patterns, $replacements, $message);
	}

	static public function linkifyWithAbsPaths($message)
	{
		$message=self::splitWords($message);

		$baseUrl = 'https://'.$_SERVER['SERVER_NAME'].'/';

		$patterns = array(
				'/gameID[:= _]?([0-9]+)/i',
				'/userID[:= _]?([0-9]+)/i',
				'#(modforum.php.*viewthread[:= _]?)([0-9]+)#i',
				'#/forum.php.*threadID[:= _]?([0-9]+)#i',
				'/((?:[^a-z0-9])|(?:^))([0-9]+) ?(?:(?:D)|(?:points))((?:[^a-z])|(?:$))/i',
			);
		$replacements = array(
				'<a href="'.$baseUrl.'board.php?gameID=\1" class="light">gameID=\1</a>',
				'<a href="'.$baseUrl.'profile.php?userID=\1" class="light">userID=\1</a>',
				'<a href="'.$baseUrl.'modforum.php?viewthread=\2#\2" class="light">\1\2</a>',
				'<a href="'.$baseUrl.'forum.php?threadID=\1#\1" class="light">'.$baseUrl.'/forum.php?threadID=\1</a>',
				'\1\2'.libHTML::points($baseUrl).'\3'
			);

		return preg_replace($patterns, $replacements, $message);
	}

	/**
	 * Send a message to the public forum. The variables passed are assumed to be already sanitized
	 *
	 * @param int $toID User/Thread ID to send to
	 * @param int $fromUserID UserID sent from
	 * @param string $plainMessage The message to be sent
	 * @param string[optional] $subject The subject
	 * @param string[optional] $type 'Bulletin'(GameMaster->Player) 'ThreadStart'(User->All) 'ThreadReply'(User->Thread)
	 * @param string[optional] $adminReply 'No'(show to recipient) 'Yes'(show only internally)
	 * @param string[optional] $sendMailTo If set will be used to send an email to the provided address.
	 *
	 * @return int The message ID
	 */
	static public function send($toID, $fromUserID, $message, $subject="", $type='Bulletin', $adminReply='No', $sendMailTo=NULL)
	{
		global $DB, $User;

		if( defined('AdminUserSwitch') && AdminUserSwitch != $User->id) $fromUserID = AdminUserSwitch;

		$linkifiedMessage = self::linkify($message);

		$sentTime=time();

		if( 65000 < strlen($linkifiedMessage) )
		{
			throw new Exception("Message too long");
		}

		$sendMail = ( $sendMailTo !== NULL && $adminReply === 'No' );
		if( $sendMail )
		{
			require_once(l_r('objects/mailer.php'));
			$Mailer = new Mailer();
			$Mailer->Send(array($sendMailTo=>$sendMailTo), $subject, stripslashes(self::linkifyWithAbsPaths($message)), 'mod');
			/* Note on stripslashes:
			 * By design / architecture $DB->msg_escape is called much earlier in execution and results in the message being also excaped with respect to quotes.
			 * We decide to revert the quote escapes here for sending out the mail instead of passing an unescaped message down since changing the current
			 * behavior in this one case just seems to dangerous. Unescaped strings could too easily end up in the DB and mess things up there.
			 */
		}

		if( $type === 'ThreadReply' )
		{
			$subject="";
		}

		libCache::wipeDir(libCache::dirName('mod_forum'));

		$DB->sql_put("INSERT INTO wD_ModForumMessages
						SET toID = ".$toID.", fromUserID = ".$fromUserID.", timeSent = ".$sentTime.",
						message = '".$linkifiedMessage."', subject = '".$subject."', replies = 0,
						type = '".$type."', latestReplySent = 0, adminReply = '".$adminReply."'");

		$id = $DB->last_inserted();

		$DB->sql_put("UPDATE wD_Users SET notifications = CONCAT_WS(',',notifications, 'ModForum') WHERE type LIKE '%Moderator%' AND id != ".$fromUserID);
		
		if ( $type == 'ThreadReply' )
			$DB->sql_put("UPDATE wD_ModForumMessages SET latestReplySent = ".$id.", replies = replies + 1 WHERE ( id=".$id." OR id=".$toID." )");
		else
			$DB->sql_put("UPDATE wD_ModForumMessages SET latestReplySent = id WHERE id = ".$id);
			
		if ($User->type['Moderator'])
		{
			$DB->sql_put("UPDATE wD_ModForumMessages SET status='Open' WHERE status='New' AND id = ".$toID);
		}
		
		if ( $type == 'ThreadReply' && $adminReply=='No')
		{
			list($starter) = $DB->sql_row('SELECT fromUserID FROM wD_ModForumMessages WHERE id = '.$toID);
			if ( isset($starter) && $starter != $fromUserID)
				$DB->sql_put("UPDATE wD_Users SET notifications = CONCAT_WS(',',notifications, 'ModForum') WHERE id = ".$starter);
		}	


		$tabl=$DB->sql_tabl("SELECT t.id FROM wD_ModForumMessages t LEFT JOIN wD_ModForumMessages r ON ( r.toID=t.id AND r.fromUserID=".$fromUserID." AND r.type='ThreadReply' ) WHERE t.type='ThreadStart' AND ( t.fromUserID=".$fromUserID." OR r.id IS NOT NULL ) GROUP BY t.id");
		$participatedThreadIDs=array();
		while(list($participatedThreadID)=$DB->tabl_row($tabl)) {
			$participatedThreadIDs[$participatedThreadID] = $participatedThreadID;
		}

		$cacheUserParticipatedThreadIDsFilename = libCache::dirID('users',$fromUserID).'/readModThreads.js';

		file_put_contents($cacheUserParticipatedThreadIDsFilename, 'participatedModThreadIDs = $A(['.implode(',',$participatedThreadIDs).']);');

		return $id;
	}

	/**
	 * Remove any HTML added to a message
	 * @param $message The message to filter
	 * @return string The filtered message
	 */
	static function refilterHTML($message)
	{
		$patterns = array(
				'/<style[^>]*>[^<]+<\/style>/i',
				'/<[^>]+>/i',
				'/<[^>]+$/i'
			);
		$replacements = array(
				'',
				'',
				''
			);

		return preg_replace($patterns, $replacements, $message);
	}

	static function storeModMail($fromMail, $fromName, $subject, $message, $timeSent)
	{
		global $DB;

		$fromMail = $DB->escape($fromMail);
		$fromName = $DB->escape($fromName);
		$subject = $DB->escape($subject);
		$message = self::linkify($DB->msg_escape(self::refilterHTML(html_entity_decode($message))));
		$timeSent = $DB->escape($timeSent);

		$senderUserID = User::findEmail($fromMail); 
		if( $senderUserID != 0 ){
			$senderUser = New User($senderUserID);
		}

		if( 65000 < strlen($message) )
		{
			throw new Exception("Message too long");
		}

		libCache::wipeDir(libCache::dirName('mod_forum'));

		// check if mail is a reply to an existing mail. If so, attach to thread
		$tabl = $DB->sql_tabl("SELECT id, subject
						FROM wD_ModForumMessages
						WHERE fromMail = '".$fromMail."' and type='ThreadStart' and subject <> ''
						ORDER BY timeSent asc");

		while( list($id, $existingSubject) = $DB->tabl_row(($tabl) )){

			if( strpos($subject, $existingSubject) !== false ){
				$threadID = $id;
				break;
			}

		}

		if( !isset($threadID) ){
			// no existing thread -> start new one
			$threadHeadContent = 'There is a new Mail in the mod-team-inbox (<a href="'.Config::$modEMailServerHTTP.'">Webmail</a>)<br /><br />';
			
			$threadHeadContent .= 'Subject: '.$subject.'<br />';
			$threadHeadContent .= 'Sender: '.$fromName.' <'.$fromMail.'>';
			if(isset($senderUser)) $threadHeadContent .= ' ('.$senderUser->profile_link().')';
			$threadHeadContent .='<br />';
			$threadHeadContent .= 'Time sent: '.libTime::text($timeSent);

			$DB->sql_put("INSERT INTO wD_ModForumMessages
						SET toID = 0, fromMail = '".$fromMail."', timeSent = ".$timeSent.",
						message = '".$threadHeadContent."', subject = '".$subject."', replies = 0,
						type = 'ThreadStart', latestReplySent = 0, adminReply = 'No'");

			$threadID = $DB->last_inserted();
		}

		$DB->sql_put("INSERT INTO wD_ModForumMessages
						SET toID = ".$threadID.", fromMail = '".$fromMail."', timeSent = ".$timeSent.",
						message = '".$message."', subject = '', replies = 0,
						type = 'ThreadReply', latestReplySent = 0, adminReply = 'No'");

		$id = $DB->last_inserted();

		$DB->sql_put("UPDATE wD_Users SET notifications = CONCAT_WS(',',notifications, 'ModForum') WHERE type LIKE '%Moderator%'");
		
		$DB->sql_put("UPDATE wD_ModForumMessages SET latestReplySent = ".$id.", replies = replies + 1 WHERE ( id=".$id." OR id=".$threadID." )");

		return $id;
	}
}

?>