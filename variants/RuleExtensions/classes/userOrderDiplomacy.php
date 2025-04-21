<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class RuleExtensionsVariant_userOrderDiplomacy_base extends userOrderDiplomacy 
{
	public $Variant;

	public function __construct($Variant, $orderID, $gameID, $countryID)
	{
		$this->Variant = $Variant;

		parent::__construct($orderID, $gameID, $countryID);
	}
}

require_once('4_Transform/userOrderDiplomacy.php');

class RuleExtensionsVariant_userOrderDiplomacy extends Transform_userOrderDiplomacy {}