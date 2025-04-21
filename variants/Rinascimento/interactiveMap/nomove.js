IA_nomove = function(unit_id){
    MyOrders.map(function(o){
        var IA = o.interactiveMap;
        
        if(!Object.isUndefined(o.Unit) && o.Unit.id == unit_id){
            var setOrder = IA.setOrder.bind(IA);
            
            IA.setOrder = function(value){
				if (this.orderType != null) {
                    interactiveMap.errorMessages.uncompletedOrder();
                    return;
                }
                
                if(value == 'Move'){
                    alert('Unit not allowed to move in this variant!');
                    interactiveMap.abortOrder();
                    return;
                }
                
                setOrder(value);
            };
        }
    });
	
	IA_nomove_updateButtons(unit_id)
};

IA_nomove_updateButtons = function(unit_id){
    if(context.phase == "Diplomacy"){
        var OrderMenuShowElement = interactiveMap.interface.orderMenu.showElement;
        
        interactiveMap.interface.orderMenu.showElement = function(element){
            if(element.id == 'imgMove' && interactiveMap.currentOrder != null && interactiveMap.currentOrder.Unit.id == unit_id){
                interactiveMap.interface.orderMenu.hideElement(element);
                return;
            }
            
            OrderMenuShowElement(element);
        };
    }
};