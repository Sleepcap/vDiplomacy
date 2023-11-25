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

require_once('1_CustomIcons/OrderInterface.php');
require_once('2_CustomIconsPerCountry/OrderInterface.php');
require_once('3_BuildAnywhere/OrderInterface.php');
require_once('4_Transform/OrderInterface.php');

class RuleExtensionsVariant_OrderInterface extends Transform_OrderInterface {}