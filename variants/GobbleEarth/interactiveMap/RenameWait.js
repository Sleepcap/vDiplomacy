/* 
 * Renames the printed text, when Build postponed is chosen.
 */
function loadRenameWait(){
	MyOrders.map(function(o) {
		var IA = o.interactiveMap;
		
		var origPrintType = IA.printType;
		
		IA.printType = function() {
			if(this.orderType == "Wait")
				interactiveMap.insertMessage("Build No. " + (MyOrders.indexOf(o) + 1) + " is saved for colonial build next year", true, true);
			else
				origPrintType.bind(this)();
		};
	});
}


