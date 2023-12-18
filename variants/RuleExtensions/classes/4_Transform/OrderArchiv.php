<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class Transform_OrderArchiv extends RuleExtensionsVariant_OrderArchiv_base {

	public function OutputOrder($order)
	{
		if(!$this->Variant->rules[RULE_TRANSFORM]){
			return parent::OutputOrder($order);
		}

		if ($order['toTerrID'] > 1000)
		{
			$order['toTerrID'] = $order['toTerrID'] - 1000;
			$order['type'] = 'transform';
			if ($order['toTerrID'] == $order['terrID'])
				$order['toTerrID']=false;		
		}
		return parent::OutputOrder($order);
	}

}