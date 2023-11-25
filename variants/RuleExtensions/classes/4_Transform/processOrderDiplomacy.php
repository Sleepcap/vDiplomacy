<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class Transform_processOrderDiplomacy extends RuleExtensionsVariant_processOrderDiplomacy_base
{
	public function apply($standoffTerrs)
	{
		global $Game, $DB;

		if(!$this->Variant->rules[RULE_TRANSFORM]){
			return parent::apply($standoffTerrs);
		}

		// Transform all sucessfull "Transformations":
		$DB->sql_put("UPDATE wD_Units u 
					INNER JOIN wD_Orders o ON (o.unitID = u.id)
					INNER JOIN wD_Moves  m ON (m.gameID=o.gameID AND m.orderID = o.id)
			SET u.type = IF(u.type='Fleet','Army','Fleet'), u.terrID = (o.toTerrID - 1000)
			WHERE o.type='Support hold' AND m.success='Yes' AND o.toTerrID>1000
			AND u.id = o.unitID AND o.gameID = ".$Game->id);
		parent::apply($standoffTerrs);
	}

}

?>