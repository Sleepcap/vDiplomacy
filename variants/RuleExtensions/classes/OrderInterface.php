<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class RuleExtensionsVariant_OrderInterface_base extends OrderInterface 
{
	public $Variant;

	public function __construct($Variant, $gameID, $variantID, $userID, $memberID, $turn, $phase, $countryID,
	setMemberOrderStatus $orderStatus, $tokenExpireTime, $maxOrderID=false)
	{
		$this->Variant = $Variant;

		parent::__construct($gameID, $variantID, $userID, $memberID, $turn, $phase, $countryID, $orderStatus, $tokenExpireTime, $maxOrderID);
	}
}

require_once('3_BuildAnywhere/OrderInterface.php');

class RuleExtensionsVariant_OrderInterface extends BuildAnywhere_OrderInterface {}