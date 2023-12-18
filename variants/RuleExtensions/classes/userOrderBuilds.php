<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class RuleExtensionsVariant_userOrderBuilds_base extends userOrderBuilds 
{
	public $Variant;

	public function __construct($Variant, $orderID, $gameID, $countryID)
	{
		$this->Variant = $Variant;

		parent::__construct($orderID, $gameID, $countryID);
	}
}

require_once('3_BuildAnywhere/userOrderBuilds.php');

class RuleExtensionsVariant_userOrderBuilds extends BuildAnywhere_userOderBuilds {}