/*
 * Disables Spanish fleet build in Spain.
 * 
 * Only loaded in builds phase and by Spain.
 */
function loadOnlyFleets(ids) {
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
				if (MyOrders[0].type == "Destroy") {
					if (interactiveMap.currentOrder != null) {
						interactiveMap.interface.orderMenu.element.show();
					}
				} else {
					var SupplyCenter = SupplyCenters.detect(function(sc){return sc.id == interactiveMap.selectedTerritoryID});
					if ((!Object.isUndefined(SupplyCenter)) && (!interactiveMap.isUnitIn(interactiveMap.selectedTerritoryID))) {
						if (ids.inArray(SupplyCenter.coastParent.id))
							interactiveMap.interface.orderMenu.hideElement($("imgBuildArmy"));
						else if (SupplyCenter.type != "Coast")
							interactiveMap.interface.orderMenu.hideElement($("imgBuildFleet"));
						interactiveMap.interface.orderMenu.element.show();
					}
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
                    alert("No own empty supply center selected (" + Territories.get(terrID).name + ")!");
                    interactiveMap.abortOrder();
                    return;
                }
                if (value == "Build Fleet") {
                    if (Territories.get(terrID).type != "Coast") {
                        alert("No coastal supply center selected (" + Territories.get(terrID).name + ")!");
                        interactiveMap.abortOrder();
                        return;
                    }
                    if (Territories.get(terrID).coast != "No")
                        terrID = this.getCoastByCoords(SupplyCenters.select(function(sc) {
                            return (sc.coastParentID == terrID) && (sc.id != sc.coastParentID)
                        }), this.coordinates).id;
                }
				if (value == "Build Army" && ids.inArray(terrID)) {
					alert("Spain not allowed for army builds!");
                    interactiveMap.abortOrder();
                    return;
				}
                this.enterOrder('type', value);
                this.enterOrder('toTerrID', terrID);
                return;
            }

            if (value == "Destroy") {
                var terrID = interactiveMap.selectedTerritoryID;
                if (!this.isOwnUnitIn(terrID)) {
                    interactiveMap.errorMessages.noOwnUnit(terrID);
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