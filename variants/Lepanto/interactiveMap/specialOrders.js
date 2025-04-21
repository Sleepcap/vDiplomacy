interactiveMap.parameters.armyName = 'galley';
interactiveMap.parameters.fleetName = 'frigate';

loadSpecialOrders = function(){
    MyOrders.map(function(o){
        var IA = o.interactiveMap;
        
        if(!Object.isUndefined(o.Unit) && (o.Unit.terrID == 11 || o.Unit.terrID == 13 || o.Unit.terrID == 86 || o.Unit.terrID == 88)){
            var setOrder = IA.setOrder.bind(IA);
            
            IA.setOrder = function(value){
				if (this.orderType != null) {
                    interactiveMap.errorMessages.uncompletedOrder();
                    return;
                }
                
                if(value == 'Move'){
                    alert('Flagships are not allowed to move!');
                    interactiveMap.abortOrder();
                    return;
                }
                
                setOrder(value);
            };
        }
        
        var setOrder2 = IA.setOrder.bind(IA);
            
        IA.setOrder = function(value){
            if (this.orderType != null) {
                interactiveMap.errorMessages.uncompletedOrder();
                return;
            }
                
            if(value == 'Convoy'){
                alert('Units cannot be convoyed in this variant');
                interactiveMap.abortOrder();
                return;
            }
                
            setOrder2(value);
        };
    });
};

updateButtons = function(){
    if($('convoy')!=null)
        $('convoy').remove();
    
    if($('buildFleet')!=null)
        $('buildFleet').remove();
    
    if(context.phase == "Diplomacy"){
        var OrderMenuShowElement = interactiveMap.interface.orderMenu.showElement;
        
        interactiveMap.interface.orderMenu.showElement = function(element){
            if(element.id == 'imgConvoy' || element.id == 'imgBuildFleet') return;
            
            if(element.id == 'imgMove' && interactiveMap.currentOrder != null && ["11","13","86","88"].indexOf(interactiveMap.currentOrder.Unit.terrID) != -1){
                interactiveMap.interface.orderMenu.hideElement(element);
                return;
            }
            
            OrderMenuShowElement(element);
        };
    }
};