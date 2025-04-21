function SupplyCentersCorrect() {
	SupplyCenters = new Array();
	
	/*
         * Based on the code from the BuildAnywhere variant.
         * 
	 * In javascript/board/load.js SupplyCenters is created using similar code but only with home SCs, 
	 * replacing it here allows them to be selected. A modified OrderInterface calls this shortly after load.js.
	 */
	Territories.each(function(p){
		var t=p[1];
		if( t.coastParent.supply && 
                    t.coastParent.ownerCountryID == context.countryID && 
                    Object.isUndefined(t.coastParent.Unit) &&
                    (t.buildEligibilityFlags & (1 << context.countryID)) != 0
                  )
		{
			SupplyCenters.push(t);
		}
	},this);
}