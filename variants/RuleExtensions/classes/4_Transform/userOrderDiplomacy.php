<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class Transform_userOrderDiplomacy extends RuleExtensionsVariant_userOrderDiplomacy_base
{
	// Allow the transform command
	protected function typeCheck()
	{
		if(!$this->Variant->rules[RULE_TRANSFORM]){
			return parent::typeCheck();
		}

		if (strrpos($this->type,'Transform_1') !== false) return true;
		return parent::typeCheck();	
	}	
	
	// Save the transform command as a Support-hold
	public function commit()
	{
		if(!$this->Variant->rules[RULE_TRANSFORM]){
			return parent::commit();
		}

		// Clear the toTerrID (if there is any) from the transform command
		if ($this->type=='Hold')
			$this->paramWipe('toTerrID');
	
		if (strrpos($this->type,'Transform_1') !== false)
		{
			$this->toTerrID = substr($this->type, -4);
			$this->wiped = array('fromTerrID','viaConvoy');
			$this->changed = array('type','toTerrID');
			$this->type='Support hold';		
		}
		return parent::commit();
	}

	public function loadFromDB(array $inputs)
	{
		if(!$this->Variant->rules[RULE_TRANSFORM]){
			parent::loadFromDB($inputs);
		}

		if( isset($inputs['toTerrID']) && $inputs['toTerrID'] >  1000 )
		{
			$inputs['type'] = 'Transform_' . $inputs['toTerrID'];
			unset($inputs['toTerrID']);	
		}
		parent::loadFromDB($inputs);
	}

	public function loadFromInput(array $inputs)
	{
		if(!$this->Variant->rules[RULE_TRANSFORM]){
			parent::loadFromInput($inputs);
		}

		if( isset($inputs['toTerrID']) && $inputs['toTerrID'] >  1000)
		{
			$inputs['type'] = 'Transform_' . $inputs['toTerrID'];
			unset($inputs['toTerrID']);	
		}
		parent::loadFromInput($inputs);
	}
}