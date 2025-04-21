/*
 Copyright (C) 2013 Tobias Florin
 
 This file is part of the InterActive-Map mod for webDiplomacy
 
 The InterActive-Map mod for webDiplomacy is free software: you can
 redistribute it and/or modify it under the terms of the GNU Affero General
 Public License as published by the Free Software Foundation, either version
 3 of the License, or (at your option) any later version.
 
 The InterActive-Map mod for webDiplomacy is distributed in the hope
 that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
 warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 See the GNU General Public License for more details.
 
 You should have received a copy of the GNU Affero General Public License
 along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.
 */

interactiveMap.currentOrder = null;

/*
 * Basic function which is called after each order and if the RESET ORDER-button is used.
 * Resets all important variables, redraws the image, greys out territories and saves the last order.
 */

interactiveMap.resetOrder = function() {
    if (interactiveMap.currentOrder != null)
        interactiveMap.currentOrder.interactiveMap.resetOrder();
    interactiveMap.selectedTerritoryID = null;
    interactiveMap.currentOrder = null;
    
    if(interactiveMap.autosave && OrdersHTML.ordersChanged)
        OrdersHTML.sendUpdates('ajax.php'); //save immediately
    
    //interface (map, menu)
    interactiveMap.draw();
    if ((context.phase == "Builds") && (MyOrders.length != 0) && (MyOrders[0].type != "Destroy"))
        interactiveMap.greyOut.draw(SupplyCenters.select(function(n) {
            return MyOrders.pluck("ToTerritory").collect(function(t){if(!Object.isUndefined(t)) return t.coastParent;}).indexOf(n) == -1 && n.coast != "Child";
        }).pluck("id"));
    else if ((context.phase == "Retreats") && (MyOrders.length != 0))
        interactiveMap.greyOut.draw(MyOrders.pluck("Unit").pluck("terrID"));
    else
        interactiveMap.greyOut.draw();
    interactiveMap.interface.orderMenu.element.hide();
};

/* 
 * An aborted order is automatically set to previous order and all variables will be reset.
 */
interactiveMap.abortOrder = function() {
	if(interactiveMap.currentOrder != null && interactiveMap.currentOrder.interactiveMap.previousOrder != null){
		interactiveMap.currentOrder.interactiveMap.setAndShow('type', interactiveMap.currentOrder.interactiveMap.previousOrder.type);
		
		interactiveMap.currentOrder.requirements.each(function(req){
			if(req == "type") return;
			
			interactiveMap.currentOrder.interactiveMap.setAndShow(req, interactiveMap.currentOrder.interactiveMap.previousOrder[req]);
		});
	} 
	
	
	this.insertMessage(" ... (aborted)",false);
    this.resetOrder();
}

/*
 * Handles the actions after an order button is clicked.
 * 
 * @param {type} value
 */
interactiveMap.sendOrder = function(value) {
    if (interactiveMap.currentOrder != null) {
        interactiveMap.currentOrder.interactiveMap.setOrder(value);
    } else {
        if (interactiveMap.selectedTerritoryID == null)
            interactiveMap.errorMessages.noTerritory();
        else if (value == "Convoy") {
            var unit = Territories.get(interactiveMap.selectedTerritoryID).Unit;
            if (Object.isUndefined(unit))
                interactiveMap.errorMessages.noUnit(interactiveMap.selectedTerritoryID)
            else {
                unit.fakeOrder = Object.clone(MyUnits[0].Order); //give convoyed Unit the needed properties to enter the order
                unit.fakeOrder.interactiveMap = Object.clone(unit.fakeOrder.interactiveMap);    //Clone only shallow copy so interactiveMap has to be copied as well
                //
                unit.fakeOrder.convoyPath = new Array();
                unit.fakeOrder.fromTerrChoices = Object.clone(unit.fakeOrder.fromTerrChoices);
                unit.fakeOrder.messageSpan = null;
                unit.fakeOrder.orderSegs = null;
                unit.fakeOrder.requirements = Object.clone(unit.fakeOrder.requirements);
                unit.fakeOrder.toTerrChoices = Object.clone(unit.fakeOrder.toTerrChoices);
                unit.fakeOrder.typeChoices = Object.clone(unit.fakeOrder.typeChoices);
                unit.fakeOrder.unitIconArea = null;
                
                unit.fakeOrder.alterOrderSegment = function(){};
                //
                unit.fakeOrder.interactiveMap.Order = unit.fakeOrder;
                unit.fakeOrder.Unit = unit;

                interactiveMap.currentOrder = unit.fakeOrder;
                unit.fakeOrder.interactiveMap.setOrder(value);
            }
        } else
            interactiveMap.errorMessages.noOwnUnit(interactiveMap.selectedTerritoryID);
    }
};

interactiveMap.isUnitIn = function(terrID) {
    return (Territories.get(terrID).coastParent.unitID != null)
};

interactiveMap.isOwnUnitIn = function(terrID) {
    var unit = Territories.get(terrID).coastParent.Unit;
    if (!Object.isUndefined(unit)) {
        if (unit.countryID == context.countryID) {
            return true;
        }
    }
    return false;
};

interactiveMap.setWait = function(terrID) {
    for (var i = 0; i < MyOrders.length; i++)
        if (!MyOrders[i].isComplete&&(MyOrders[i].type != "Destroy")){ 
            interactiveMap.currentOrder = MyOrders[i];
            interactiveMap.currentOrder.interactiveMap.setOrder('Wait');}
};

/*
 * Adds additional functions to each order. These functions are needed for the interactiveMap-Interface.
 */
interactiveMap.loadOrders = function() {
    MyOrders.map(function(o) {
        o.interactiveMap = new Object();
        var IA = o.interactiveMap;

        IA.Order = o;   //for copies for fake convoy orders

        IA.resetOrder = function() {
            this.orderType = null;
			this.previousOrder = null;
            this.coordinates = null;
            this.convoyChain = new Array();
            this.terrChoices = new Array();
            this.orderCounter = 0;
        };

        IA.setAndShow = function(n, v) {
            if (this.Order[n] != v) {
                try {
                    this.Order.inputValue(n, v);
                } catch (err) {
                    /*
                     * Fix for support move problems probably caused by model.js line 196-219 (disabled extra check).
                     * Could lead to crashes when postUpdate() is called in inputValue() as this.ToTerritory.ConvoyGroup is wrongly expected.
                     * If postUpdate() crashes, OrdersHTML.updateFormButtons() has to be called directly.
                     */
                    if (err.message == 'this.ToTerritory.ConvoyGroup is undefined')
                        OrdersHTML.updateFormButtons();
                }
                if(this.Order.orderSegs != null)this.Order.reHTML(n);
            } else {
				/*
				 * If an already existing value is entered as last requirement,
				 * check if the order is complete (so the last value is actually
				 * valid (!= "")), so the IA order can be savely completed.
				 */
                if (this.Order.requirements[this.Order.requirements.length - 1] == n) {
					this.Order.checkComplete();
					OrdersHTML.updateFormButtons();
				} else
					this.Order.isComplete = false;
            }
            if (((this.orderType == "Support move to") && (n == "toTerrID")) || ((this.orderType == "Support hold") || (this.orderType == "Retreat")) && (n == "type")) {
                this.Order.isComplete = false; //fix for autofill (perhaps not wanted by user as supported unit changes orders as well)
            }
        };

        IA.orderType = null;
		IA.previousOrder = null;
        IA.setOrder = function(value) {
            if (this.orderType != null) {
				interactiveMap.errorMessages.uncompletedOrder();
				return;
            }

            if (value == "Convoy")
                if (!["Coast","Strait"].includes(this.Order.Unit.Territory.type)) {
                    interactiveMap.errorMessages.noCoast(this.Order.Unit.terrID);
                    interactiveMap.abortOrder();
                    return;
                } else if (this.Order.Unit.type != "Army") {
                    interactiveMap.errorMessages.noArmy(this.Order.Unit.terrID);
                    interactiveMap.abortOrder();
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
                    if (!["Coast","Strait"].includes(Territories.get(terrID).type)) {
                        alert("No coastal supply center selected (" + Territories.get(terrID).name + ")!");
                        interactiveMap.abortOrder();
                        return;
                    }
                    if (Territories.get(terrID).coast != "No")
                        terrID = this.getCoastByCoords(SupplyCenters.select(function(sc) {
                            return (sc.coastParentID == terrID) && (sc.id != sc.coastParentID)
                        }), this.coordinates).id;
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

            value = (value == "Convoy") ? "Move" : value;

            this.enterOrder('type', value);
			
			if(!this.Order.isComplete)
				// display reset button if order is not completed
				interactiveMap.interface.orderMenu.show(undefined, true);
        };

        IA.enterOrder = function(name, value) {
            if (o.isValid(name, value)) {
                this.setAndShow(name, value);
                this.print(name, value);
                this.getTerrChoices();
                if (Object.isUndefined(this.terrChoices[0]))
                    interactiveMap.greyOut.draw(new Array());
                else
                    interactiveMap.greyOut.draw(this.terrChoices);
                if (this.Order.isComplete)
                    interactiveMap.resetOrder();
            } else {
                alert("'" + value + "' as '" + name + "' could not be selected! Order reset!");
                interactiveMap.abortOrder();
            }
        };

        IA.print = function(name, value) {
            switch (name) {
                case "type":
                    this.printType();
                    break;
                default:
                    this.printOrderPart(name, value);
                    break;
            }
        };

        IA.printType = function() {
            switch (this.orderType) {
                case "Hold":
                    interactiveMap.insertMessage(" holds", true);
                    break;
                case "Move":
                    interactiveMap.insertMessage(" moves to ");
                    break;
                case "Support hold":
                    interactiveMap.insertMessage(" supports the holding unit in ");
                    break;
                case "Support move":
                    interactiveMap.insertMessage(" supports the moving unit to ");
                    break;
                case "Convoy":
                    interactiveMap.insertMessage(" moves via ");
                    break;

                case "Destroy":
                    interactiveMap.insertMessage(" is destroyed", true);
                    break;

                case "Build Army":
                    interactiveMap.insertMessage(" is chosen to build an " + interactiveMap.parameters.armyName, true);
                    break;
                case "Build Fleet":
                    interactiveMap.insertMessage(" is chosen to build a " + interactiveMap.parameters.fleetName, true);
                    break;
                case "Wait":
                    interactiveMap.insertMessage("Build No. " + (MyOrders.indexOf(o) + 1) + " is postponed", true, true);
                    break;

                case "Retreat":
                    interactiveMap.insertMessage(" retreats to ");
                    break;
                case "Disband":
                    interactiveMap.insertMessage(" disbands", true);
                    break;
            }
        };

        IA.printOrderPart = function(name, value) {
            switch (this.orderType) {
                case "Move":
                case "Support hold":
                    if (!isNaN(value))
                        interactiveMap.insertMessage(Territories.get(value).name, true);
                    break;
                case "Support move to":
                    interactiveMap.insertMessage(Territories.get(value).name + " from ");
                    break;
                case "Support move from":
                    interactiveMap.insertMessage(Territories.get(value).name, true);
                    break;
                case "Convoy":
                    if (!isNaN(value))
                        interactiveMap.insertMessage(" to " + Territories.get(value).name, true);
                    break;

                case "Retreat":
                    interactiveMap.insertMessage(Territories.get(value).name, true);
                    break;
            }
        };

        IA.setOrderPart = function(terrID, coordinates) {
            switch (this.orderType) {
                case "Move":
                    this.setMove(terrID, coordinates);
                    break;
                case "Support hold":
                    this.setSupportHold(terrID);
                    break;
                case "Support move":
                    this.setSupportMove(terrID, coordinates);
                    break;
                case "Support move from":
                    this.setSupportMoveFrom(terrID);
                    break;
                case "Convoy":
                    this.setConvoy(terrID);
                    break;

                case "Retreat":
                    this.setRetreat(terrID, coordinates);
                    break;
            }
        };

        IA.setMove = function(terrID, coordinates) {
            if ((o.Unit.type == "Fleet") && (Territories.get(terrID).coast != "No")) {
                terrID = this.getCoast(terrID, coordinates);
            }

            if (!o.Unit.getMovableTerritories().pluck('id').include(terrID)) {
                alert(((o.Unit.type == "Army") ? interactiveMap.parameters.armyName : interactiveMap.parameters.fleetName) + " in " + o.Unit.Territory.name + " cannot move to " + Territories.get(terrID).name + " (not adjacent / wrong unit type)");
                return;
            }
            this.enterOrder('toTerrID', terrID);
            if (!o.isComplete)
                this.enterOrder('viaConvoy', 'No');
        };

        IA.setSupportHold = function(terrID) {
            if (!o.Unit.getMovableTerritories().pluck('coastParentID').include(terrID)) {
                alert(((o.Unit.type == "Army") ? interactiveMap.parameters.armyName : interactiveMap.parameters.fleetName) + " in " + o.Unit.Territory.name + " cannot support unit in " + Territories.get(terrID).name + " (not adjacent / wrong unit type)");
                return;
            }
            if (!this.isUnitIn(terrID)) {
                interactiveMap.errorMessages.noUnit(terrID);
                return;
            }

            //enter order for supported unit
            if (this.isOwnUnitIn(terrID)) {
                var order = Territories.get(terrID).Unit.Order;
                if ((order.type == "Move") || !order.isComplete)
                    order.interactiveMap.setAndShow('type', 'Hold');
            }

            this.enterOrder('toTerrID', terrID);
        };

        IA.setSupportMove = function(terrID, coordinates) {
            if (!o.Unit.getSupportMoveToChoices().include(terrID)) {
                alert(((o.Unit.type == "Army") ? interactiveMap.parameters.armyName : interactiveMap.parameters.fleetName) + " in " + o.Unit.Territory.name + " cannot support unit to " + Territories.get(terrID).name + " (not adjacent / wrong unit type)");
                return;
            }
            if (o.Unit.getSupportMoveFromChoices(Territories.get(terrID)).length == 0) {
                alert(((o.Unit.type == "Army") ? interactiveMap.parameters.armyName : interactiveMap.parameters.fleetName) + " in " + o.Unit.Territory.name + " cannot support unit to " + Territories.get(terrID).name + " (not reachable for other units)");
                return;
            }

            this.coordinates = coordinates;

            this.orderType = "Support move to";
            this.enterOrder('toTerrID', terrID);
            this.orderType = "Support move from";
        };

        IA.setSupportMoveFrom = function(terrID) {
            if (!this.isUnitIn(terrID)) {
                interactiveMap.errorMessages.noUnit(terrID);
                return;
            }
            if (!o.Unit.getSupportMoveFromChoices(o.ToTerritory).include(terrID)) {
                alert(o.ToTerritory.name + " cannot be reached by " + ((Territories.get(terrID).Unit.type == "Army") ? interactiveMap.parameters.armyName : interactiveMap.parameters.fleetName) + " from " + Territories.get(terrID).name + " (not adjacent / wrong unit type)");
                return;
            }

            //enter order for supported unit
            if (this.isOwnUnitIn(terrID)) {
                var order = Territories.get(terrID).Unit.Order;
                var toTerrID = ((Territories.get(terrID).Unit.type == "Fleet") && (Territories.get(o.toTerrID).coast != "No")) ? this.getCoastFrom(o.toTerrID, terrID, this.coordinates) : o.toTerrID;
                if (!((order.type == "Move") && (order.toTerrID == toTerrID) && order.isComplete)) {
                    order.interactiveMap.setAndShow('type', 'Move');
                    order.interactiveMap.setAndShow('toTerrID', toTerrID);
                    if (!order.isComplete)
                        order.interactiveMap.setAndShow('viaConvoy', 'No');
                }
            }

            this.enterOrder('fromTerrID', terrID);
        };

        IA.convoyChain = new Array();

        IA.setConvoy = function(terrID) {
            if (Territories.get(terrID).type == "Coast" || (Territories.get(terrID).type == "Strait" && !(this.isUnitIn(terrID) && Territories.get(terrID).Unit.type == "Fleet")))
                this.finishConvoy(terrID);
			else if(Territories.get(terrID).type == "Strait" && this.convoyChain.last() == terrID && this.convoyChain.length > 1 ){
				// a convoy is finished on a strait with a fleet already selected
				// for that convoy -> remove the fleet again and finish
				this.convoyChain.pop();
				this.finishConvoy(terrID);
			} else {
                if (!this.isUnitIn(terrID)) {
                    interactiveMap.errorMessages.noUnit(terrID);
                    return;
                }
                if (Territories.get(terrID).Unit.type != "Fleet") {
                    interactiveMap.errorMessages.noFleet(terrID);
                    return;
                }
                /*if(Territories.get(terrID).type != "Sea"){
                 alert("Convoying " + interactiveMap.parameters.fleetName + " not in Sea-Territory");
                 return;
                 }*/
                if (!Territories.get(terrID).Unit.getMovableTerritories().pluck('coastParentID').include(Object.isUndefined(this.convoyChain[0]) ? this.Order.Unit.terrID : this.convoyChain[this.convoyChain.length - 1])) {
                    alert(interactiveMap.parameters.fleetName + " (" + Territories.get(terrID).name + ") not neighbor of " + (Object.isUndefined(this.convoyChain[0]) ? this.Order.Unit.Territory.name : Territories.get(this.convoyChain[this.convoyChain.length - 1]).name) + "!");
                    return;
                }
                if (this.convoyChain.any(function(e) {
                    return terrID == e;
                })) {
                    alert(interactiveMap.parameters.fleetName + " (" + Territories.get(terrID).name + ") already selected!");
                    return;
                }
                if (!Object.isUndefined(this.convoyChain[0]))
                    interactiveMap.insertMessage(", ");
                else
                    this.convoyChain = new Array();
                this.convoyChain.push(terrID);
                interactiveMap.insertMessage(Territories.get(terrID).name);

                this.getTerrChoices();
                interactiveMap.greyOut.draw(this.terrChoices);
            }
        };

        IA.finishConvoy = function(terrID) {
            if (Object.isUndefined(this.convoyChain[0])) {
                alert("You have to select at least one " + interactiveMap.parameters.fleetName + " to convoy an " + interactiveMap.parameters.armyName + "!");
                return;
            }
            if (!Territories.get(this.convoyChain[this.convoyChain.length - 1]).Unit.getMovableTerritories().pluck('coastParentID').include(terrID)) {
                alert(Territories.get(terrID).name + " not neighbor of " + Territories.get(this.convoyChain[this.convoyChain.length - 1]).name + "!");
                return;
            }
            if (terrID == this.Order.Unit.terrID) {
                return;
            }

            //enter order for convoying units
            for (var i = 0; i < this.convoyChain.length; i++) {
                if (this.isOwnUnitIn(this.convoyChain[i])) {
                    var order = Territories.get(this.convoyChain[i]).Unit.Order;
                    order.interactiveMap.setAndShow('type', 'Convoy');
                    order.interactiveMap.setAndShow('toTerrID', terrID);
                    order.interactiveMap.setAndShow('fromTerrID', this.Order.Unit.terrID);
                }
            }

            if (this.isOwnUnitIn(this.Order.Unit.terrID)) {
                this.enterOrder('toTerrID', terrID);
                if (this.Order.viaConvoy=='No'||!this.Order.isComplete)
                    this.enterOrder('viaConvoy', 'Yes');
            } else {
                this.print('toTerrID', terrID);
                interactiveMap.resetOrder();
            }
        };

        IA.setRetreat = function(terrID, coordinates) {
            if ((o.Unit.type == "Fleet") && (Territories.get(terrID).coast != "No")) {
                terrID = this.getCoastFromUnit(terrID, o.Unit, coordinates);
            }

            if (!o.toTerrChoices.keys().include(terrID)) {
                alert(((o.Unit.type == "Army") ? interactiveMap.parameters.armyName : interactiveMap.parameters.fleetName) + " in " + o.Unit.Territory.name + " cannot move to " + Territories.get(terrID).name + " (not adjacent / wrong unit type / occupied / standoff)");
                return;
            }
            this.enterOrder('toTerrID', terrID);
        };

        IA.getCoast = function(terrID, coordinates) {
            return this.getCoastFrom(terrID, o.Unit.terrID, coordinates);
        };

        IA.getCoastFrom = function(terrID, fromTerrID, coordinates) {
            return this.getCoastFromUnit(terrID, Territories.get(fromTerrID).coastParent.Unit, coordinates);
        };

        IA.getCoastFromUnit = function(terrID, fromUnit, coordinates) {
            var coasts = fromUnit.getMovableTerritories().select(function(n) {
                return (n.coastParentID == terrID) && (n.coast != "Parent");
            });

            if (Object.isUndefined(coasts) || (coasts == null) || (coasts.length == 0)) { //if something went wrong (e.g. not reachable for unit)
                return terrID;
            } else if (coasts.length == 1) {   //only one coast can be selected
                return coasts[0].id;
            } else {  //more coasts are possible
                return this.getCoastByCoords(coasts, coordinates).id;
            }
        };

        IA.getCoastByCoords = function(coasts, coordinates) {
            return coasts.sortBy(function(coast) {
                var xdiff = Math.abs(coordinates.x - coast.smallMapX);
                var ydiff = Math.abs(coordinates.y - coast.smallMapY);
                var distance = Math.sqrt(xdiff * xdiff + ydiff * ydiff);
                return distance;
            })[0];
        };

        IA.isUnitIn = function(terrID) {
            return interactiveMap.isUnitIn(terrID);
        };

        IA.isOwnUnitIn = function(terrID) {
            return interactiveMap.isOwnUnitIn(terrID);
        };

        IA.terrChoices = new Array();

        IA.getTerrChoices = function() {
            switch (this.orderType) {
                case "Move":
                    this.terrChoices = o.Unit.getMovableTerritories().pluck('id');
                    break;
                case "Support hold":
                    this.terrChoices = o.Unit.getSupportHoldChoices();
                    break;
                case "Support move":
                    this.terrChoices = o.Unit.getSupportMoveToChoices().select(function(c) {
                        return (o.Unit.getSupportMoveFromChoices(Territories.get(c)).length != 0);
                    });
                    break;
                case "Support move to":
                    this.terrChoices = o.Unit.getSupportMoveFromChoices(o.ToTerritory);
                    break;
                case "Convoy":
                    var currentUnit = Object.isUndefined(this.convoyChain[0]) ? this.Order.Unit : Territories.get(this.convoyChain[this.convoyChain.length - 1]).Unit;
                    this.terrChoices = currentUnit.getBorderUnits().select(function(u) {
                        return ["Sea","Strait"].includes(u.Territory.type);   //adjacent fleets
                    }).pluck("terrID");
                    if (currentUnit.type == "Fleet")
                        this.terrChoices = this.terrChoices.concat(currentUnit.Territory.getBorderTerritories().select(function(t) {
                            return ["Coast","Strait"].includes(t.type);       //adjacent coast if at least one fleet in convoyChain
                        }).pluck('id'));
                    this.terrChoices = this.terrChoices.select(function(terrID) {
                        return (this.convoyChain.indexOf(terrID) == -1) && (terrID != this.Order.Unit.terrID);   //remove already used fleets and army's origin
                    }, this);
					// if the current territory is a fleet on a strait (and not the only fleet), 
					// include that strait again so the user is notified about 
					// the possibility to double select the strait for a direct convoy
					if(this.convoyChain.length > 1 && currentUnit.type == "Fleet" 
							&& currentUnit.Territory.type == "Strait")
						this.terrChoices.push(currentUnit.Territory.id)
					
                    break;

                case "Retreat":
                    this.terrChoices = o.toTerrChoices.keys();
            }
        };

        IA.fakeOrder = function() {
            if (((o.type == "Support move") || (o.type == "Convoy")) && !this.isOwnUnitIn(o.fromTerrID) && this.isUnitIn(o.fromTerrID)) {
                return {
                    Unit: Territories.get(o.fromTerrID).Unit,
                    type: "Faked Move",
                    toTerrID: o.toTerrID,
                    fromTerrID: Territories.get(o.fromTerrID).Unit.terrID,
                    isComplete: true
                };
            } else
                return null;
        };
    });
};