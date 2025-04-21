/*
 * Disables all orders including a move to Spain (id=9) in Diplomacy and Retreats
 * phase.
 * 
 * This is done by adding additional checks and filtering the the terr choices 
 * for GreyOut.
 */
function loadOneWay(){
	MyOrders.map(function(o) {
		var IA = o.interactiveMap;
		
		// additional checks for Move (incl. Convoy), Support (hold and move), Retreat
		
		var origSetMove = IA.setMove;
		
		IA.setMove = function(terrID, coordinates) {
			if(terrID == 9){
				alert(((o.Unit.type == "Army") ? interactiveMap.parameters.armyName : interactiveMap.parameters.fleetName) + " in " + o.Unit.Territory.name + " cannot move to " + Territories.get(terrID).name + " (Spain cannot be entered)");
                return;
			}
			
			origSetMove.bind(this)(terrID, coordinates);
		};
		
		var origSetSupportHold = IA.setSupportHold;
		
		IA.setSupportHold = function(terrID) {
			if(terrID == 9){
				alert(((o.Unit.type == "Army") ? interactiveMap.parameters.armyName : interactiveMap.parameters.fleetName) + " in " + o.Unit.Territory.name + " cannot support unit in " + Territories.get(terrID).name + " (Spain cannot be entered)");
                return;
			}
			
			origSetSupportHold.bind(this)(terrID);
		};
		
		var origSetSupportMove = IA.setSupportMove;
		
		IA.setSupportMove = function(terrID, coordinates) {
			if(terrID == 9){
				alert(((o.Unit.type == "Army") ? interactiveMap.parameters.armyName : interactiveMap.parameters.fleetName) + " in " + o.Unit.Territory.name + " cannot support unit to " + Territories.get(terrID).name + " (Spain cannot be entered)");
                return;
			}
			
			origSetSupportMove.bind(this)(terrID, coordinates);
		};
		
		var origSetConvoy = IA.setConvoy;
		
		IA.setConvoy = function(terrID) {
			if(terrID == 9){
				alert(((o.Unit.type == "Army") ? interactiveMap.parameters.armyName : interactiveMap.parameters.fleetName) + " in " + o.Unit.Territory.name + " cannot convoy to " + Territories.get(terrID).name + " (Spain cannot be entered)");
                return;
			}
			
			origSetConvoy.bind(this)(terrID);
		};
		
		var origSetRetreat = IA.setRetreat;
		
		IA.setRetreat = function(terrID, coordinates) {
			if(terrID == 9){
				alert(((o.Unit.type == "Army") ? interactiveMap.parameters.armyName : interactiveMap.parameters.fleetName) + " in " + o.Unit.Territory.name + " cannot move to " + Territories.get(terrID).name + " (Spain cannot be entered)");
                return;
			}
			
			origSetRetreat.bind(this)(terrID, coordinates);
		};
		
		
		// filter the TerrChoices
		var origGetTerrChoices = IA.getTerrChoices;
		
		IA.getTerrChoices = function() {
			origGetTerrChoices.bind(this)();
			
			if(this.orderType != "Support move to") {
				this.terrChoices = this.terrChoices.reject(function(terrID){
					return terrID == 9;
				});
			}
		}
	});
}


