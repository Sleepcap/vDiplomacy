function RetreatAsArmy() {
	
		UnitClass.addMethods( {	
		
			// Can I cross a given Border (only show Army-moves)
			canCrossBorder : function (b) {
				if( !b.a ) 
					return false;
				else 
					return true;
			}
		
		});
};