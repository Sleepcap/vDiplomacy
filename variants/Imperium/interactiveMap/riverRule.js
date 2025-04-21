/* 
 * Enforces river rule (no supports accross river). Adjustments must only be made
 * for Support hold due to inconsistent use of given methods for the IA checks
 */
function loadRiverRule(){
	MyOrders.map(function(o) {
		var IA = o.interactiveMap;
		
		// additional checks Support Hold
		
		var origSetSupportHold = IA.setSupportHold;
		
		IA.setSupportHold = function(terrID) {
			if (!o.Unit.getSupportHoldChoices().include(terrID)) {
                alert(((o.Unit.type == "Army") ? interactiveMap.parameters.armyName : interactiveMap.parameters.fleetName) + " in " + o.Unit.Territory.name + " cannot support unit in " + Territories.get(terrID).name + " (not adjacent / wrong unit type)");
                return;
            }
			
			origSetSupportHold.bind(this)(terrID);
		};
	});
}


