transSibTerritories = ['28','29','30','35','39','40'];

interactiveMap.parameters.imgTSR = 'variants/Colonial/interactiveMap/TSR.png';
interactiveMap.parameters.imgSuez = 'variants/Colonial/interactiveMap/Suez.png';

interactiveMap.interface.createOrderButtons = function() {
    var orderButtons = new Element('div',{'id':'orderButtons'});
    switch (context.phase) {
        case "Diplomacy":
            orderButtons.appendChild(new Element('button', {'id': 'hold', 'class':'buttonIA form-submit', 'onclick': 'interactiveMap.sendOrder("Hold")', 'disabled': 'true'}).update("HOLD"));
            orderButtons.appendChild(new Element('button', {'id': 'move', 'class':'buttonIA form-submit', 'onclick': 'interactiveMap.sendOrder("Move")', 'disabled': 'true'}).update("MOVE"));
            orderButtons.appendChild(new Element('button', {'id': 'sHold', 'class':'buttonIA form-submit', 'onclick': 'interactiveMap.sendOrder("Support hold")', 'disabled': 'true'}).update("SUPPORT HOLD"));
            orderButtons.appendChild(new Element('button', {'id': 'sMove', 'class':'buttonIA form-submit', 'onclick': 'interactiveMap.sendOrder("Support move")', 'disabled': 'true'}).update("SUPPORT MOVE"));
            orderButtons.appendChild(new Element('button', {'id': 'convoy', 'class':'buttonIA form-submit', 'onclick': 'interactiveMap.sendOrder("Convoy")', 'disabled': 'true'}).update("CONVOY"));
            
            //added
            if(context.countryID == "6")
                orderButtons.appendChild(new Element('button', {'id': 'tsr', 'class':'buttonIA form-submit', 'onclick': 'interactiveMap.sendOrder("TSR")', 'disabled': 'true'}).update("TSR"));
            if(!Object.isUndefined(MyOrders.find(function(order){return order.Unit.terrID === "126";})))
                orderButtons.appendChild(new Element('button', {'id': 'suez', 'class':'buttonIA form-submit', 'onclick': 'interactiveMap.sendOrder("Suez")', 'disabled': 'true'}).update("USE SUEZ"));
            
            break;
        case "Builds":
            if (MyOrders.length == 0) {
                orderButtons.appendChild(new Element('p').update("No orders this phase!"));
            } else if (MyOrders[0].type == "Destroy") {
                orderButtons.appendChild(new Element('button', {'id': 'destroy', 'class':'buttonIA form-submit', 'onclick': 'interactiveMap.sendOrder("Destroy")', 'disabled': 'true'}).update("DESTROY"));
            } else {
                orderButtons.appendChild(new Element('button', {'id': 'buildArmy', 'class':'buttonIA form-submit', 'onclick': 'interactiveMap.sendOrder("Build Army")', 'disabled': 'true'}).update("BUILD "+interactiveMap.parameters.armyName.toUpperCase()));
                orderButtons.appendChild(new Element('button', {'id': 'buildFleet', 'class':'buttonIA form-submit', 'onclick': 'interactiveMap.sendOrder("Build Fleet")', 'disabled': 'true'}).update("BUILD "+interactiveMap.parameters.fleetName.toUpperCase()));
                orderButtons.appendChild(new Element('button', {'id': 'wait', 'class':'buttonIA form-submit', 'onclick': 'interactiveMap.sendOrder("Wait")', 'disabled': 'true'}).update("WAIT"));
            }
            break;
        case "Retreats":
            if (MyOrders.length == 0) {
                orderButtons.appendChild(new Element('p').update("No orders this phase!"));
            } else {
                orderButtons.appendChild(new Element('button', {'id': 'retreat', 'class':'buttonIA form-submit', 'onclick': 'interactiveMap.sendOrder("Retreat")', 'disabled': 'true'}).update("RETREAT"));
                orderButtons.appendChild(new Element('button', {'id': 'disband', 'class':'buttonIA form-submit', 'onclick': 'interactiveMap.sendOrder("Disband")', 'disabled': 'true'}).update("DISBAND"));
            }
    }
    return orderButtons;
};


var origCreate = interactiveMap.interface.orderMenu.create;

/*
 * creates the menu that appears when a user clicks on the map
 */
interactiveMap.interface.orderMenu.create = function() {	
	if (typeof interactiveMap.interface.orderMenu.element == "undefined") { 
		origCreate();
			
		if(context.phase == "Diplomacy"){
			interactiveMap.interface.orderMenu.createButtonSet('TSR','Trans-Siberian Railroad (TSR)');
			interactiveMap.interface.orderMenu.createButtonSet('Suez','use Suez Canal');
		}
	}   
};

/*
 * adds the needed options and make the orderMenu visible
 */
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
		switch (context.phase) {
			case 'Builds':
				if (MyOrders.length != 0) {
					if (MyOrders[0].type == "Destroy") {
						if (interactiveMap.currentOrder != null) {
							interactiveMap.interface.orderMenu.element.show();
						}
					} else {
						var SupplyCenter = SupplyCenters.detect(function(sc){return sc.id == interactiveMap.selectedTerritoryID});
						if ((!Object.isUndefined(SupplyCenter)) && (!interactiveMap.isUnitIn(interactiveMap.selectedTerritoryID))) {
							if (SupplyCenter.type != "Coast")
								interactiveMap.interface.orderMenu.hideElement($("imgBuildFleet"));
							else
								interactiveMap.interface.orderMenu.showElement($("imgBuildFleet"));
							interactiveMap.interface.orderMenu.element.show();
						}
					}
				}
				break;
			case 'Diplomacy':
				if (interactiveMap.currentOrder != null) {//||(unit(interactiveMap.selectedTerritoryID)&&(Territories.get(interactiveMap.selectedTerritoryID).type=="Coast")&&(Territories.get(interactiveMap.selectedTerritoryID).Unit.type=="Army")))
					if ((interactiveMap.currentOrder.Unit.type == "Fleet") || (Territories.get(interactiveMap.selectedTerritoryID).type != "Coast"))
						interactiveMap.interface.orderMenu.hideElement($("imgConvoy"));
					
					//added
                    if ((interactiveMap.currentOrder.Unit.type == "Fleet") || interactiveMap.currentOrder.Unit.countryID != "6" || !transSibTerritories.include(interactiveMap.selectedTerritoryID))
                            interactiveMap.interface.orderMenu.hideElement($("imgTSR"));
                    
                    if(!['99','101'].include(interactiveMap.selectedTerritoryID) || Object.isUndefined(MyOrders.find(function(order){return order.Unit.terrID === "126";})))
                            interactiveMap.interface.orderMenu.hideElement($("imgSuez"));
						
					interactiveMap.interface.orderMenu.element.show();
				} else {
					if ((Territories.get(interactiveMap.selectedTerritoryID).type == "Coast") && !Object.isUndefined(Territories.get(interactiveMap.selectedTerritoryID).Unit) && (Territories.get(interactiveMap.selectedTerritoryID).Unit.type == "Army")) {
						interactiveMap.interface.orderMenu.hideElement($("imgMove"));
						interactiveMap.interface.orderMenu.hideElement($("imgHold"));
						interactiveMap.interface.orderMenu.hideElement($("imgSupportmove"));
						interactiveMap.interface.orderMenu.hideElement($("imgSupporthold"));
                        interactiveMap.interface.orderMenu.hideElement($("imgTSR"));
                        interactiveMap.interface.orderMenu.hideElement($("imgSuez"));
						interactiveMap.interface.orderMenu.showElement($("imgConvoy"));
						interactiveMap.interface.orderMenu.element.show();
					}
					
					//added
                    else if (['99','101'].include(interactiveMap.selectedTerritoryID) && !Object.isUndefined(Territories.get(interactiveMap.selectedTerritoryID).Unit) && !Object.isUndefined(MyOrders.find(function(order){return order.Unit.terrID === "126";}))){
                        interactiveMap.interface.orderMenu.hideElement($("imgMove"));
                        interactiveMap.interface.orderMenu.hideElement($("imgHold"));
                        interactiveMap.interface.orderMenu.hideElement($("imgSupportmove"));
                        interactiveMap.interface.orderMenu.hideElement($("imgSupporthold"));
                        interactiveMap.interface.orderMenu.hideElement($("imgTSR"));
                        interactiveMap.interface.orderMenu.showElement($("imgSuez"));
                        interactiveMap.interface.orderMenu.hideElement($("imgConvoy"));
                        interactiveMap.interface.orderMenu.element.show();    
                    }
				}
				break;
			case 'Retreats':
				if (MyOrders.length != 0) {
					if (interactiveMap.currentOrder != null)
						interactiveMap.interface.orderMenu.element.show();
				}
				break;
		}
		
	} 
    
	this.positionMenu(coor);
	this.toggle(true);
};