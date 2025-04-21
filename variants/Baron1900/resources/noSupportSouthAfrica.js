/* 
 * Disable support moves and support holds around South Africa in the interface
 */
function noSupportSouthAfrica(hejaz, egypt, mao)
{
	MyUnits.map(function(UnitObj)
	{
		if(UnitObj.terrID == mao || UnitObj.terrID == hejaz || UnitObj.terrID == egypt){
			var origGetSupportHoldChoices = UnitObj.getSupportHoldChoices.bind(UnitObj);
			var origGetSupportMoveToChoices = UnitObj.getSupportMoveToChoices.bind(UnitObj);
			
			if(UnitObj.terrID == mao){
				
				UnitObj.getSupportHoldChoices = function() {
					return origGetSupportHoldChoices().reject(
						function(choice){return (choice == egypt || choice == hejaz);});
				};
				
				UnitObj.getSupportMoveToChoices = function(){
					return origGetSupportMoveToChoices().reject(
						function(choice){return (choice == egypt || choice == hejaz);});
				};
				
			}else{//ID must be hejaz or egypt
				
				UnitObj.getSupportHoldChoices = function() {
					return origGetSupportHoldChoices().reject(
						function(choice){return choice == mao;});
				};
				
				UnitObj.getSupportMoveToChoices = function(){
					return origGetSupportMoveToChoices().reject(
						function(choice){return choice == mao;});
				};
				
			}
		}
	}, this);
}

