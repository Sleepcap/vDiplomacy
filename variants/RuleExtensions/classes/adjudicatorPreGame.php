<?php

defined('IN_CODE') or die('This script can not be run by itself.');

// by default we extend the classic variant for loading starting positions on the default map
class RuleExtensionsVariant_adjudicatorPreGame_base extends ClassicVariant_adjudicatorPreGame
{
	public $Variant;

	public function __construct($Variant)
	{
		$this->Variant = $Variant;
	}
}

class RuleExtensionsVariant_adjudicatorPreGame  extends RuleExtensionsVariant_adjudicatorPreGame_base {}

?>