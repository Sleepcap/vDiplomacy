<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class RuleExtensionsVariant_processOrderBuilds_base extends processOrderBuilds 
{
	public $Variant;

	public function __construct($Variant)
	{
		$this->Variant = $Variant;
	}
}

require_once('3_BuildAnywhere/processOrderBuilds.php');

class RuleExtensionsVariant_processOrderBuilds extends BuildAnywhere_processOrderBuilds {}

?>