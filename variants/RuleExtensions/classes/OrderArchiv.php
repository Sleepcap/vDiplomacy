<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class RuleExtensionsVariant_OrderArchiv_base extends OrderArchiv
{
	public $Variant;

	public function __construct($Variant, $smallmap)
	{
		$this->Variant = $Variant;

		parent::__construct($smallmap);
	}
}

require_once('4_Transform/OrderArchiv.php');

class RuleExtensionsVariant_OrderArchiv  extends Transform_OrderArchiv {}

?>