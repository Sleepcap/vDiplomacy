<?php

class libBlockUser {
	
	static function BlockUserHTML()
	{
		global $DB, $UserProfile, $User;
		if ( $User->type['User'] && $UserProfile->type['User'] && ! ( $User->id == $UserProfile->id || $UserProfile->type['Guest'] || $UserProfile->type['Admin'] ) )
		{
			$userBlocked = $User->isUserBlocked($UserProfile->id);

			print ' / <a name="block"></a>';
			if( isset($_REQUEST['toggleBlock'])) {
				$User->toggleUserBlock($UserProfile->id);
				$userBlocked = !$userBlocked;
			}
			$blockURL = 'profile.php?userID='.$UserProfile->id.'&toggleBlock=on&rand='.rand(0,99999).'#block';
			print ' '.($userBlocked ? libHTML::blocked($blockURL) : libHTML::unblocked($blockURL));
		}
	}

	static function blockUserProfileInfo()
	{
		global $DB, $UserProfile;
		print '<li>&nbsp;</li>';

		$tabl = $DB->sql_tabl("SELECT b.blockUserID, u.username FROM wD_BlockUser b LEFT JOIN wD_Users u ON (b.blockUserID = u.id) WHERE b.userID = ".$UserProfile->id);
		if ($DB->last_affected() != 0)
		{
			print '<li><strong>'.l_t('Blocklist').':</strong>';
			print '<ul class="gamesublist">';
			while(list($blockUserID,$name)=$DB->tabl_row($tabl))
				print '<li><a href="profile.php?userID='.$blockUserID.'">'.$name.'</a></li>';
			print '</ul>';
		}
		$tabl = $DB->sql_tabl("SELECT b.userID, u.username FROM wD_BlockUser b LEFT JOIN wD_Users u ON (b.userID = u.id) WHERE b.blockUserID = ".$UserProfile->id);
		if ($DB->last_affected() != 0)
		{
			print '<li><strong>'.l_t('Blocked by').':</strong>';
			print '<ul class="gamesublist">';
			while(list($blockUserID,$name)=$DB->tabl_row($tabl))
				print '<li><a href="profile.php?userID='.$blockUserID.'">'.$name.'</a></li>';
			print '</ul>';
		}
		print '</ul>';
	}
}