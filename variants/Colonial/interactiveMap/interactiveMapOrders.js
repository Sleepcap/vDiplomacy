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
                if (this.Order.Unit.Territory.type != "Coast") {
                    interactiveMap.errorMessages.noCoast(this.Order.Unit.terrID);
                    interactiveMap.abortOrder();
                    return;
                } else if (this.Order.Unit.type != "Army") {
                    interactiveMap.errorMessages.noArmy(this.Order.Unit.terrID);
                    interactiveMap.abortOrder();
                    return;
                }
                
            if (value == "TSR" && MyOrders.any(function(myO){return myO.isTransSibOrder() && myO != o && myO.isComplete;})){
                alert("Only one unit per turn can use the TSR!");
                interactiveMap.abortOrder();
                return;
            }
            
            if (value == "Suez" && !['99','101'].include(interactiveMap.selectedTerritoryID)){
                alert("Only fleets in Mediterranean Sea and Red Sea can get permission to use the Suez Canal!");
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
                this.enterOrder('type', value);
                this.enterOrder('toTerrID', terrID);
                return;
            }
            
            if ((value == "Suez")) {
                var terrID = interactiveMap.selectedTerritoryID;
                this.enterOrder('type', 'Support hold');
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

            value = (value == "Convoy" || value == "TSR") ? "Move" : value;

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
                    
                
                case "TSR":
                    interactiveMap.insertMessage(" moves via Trans-Siberian Railroad to ");
                    break;
                    
                case "Suez":
                    interactiveMap.insertMessage(" is allowed to use the Suez Canal.", true);
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
                case "TSR":
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
                    
                case "TSR":
                    this.setTSR(terrID, coordinates);
            }
        };
        
        IA.setTSR = function(terrID, coordinates) {
            if (!transSibTerritories.include(terrID)) {
                alert(((o.Unit.type == "Army") ? interactiveMap.parameters.armyName : interactiveMap.parameters.fleetName) + " in " + o.Unit.Territory.name + " can not move to " + Territories.get(terrID).name + " via TSR (not reachable)");
                return;
            }
            this.enterOrder('toTerrID', terrID);
            if (!o.isComplete)
                this.enterOrder('viaConvoy', 'Yes');
        }

        IA.setMove = function(terrID, coordinates) {
            if ((o.Unit.type == "Fleet") && (Territories.get(terrID).coast != "No")) {
                terrID = this.getCoast(terrID, coordinates);
            }

            if (!o.Unit.getMovableTerritories().pluck('id').include(terrID) && !(Array('99','101').include(o.Unit.terrID)) && Array('99','101').include(terrID)) {
                alert(((o.Unit.type == "Army") ? interactiveMap.parameters.armyName : interactiveMap.parameters.fleetName) + " in " + o.Unit.Territory.name + " can not move to " + Territories.get(terrID).name + " (not adjacent / wrong unit type)");
                return;
            }
            
            if((Array('99','101').include(o.Unit.terrID)) && Array('99','101').include(terrID) && !Object.isUndefined(MyOrders.find(function(order){return order.Unit.terrID === "126";}))){
                var order = MyOrders.find(function(order){return order.Unit.terrID === "126";});
                
                if(!((order.type == "Support hold") && (order.toTerrID == o.Unit.terrID) && order.isComplete)){
                    order.interactiveMap.setAndShow('type', 'Support hold');
                    order.interactiveMap.setAndShow('toTerrID', o.Unit.terrID);
                }
            }
            
            this.enterOrder('toTerrID', terrID);
            if (!o.isComplete)
                this.enterOrder('viaConvoy', 'No');
        };

        IA.setSupportHold = function(terrID) {
            if (!o.Unit.getMovableTerritories().pluck('coastParentID').include(terrID)) {
                alert(((o.Unit.type == "Army") ? interactiveMap.parameters.armyName : interactiveMap.parameters.fleetName) + " in " + o.Unit.Territory.name + " can not support unit in " + Territories.get(terrID).name + " (not adjacent / wrong unit type)");
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
                alert(((o.Unit.type == "Army") ? interactiveMap.parameters.armyName : interactiveMap.parameters.fleetName) + " in " + o.Unit.Territory.name + " can not support unit to " + Territories.get(terrID).name + " (not adjacent / wrong unit type)");
                return;
            }
            if (o.Unit.getSupportMoveFromChoices(Territories.get(terrID)).length == 0) {
                alert(((o.Unit.type == "Army") ? interactiveMap.parameters.armyName : interactiveMap.parameters.fleetName) + " in " + o.Unit.Territory.name + " can not support unit to " + Territories.get(terrID).name + " (not reachable for other units)");
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
                alert(o.ToTerritory.name + " cannot be reached by " + ((Territories.get(terrID).Unit.type == "Army") ? interactiveMap.parameters.armyName : interactiveMap.parameters.fleetName) + " from " + Territories.get(terrID).name) + " (not adjacent / wrong unit type)";
                return;
            }

            //enter order for supported unit
            if (this.isOwnUnitIn(terrID)) {
                var order = Territories.get(terrID).Unit.Order;
                var toTerrID = ((Territories.get(terrID).Unit.type == "Fleet") && (Territories.get(o.toTerrID).coast != "No")) ? this.getCoastFrom(o.toTerrID, terrID, this.coordinates) : o.toTerrID;
                if (!((order.type == "Move") && (order.toTerrID == toTerrID) && order.isComplete)) {
                    order.interactiveMap.setAndShow('type', 'Move');
                    order.interactiveMap.setAndShow('toTerrID', toTerrID);
                    
                    //enter Suez Permission if needed
                    if((Array('99','101').include(terrID)) && Array('99','101').include(toTerrID) && !Object.isUndefined(MyOrders.find(function(order){return order.Unit.terrID === "126";}))){
                        var order2 = MyOrders.find(function(order){return order.Unit.terrID === "126";});
                
                        if(!((order2.type == "Support hold") && (order2.toTerrID == terrID) && order2.isComplete)){
                            order2.interactiveMap.setAndShow('type', 'Support hold');
                            order2.interactiveMap.setAndShow('toTerrID', terrID);
                        }
                    }
                    
                    
                    if (!order.isComplete)
                        order.interactiveMap.setAndShow('viaConvoy', 'No');
                }
            }

            this.enterOrder('fromTerrID', terrID);
        };

        IA.convoyChain = new Array();

        IA.setConvoy = function(terrID) {
            if (Territories.get(terrID).type == "Coast")
                this.finishConvoy(terrID);
            else {
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
                if (!this.Order.isComplete)
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
                alert(((o.Unit.type == "Army") ? interactiveMap.parameters.armyName : interactiveMap.parameters.fleetName) + " in " + o.Unit.Territory.name + " can not move to " + Territories.get(terrID).name + " (not adjacent / wrong unit type / occupied / standoff)");
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
                    this.terrChoices = o.Unit.getMovableTerritories().pluck('id').concat(Array('99','101').filter(function(terrID){return o.Unit.getMoveChoices().include(terrID);})).uniq();
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
                        return u.Territory.type == "Sea";   //adjacent fleets
                    }).pluck("terrID");
                    if (currentUnit.type == "Fleet")
                        this.terrChoices = this.terrChoices.concat(currentUnit.Territory.getBorderTerritories().select(function(t) {
                            return t.type == "Coast";       //adjacent coast if at least one fleet in convoyChain
                        }).pluck('id'));
                    this.terrChoices = this.terrChoices.select(function(terrID) {
                        return (this.convoyChain.indexOf(terrID) == -1) && (terrID != this.Order.Unit.terrID);   //remove already used fleets and army's origin
                    }, this);
                    break;

                case "Retreat":
                    this.terrChoices = o.toTerrChoices.keys();
                    break;
                    
                    
                case "TSR":
                    this.terrChoices = transSibTerritories.filter(function(terrID){return terrID != o.Unit.terrID});
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

interactiveMap.sendOrder = function(value) {
    if(value == "Suez") { 
            if(Object.isUndefined(MyOrders.find(function(order){return order.Unit.terrID === "126";})))
                alert('The Suez Canal (Egypt) is not occupied by an own Unit!');   
            else if(Object.isUndefined(Territories.get(interactiveMap.selectedTerritoryID).Unit))
                interactiveMap.errorMessages.noUnit(interactiveMap.selectedTerritoryID);
            else {
                interactiveMap.currentOrder = MyOrders.find(function(order){return order.Unit.terrID === "126";});
                interactiveMap.currentOrder.interactiveMap.setOrder(value);
            }
            
    } else if (interactiveMap.currentOrder != null) {
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