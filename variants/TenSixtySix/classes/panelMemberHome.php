<?php
/*
	Copyright (C) 2011 Oliver Auth

	This file is part of the 1066 variant for webDiplomacy

	The 1066 variant for webDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License 
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The 1066 variant for webDiplomacy is distributed in the hope that it will be
	useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.		
*/

defined('IN_CODE') or die('This script can not be run by itself.');

class Fog_panelMemberHome extends panelMemberHome
{
	// Hide the finalized Check, so nobody can guess what players need to enter orders in a given phase
	function memberFinalized()
	{
		global $User;
		if ($this->status!='Playing' ) return '';
		if (($this->userID == $User->id) || ( $User->type['Admin'] && !$this->Game->Members->isJoined() )) return parent::memberFinalized();
		return '<span class="member'.$this->id.'StatusIcon"><img src="variants/'.$this->Game->Variant->name.'/resources/question.png" alt="?" title="Unknown orderstatus" /></span>';
	}
	
	function memberFinalizedAnon()
	{
		global $User;
		if( $this->status!='Playing' ) return '';
		if (($this->userID == $User->id) || ( $User->type['Admin'] && !$this->Game->Members->isJoined() )) return parent::memberFinalizedAnon();
		return '<span class="member'.$this->id.'StatusIcon"><img src="variants/'.$this->Game->Variant->name.'/resources/question.png" alt="?" title="Unknown orderstatus" /></span>';
	}
}

class TenSixtySixVariant_panelMemberHome extends Fog_panelMemberHome {}

