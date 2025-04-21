function SupplyCentersCorrect() {
	SupplyCenters = new Array();
	
	Territories.each(function(p){
		var t=p[1];
		if( t.coastParent.supply && t.coastParent.ownerCountryID == context.countryID && Object.isUndefined(t.coastParent.Unit) )
		{
			SupplyCenters.push(t);
		}
	},this);
}