<?php

class BuildBasedOnFlagsVariant_OrderInterface extends OrderInterface {

	/**
	 * Call the parent constructor transparently to keep things working
	 */
	public function __construct($gameID, $variantID, $userID, $memberID, $turn, $phase, $countryID,
		setMemberOrderStatus $orderStatus, $tokenExpireTime, $maxOrderID=false)
	{
		parent::__construct($gameID, $variantID, $userID, $memberID, $turn, $phase, $countryID,
			$orderStatus, $tokenExpireTime, $maxOrderID);
	}

	protected function jsLoadBoard() {
                global $Variant;
                parent::jsLoadBoard();

		if( $this->phase=='Builds' )
		{
			// Replace the allowed SupplyCenters array with SCs whose bitflags match that power.
			libHTML::$footerIncludes[] = l_jf('../variants/'.$Variant->name.'/resources/buildInFlaggedSCs.js');
			foreach(libHTML::$footerScript as $index=>$script)
                            libHTML::$footerScript[$index]=str_replace('loadBoard();','loadBoard();SupplyCentersCorrect();', $script);
		}
	}


}

class Baron1900Variant_OrderInterface extends BuildBasedOnFlagsVariant_OrderInterface 
{
    /**
    * Call the parent constructor transparently to keep things working
    */
    public function __construct($gameID, $variantID, $userID, $memberID, $turn, $phase, $countryID,
            setMemberOrderStatus $orderStatus, $tokenExpireTime, $maxOrderID=false)
    {
            parent::__construct($gameID, $variantID, $userID, $memberID, $turn, $phase, $countryID,
                    $orderStatus, $tokenExpireTime, $maxOrderID);
    }
    
    protected function jsLoadBoard() {
        
        parent::jsLoadBoard();
        global $Variant;
        global $DB;
        
        if( $this->phase=='Builds' && $this->countryID == 6 && $this->russianEMRImpact() == 1)
        {
                // Replace the allowed SupplyCenters array with SCs whose bitflags match that power.
                libHTML::$footerIncludes[] = l_jf('../variants/'.$Variant->name.'/resources/buildInSiberiaToo.js');
                foreach(libHTML::$footerScript as $index=>$script)
                    libHTML::$footerScript[$index]=str_replace('loadBoard();','loadBoard();SupplyCentersCorrect();', $script);
        }
        else if ( $this->phase=='Diplomacy' )
        {
            libHTML::$footerIncludes[] = '../variants/'.$Variant->name.'/resources/noSupportSouthAfrica.js';
            foreach(libHTML::$footerScript as $index=>$script)
            {
                libHTML::$footerScript[$index]=str_replace('loadBoard();',
                        'loadBoard();noSupportSouthAfrica('.Baron1900Variant::$hejazID.','.Baron1900Variant::$egyptID.','.Baron1900Variant::$MidAtlanticOceanID.');', $script);
            }
        }
        else
        {
            
        }
    }
    
    protected function russianEMRImpact()
    {
        global $DB;
        list($howManyRussianHome)=$DB->sql_row(
                "SELECT count(*)
                FROM wD_TerrStatus
                WHERE gameID=".$this->gameID." AND 
                terrID IN(86,91,92,97) AND 
                countryID=6");

        if ($howManyRussianHome > 0 && $howManyRussianHome < 4)
        {
            return 1;
        }
        return 0;
    }
}