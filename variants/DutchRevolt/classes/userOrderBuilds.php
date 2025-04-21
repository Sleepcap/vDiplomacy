<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class DutchRevoltVariant_userOrderBuilds extends userOrderBuilds
{
	public function __construct($orderID, $gameID, $countryID)
	{
		parent::__construct($orderID, $gameID, $countryID);
	}

	protected function toTerrIDCheck()
	{

		$turn = $GLOBALS['Variants'][VARIANTID]->turn;			
		
		if (($turn == 0) && ($this->countryID == 1)) {
			if ($this->type == 'Build Army')
				return false;
			if (($this->toTerrID == 1) || ($this->toTerrID  == 4) ||  ($this->toTerrID == 5 ))
				return true;
			else
				return false;
		} 
		elseif ( $this->type == 'Destroy' && $this->toTerrID == 9 )
			return true;
		else
			return parent::toTerrIDCheck();
	}
}

?>
