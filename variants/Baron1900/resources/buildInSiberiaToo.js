function SupplyCentersCorrect() {
	SupplyCenters = new Array();
	
	/*
         * A special version of SupplyCentersCorrect for Russian EMR conditions.
	 */
	Territories.each(function(p){
		var t=p[1];
		if( Object.isUndefined(t.coastParent.Unit) &&
                    ((t.coastParent.supply && 
                      t.coastParent.ownerCountryID == context.countryID && 
                      (t.buildEligibilityFlags & (1 << context.countryID)) != 0)
                      ||
                      t.id == 54)
                  )
		{
			SupplyCenters.push(t);
		}
	},this);
}