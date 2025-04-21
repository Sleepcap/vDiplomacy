<?php


class NoSupportAroundSouthAfrica_userOderDiplomacy extends userOrderDiplomacy{
	protected function supportAroundSouthAfrica(){
		$egyptHejaz = array(Baron1900Variant::$hejazID,Baron1900Variant::$egyptID);
		
		return ( $this->Unit->terrID == Baron1900Variant::$MidAtlanticOceanID && in_array($this->toTerrID,$egyptHejaz) )
				|| ( in_array($this->Unit->terrID,$egyptHejaz) && $this->toTerrID == Baron1900Variant::$MidAtlanticOceanID );
	}
	
	protected function supportHoldToTerrCheck(){
		$result = parent::supportHoldToTerrCheck();
		
		if($this->supportAroundSouthAfrica())
			return false;
		else 
			return $result;
	}
	
	protected function supportMoveToTerrCheck(){
		$result = parent::supportMoveToTerrCheck();
		
		if($this->supportAroundSouthAfrica())
			return false;
		else 
			return $result;
	}
}

class Baron1900Variant_userOrderDiplomacy extends NoSupportAroundSouthAfrica_userOderDiplomacy {}
