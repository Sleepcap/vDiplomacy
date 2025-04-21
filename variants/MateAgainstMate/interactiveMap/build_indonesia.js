/*
 * Enforce that a "build fleet" of Indonesia in Jakarta is always selected for the north coast.
 * 
 * Script should only be called for Indonesia in Build phase.
 */

function loadCustomBuildIndonesia() {

	// if setOrder is called for Jakarta (id = 1) with orderType "Build Fleet" 
	// change coordinates to north coast's coordinates so north coast (id = 32) will be 
	// automatically selected
	MyOrders.map(function(o) {
		var IA = o.interactiveMap;
		
		var origSetOrder = IA.setOrder;
		
		IA.setOrder = function(value) {
            if (interactiveMap.selectedTerritoryID == 1 && value == "Build Fleet")
				this.coordinates = {
					'x': Territories.get(32).smallMapX,
					'y': Territories.get(32).smallMapY
				};
			
			origSetOrder.bind(this)(value);
		};
	});

};	
