function SupportFog() {
	
		UnitClass.addMethods( {	
		
			// Can I cross a given Border
			canCrossBorder : function (b) {
				if( this.id < 1000 )
					return true;
				if( this.type == 'Army' && !b.a ) 
					return false;
				else if( this.type == 'Fleet' && !b.f ) 
					return false;
				else 
					return true;
			},
			
			
			/*
			 * Modified this method to use Borders instead of CoastalBorders if countryID = 0.
			 * 
			 * 
			 * If there is a (fake) unit in a foggy territory with several coasts, 
			 * Borders shoul be used instead of CoastalBorders as there could be 
			 * a fleet on one of the coasts or an army on the main territory and
			 * CoastalBorders does only include the real borders for the current
			 * subterritory.
			 * 
			 * For real units CoastalBorders still needs to be used to avoid
			 * non-movable options from opposite coasts.
			 */
			getMovableTerritories: function () {

				if (Object.isUndefined(this.getMovableTerritoriesCache)){
					if(this.countryID == 0)
						this.getMovableTerritoriesCache = this.Territory.Borders
								.select(this.canCrossBorder, this).pluck('id').compact()
								.map(function (n) {
									return Territories.get(n);
								}, this)
								.sort(function (a, b) {
									return a.name > b.name;
								});
					else
						this.getMovableTerritoriesCache = this.Territory.CoastalBorders
								.select(this.canCrossBorder, this).pluck('id').compact()
								.map(function (n) {
									return Territories.get(n);
								}, this)
								.sort(function (a, b) {
									return a.name > b.name;
								});
				}

				return this.getMovableTerritoriesCache;
			}
		
		});
};