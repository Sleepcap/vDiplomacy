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
 * A library used to send messages to the public forum (could be merged with something else)
 *
 * @package Base
 */
class Message
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
				'/threadID[:= _]?([0-9]+)/i',
				'/((?:[^a-z0-9])|(?:^))([0-9]+) ?(?:\(D\))((?:[^a-z])|(?:$))/i',
			);
		$replacements = array(
				'<a href="board.php?gameID=\1" class="light">gameID=\1</a>',
				'<a href="profile.php?userID=\1" class="light">userID=\1</a>',
				'<a href="forum.php?threadID=\1#\1" class="light">threadID=\1</a>',
				'\1\2'.libHTML::points().'\3'
			);

		return preg_replace($patterns, $replacements, $message);
	}

	static public function check_anon($toID, $fromUserID, $message, $subject)
	{
	
		global $DB;
		
		$anon = 'No';
		
		$gameID = array(); $search = $subject . $message;
		
		// Check if there is a link to an anon game in the message or the subject.
		preg_match_all ('/gameID[:= _]?([0-9]+)/i', $search, $gameID);
		if (empty ($gameID[1]))
			$anonIDs = 0;
		else
		{
			$gameIDs = array_unique($gameID[1]);
			list($anonIDs)=$DB->sql_row('
				SELECT count(*) FROM wD_Games g LEFT JOIN wD_Members m ON (m.gameID = g.id)
					WHERE phase != "Finished"
					AND anon="Yes"
					AND g.id IN ('.implode (",", $gameIDs).')
					AND m.userID='.$fromUserID);
		}

		// If there is nothing in the message-body test the subject of the thread-start
		if ($anonIDs == 0 && $toID != 0)
		{
			list($search)=$DB->sql_row('SELECT subject FROM wD_ForumMessages WHERE id = '.$toID);
			preg_match_all ('/gameID[:= _]?([0-9]+)/i', $search, $gameID);
			if (empty ($gameID[1]))
				$anonIDs = 0;
			else
			{
				$gameIDs = array_unique($gameID[1]);
				list($anonIDs)=$DB->sql_row('
					SELECT count(*) FROM wD_Games g LEFT JOIN wD_Members m ON (m.gameID = g.id)
						WHERE phase != "Finished"
						AND anon="Yes"
						AND g.id IN ('.implode (",", $gameIDs).')
						AND m.userID='.$fromUserID);
			}
		}
		
		return (($anonIDs > 0)?'Yes':'No');
	}
	
	/**
	 * Send a message to the public forum. The variables passed are assumed to be already sanitized
	 *
	 * @param int $toID User/Thread ID to send to
	 * @param int $fromUserID UserID sent from
	 * @param string $message The message to be sent
	 * @param string[optional] $subject The subject
	 * @param string[optional] $type 'Bulletin'(GameMaster->Player) 'ThreadStart'(User->All) 'ThreadReply'(User->Thread)
	 *
	 * @return int The message ID
	 */
	static public function send($toID, $fromUserID, $message, $subject="", $type='Bulletin')
	{
		global $DB;

		// Disabled for testing
		// if( defined('AdminUserSwitch') ) $fromUserID = AdminUserSwitch;

		$message = self::linkify($message);

		$sentTime=time();

		if( 65000 < strlen($message) )
		{
			throw new Exception(l_t("Message too long"));
		}

		$DB->sql_put("INSERT INTO wD_ForumMessages
						SET toID = ".$toID.", fromUserID = ".$fromUserID.", timeSent = ".$sentTime.",
						message = '".$message."', subject = '".$subject."', replies = 0,
						anon = '".self::check_anon($toID, $fromUserID, $message, $subject)."',
						type = '".$type."', latestReplySent = 0");

		$id = $DB->last_inserted();

		if ( $type == 'ThreadReply' )
			$DB->sql_put("UPDATE wD_ForumMessages ".
				"SET latestReplySent = ".$id.", replies = replies + 1 WHERE ( id=".$id." OR id=".$toID." )");
		else
			$DB->sql_put("UPDATE wD_ForumMessages SET latestReplySent = id WHERE id = ".$id);

		self::updateForumCache($fromUserID);

		return $id;
	}

	static public function delete($postID) 
	{
		global $DB;

		$postToDelete = self::getPost($postID);

		$DB->sql_put("DELETE FROM wD_ForumMessages
						WHERE id = ".$postID." OR toID = ".$postID);

		if( $postToDelete['type'] == 'ThreadReply' )
		{
		 	list($lastReplyID) = $DB->sql_row("SELECT id FROM wD_ForumMessages WHERE toID = ".$postToDelete['toID']." OR id = ".$postToDelete['toID']." ORDER BY timeSent DESC LIMIT 1");
			$DB->sql_put("UPDATE wD_ForumMessages ".
				"SET latestReplySent = ".$lastReplyID.", replies = replies - 1 WHERE ( id=".$postToDelete['toID']." )");
		}

		self::updateForumCache($postToDelete['fromUserID']);

		return $postToDelete;
	}

	static public function getPost($postID)
	{
		global $DB;

		return $DB->sql_hash("SELECT toID, type, fromUserID, message, subject FROM wD_ForumMessages WHERE id = ".$postID);

	}

	static public function getUserThreads($userID)
	{
		global $DB;
		
		$userThreads = array();

		$res = $DB->sql_tabl("SELECT id, subject FROM wD_ForumMessages WHERE fromUserID = ".$userID." and type = 'ThreadStart'");
		while( $row = $DB->tabl_hash($res) ){
			$userThreads[] = $row;
		}  

		return $userThreads;
	}

	static private function updateForumCache($fromUserID) 
	{
		global $DB;

		libCache::wipeDir(libCache::dirName('forum'));

		$tabl=$DB->sql_tabl("SELECT t.id FROM wD_ForumMessages t LEFT JOIN wD_ForumMessages r ON ( r.toID=t.id AND r.fromUserID=".$fromUserID." AND r.type='ThreadReply' ) WHERE t.type='ThreadStart' AND ( t.fromUserID=".$fromUserID." OR r.id IS NOT NULL ) GROUP BY t.id");
		$participatedThreadIDs=array();
		while(list($participatedThreadID)=$DB->tabl_row($tabl)) {
			$participatedThreadIDs[$participatedThreadID] = $participatedThreadID;
		}

		$cacheUserParticipatedThreadIDsFilename = libCache::dirID('users',$fromUserID).'/readThreads.js';

		file_put_contents($cacheUserParticipatedThreadIDsFilename, 'participatedThreadIDs = $A(['.implode(',',$participatedThreadIDs).']);');
	}

	/**
	 * Remove any HTML added to a message
	 * @param $message The message to filter
	 * @return string The filtered message
	 */
	static function refilterHTML($message)
	{
		$patterns = array(
				'/<[^>]+>/i',
				'/<[^>]+$/i'
			);
		$replacements = array(
				' ',
				' '
			);

		return preg_replace($patterns, $replacements, $message);
	}
}



?>
