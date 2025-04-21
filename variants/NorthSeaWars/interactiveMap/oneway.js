/*
 * Disables all orders including a move from trade areas (id=27,28,29) 
 * to CNS (id = 30) in Diplomacy phase.
 * 
 * This is done by adding additional checks and filtering the the terr choices 
 * for GreyOut.
 */
function loadOneWay(){
	var tradeSCs = [27,28,29];
	var CNSid = 30;
	
	MyOrders.map(function(o) {
		var IA = o.interactiveMap;
		
		// additional checks for Move (incl. Convoy), Support (hold and move), Retreat
		
		var origSetMove = IA.setMove;
		
		IA.setMove = function(terrID, coordinates) {
			if(tradeSCs.include(this.Order.Unit.terrID) && terrID == CNSid){
				alert(((o.Unit.type == "Army") ? interactiveMap.parameters.armyName : interactiveMap.parameters.fleetName) + " in " + o.Unit.Territory.name + " cannot move to " + Territories.get(terrID).name + " (trade SCs cannot be left)");
                return;
			}
			
			origSetMove.bind(this)(terrID, coordinates);
		};
		
		var origSetSupportHold = IA.setSupportHold;
		
		IA.setSupportHold = function(terrID) {
			if(tradeSCs.include(this.Order.Unit.terrID) && terrID == CNSid){
				alert(((o.Unit.type == "Army") ? interactiveMap.parameters.armyName : interactiveMap.parameters.fleetName) + " in " + o.Unit.Territory.name + " cannot support unit in " + Territories.get(terrID).name + " (trade SCs cannot be left)");
                return;
			}
			
			origSetSupportHold.bind(this)(terrID);
		};
		
		var origSetSupportMove = IA.setSupportMove;
		
		IA.setSupportMove = function(terrID, coordinates) {
			if(tradeSCs.include(this.Order.Unit.terrID) && terrID == CNSid){
				alert(((o.Unit.type == "Army") ? interactiveMap.parameters.armyName : interactiveMap.parameters.fleetName) + " in " + o.Unit.Territory.name + " cannot support unit to " + Territories.get(terrID).name + " (trade SCs cannot be left)");
                return;
			}
			
			origSetSupportMove.bind(this)(terrID, coordinates);
		};
		
		var origSetConvoy = IA.setConvoy;
		
		IA.setConvoy = function(terrID) {
			if(tradeSCs.include(this.Order.Unit.terrID) && terrID == CNSid){
				alert(((o.Unit.type == "Army") ? interactiveMap.parameters.armyName : interactiveMap.parameters.fleetName) + " in " + o.Unit.Territory.name + " cannot convoy to " + Territories.get(terrID).name + " (trade SCs cannot be left)");
                return;
			}
			
			origSetConvoy.bind(this)(terrID);
		};
		
		// additional check for support move from (not include support move to CNS from trade SCs)
		
		var origSetSupportMoveFrom = IA.setSupportMoveFrom;
		
		IA.setSupportMoveFrom = function(terrID) {
			if(tradeSCs.include(terrID) && this.Order.toTerrID == CNSid){
				alert(o.ToTerritory.name + " cannot be reached by " + ((Territories.get(terrID).Unit.type == "Army") ? interactiveMap.parameters.armyName : interactiveMap.parameters.fleetName) + " from " + Territories.get(terrID).name + " (trade SCs cannot be left)");
                return;
			}
			
			origSetSupportMoveFrom.bind(this)(terrID);
		};
		
		
		// filter the TerrChoices
		var origGetTerrChoices = IA.getTerrChoices;
		
		IA.getTerrChoices = function() {
			origGetTerrChoices.bind(this)();
			
			if(this.orderType != "Support move to" && tradeSCs.include(this.Order.Unit.terrID)) {
				this.terrChoices = this.terrChoices.reject(function(terrID){
					return terrID == CNSid;
				});
			}
			
			if(this.orderType == "Support move to" && this.Order.toTerrID == CNSid){
				this.terrChoices = this.terrChoices.reject(function(terrID){
					return tradeSCs.include(terrID);
				});
			}
		}
	});
}


