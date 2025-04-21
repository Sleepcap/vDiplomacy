/*
 * Enables "Build Fleet" icons for England's special Sea territories.
 * 
 * Only loaded in first builds phase and by England.
 */
function loadEnglandCustomStart(){
	interactiveMap.interface.orderMenu.show = function(coor, drawResetButton) {
		/*
		 * If current coordinates for display of the order menu are given, use these.
		 * If no coordinates are given, use the last coordinates given.
		 */
		if(Object.isUndefined(coor))
			coor = {x:new Number(interactiveMap.currentOrder.Unit.Territory.smallMapX), y:new Number(interactiveMap.currentOrder.Unit.Territory.smallMapY)};

		/*
		 * Draw a complete set of order buttons by default 
		 */
		if(Object.isUndefined(drawResetButton)){
			drawResetButton = false;
		}

		// first hide all order buttons from previous action
		interactiveMap.interface.orderMenu.hideAll();

		// draw a reset button or draw the complete order menu
		if (drawResetButton){
			// show the reset button corresponding to current order
			interactiveMap.interface.orderMenu.showElement($('imgReset'+interactiveMap.interface.orderMenu.getShortName(interactiveMap.currentOrder.interactiveMap.orderType)));

			interactiveMap.interface.orderMenu.element.show();

		} else {
			//show all order buttons that are activated for the current phase / situation
			interactiveMap.interface.orderMenu.showAllRegular();

			// make additional phase specific adjustments
			// case 'Builds':
			if (MyOrders.length != 0) {
				// case "Builds" (no "Destroys")
				var SupplyCenter = SupplyCenters.detect(function(sc){return sc.id == interactiveMap.selectedTerritoryID});
				if ((!Object.isUndefined(SupplyCenter))) {
					interactiveMap.interface.orderMenu.hideElement($("imgBuildArmy"));
					interactiveMap.interface.orderMenu.element.show();
				}
			}
				

		} 

		this.positionMenu(coor);
		this.toggle(true);
	};
	
	MyOrders.map(function(o) {
		var IA = o.interactiveMap;
		
		IA.setOrder = function(value) {
            if (this.orderType != null) {
                interactiveMap.errorMessages.uncompletedOrder();
                return;
            }
			
            this.orderType = value;
			// before entering order: store previous order in case process of entering
			// order is aborted
			this.previousOrder = {
				'type': this.Order.type,
				'toTerrID': this.Order.toTerrID,
				'fromTerrID': this.Order.fromTerrID,
				'viaConvoy': this.Order.viaConvoy
			};

            if ((value == "Build Army") || (value == "Build Fleet")) {
                var terrID = interactiveMap.selectedTerritoryID;
                if (!SupplyCenters.any(function(sc) {
                    return sc.id == terrID
                })) {
                    alert("No starting build territory selected (" + Territories.get(terrID).name + ")!");
                    interactiveMap.abortOrder();
                    return;
                }
                if (value == "Build Army") {
                    alert("Only fleets can be build on " + Territories.get(terrID).name + "!");
					interactiveMap.abortOrder();
                    return;
                }
                this.enterOrder('type', value);
                this.enterOrder('toTerrID', terrID);
                return;
            }

            this.enterOrder('type', value);
			
			if(!this.Order.isComplete)
				// display reset button if order is not completed
				interactiveMap.interface.orderMenu.show(undefined, true);
        };
	});
}