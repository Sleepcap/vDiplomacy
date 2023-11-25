<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class RuleExtensionsVariant_processOrderDiplomacy_base extends processOrderDiplomacy
{
	public $Variant;

	public function __construct($Variant)
	{
		$this->Variant = $Variant;
	}
}

require_once('4_Transform/processOrderDiplomacy.php');

class RuleExtensionsVariant_processOrderDiplomacy extends Transform_processOrderDiplomacy {}

?>