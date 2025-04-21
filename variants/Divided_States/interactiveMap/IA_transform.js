/*
 * What have to be changed:
 * 
 * - add 'transform' button in interface
 * - add 'transform' button in order-menu on map
 * - add image for 'transform' button on map
 * - add function to set 'transform' order
 * - add function to draw 'transform' order
 * 
 */

var imgArmy = new Image();
imgArmy.observe('load',function(){
    var canvas = new Element('canvas',{'width':imgArmy.width,'height':imgArmy.height});
    var ctx = canvas.getContext('2d');
    ctx.drawImage(imgArmy,0,0);
    setTransparent(ctx);
    
    imgArmy = canvas;
});
imgArmy.src = 'contrib/smallarmy.png';

var imgFleet = new Image();
imgFleet.observe('load',function(){
    var canvas = new Element('canvas',{'width':imgFleet.width,'height':imgFleet.height});
    var ctx = canvas.getContext('2d');
    ctx.drawImage(imgFleet,0,0);
    setTransparent(ctx);
    
    imgFleet = canvas;
});
imgFleet.src = 'contrib/smallfleet.png';

function setTransparent(ctx){
    var imgData = ctx.getImageData(0,0,ctx.canvas.width,ctx.canvas.height);
    for(var i=0; i<imgData.data.length; i+=4){
        var r = imgData.data[i];
        var g = imgData.data[i+1];
        var b = imgData.data[i+2];
        
        if(r===255 && g===255 && b===255)
            imgData.data[i+3] = 0;
    }
    
    ctx.putImageData(imgData,0,0);
}

function loadIAtransform() {

    function addTransformButton() {
        $('orderButtons').appendChild(new Element('button', {'id': 'transform', 'class': 'buttonIA form-submit', 'onclick': 'interactiveMap.sendOrder("Transform")', 'disabled': 'true'}).update("TRANSFORM"));
    }

    function addOrderMenuTransformButton() {
        var origCreate = interactiveMap.interface.orderMenu.create;
		
        interactiveMap.interface.orderMenu.create = function() {
			if (typeof interactiveMap.interface.orderMenu.element == "undefined") { 
				origCreate();
			
				interactiveMap.parameters.imgTransform = 'variants/Divided_States/interactiveMap/IA_transform.png';
				interactiveMap.interface.orderMenu.createButtonSet('Transform','transform');
			}
        };

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
					case 'Diplomacy':
						if (interactiveMap.currentOrder != null) {//||(unit(interactiveMap.selectedTerritoryID)&&(Territories.get(interactiveMap.selectedTerritoryID).type=="Coast")&&(Territories.get(interactiveMap.selectedTerritoryID).Unit.type=="Army")))
							if ((interactiveMap.currentOrder.Unit.type == "Fleet") || (Territories.get(interactiveMap.selectedTerritoryID).type != "Coast"))
								interactiveMap.interface.orderMenu.hideElement($("imgConvoy"));
							if ((interactiveMap.currentOrder.Unit.Territory.type !== "Coast") || !interactiveMap.currentOrder.Unit.Territory.coastParent.supply)
								interactiveMap.interface.orderMenu.hideElement($("imgTransform"));
							interactiveMap.interface.orderMenu.element.show();
						} else {
							if ((Territories.get(interactiveMap.selectedTerritoryID).type == "Coast") && !Object.isUndefined(Territories.get(interactiveMap.selectedTerritoryID).Unit) && (Territories.get(interactiveMap.selectedTerritoryID).Unit.type == "Army")) {
								interactiveMap.interface.orderMenu.hideElement($("imgMove"));
								interactiveMap.interface.orderMenu.hideElement($("imgHold"));
								interactiveMap.interface.orderMenu.hideElement($("imgSupportmove"));
								interactiveMap.interface.orderMenu.hideElement($("imgSupporthold"));
								interactiveMap.interface.orderMenu.hideElement($("imgTransform"));
								interactiveMap.interface.orderMenu.showElement($("imgConvoy"));
								interactiveMap.interface.orderMenu.element.show();
							}
						}
						break;
				}
			}
			
			this.positionMenu(coor);
			this.toggle(true);
        };
    }

    function addSetTransform() {

        MyOrders.pluck('interactiveMap').map(function(IA) {
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

				this.orderType = value;
				// before entering order: store previous order in case process of entering
				// order is aborted
				this.previousOrder = {
					'type': this.Order.type,
					'toTerrID': this.Order.toTerrID,
					'fromTerrID': this.Order.fromTerrID,
					'viaConvoy': this.Order.viaConvoy
				};

                if (value === "Transform") { //get special transform code for order value
					if ( this.Order.Unit.type === 'Fleet' || this.Order.Unit.Territory.coast === "No") {
						// a default transform order
						value = "Transform_"+(parseInt(this.Order.Unit.Territory.coastParentID) + 1000);
					} else {
						// a transform to a fleet with several coasts 
						// -> the correct coast has to be selected by the coordinates the user clicked on
						var coastID = this.getCoastByCoords(Territories.select(function(t) {
								return (t[1].coastParentID == interactiveMap.selectedTerritoryID) && (t[1].id != t[1].coastParentID)
							}).pluck("1"), this.coordinates).id;
							
						value = "Transform_"+(parseInt(coastID) + 1000);
					}
                }

                value = (value == "Convoy") ? "Move" : value;

                this.enterOrder('type', value);
				
				if(!this.Order.isComplete)
					// display reset button if order is not completed
					interactiveMap.interface.orderMenu.show(undefined, true);
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

                    case "Transform":
						interactiveMap.insertMessage(" transforms to " + ((this.Order.Unit.type === 'Army') ? interactiveMap.parameters.fleetName : interactiveMap.parameters.armyName));
						
						if ( this.Order.Unit.type === 'Army' && this.Order.Unit.Territory.coast === "Parent" ){
							// print extra info if a coast was choosen
							var coastID = this.getCoastByCoords(Territories.select(function(t) {
								return (t[1].coastParentID == interactiveMap.selectedTerritoryID) && (t[1].id != t[1].coastParentID)
							}).pluck("1"), this.coordinates).id;	
							
							interactiveMap.insertMessage(" on "+Territories.get(coastID).name.match(/\((.*)\)/)[1]);
						}
						
						interactiveMap.insertMessage(" ",true);
							
                        break;
                }
            };
        });
    }

    function addDrawTransform() {
        function drawArmy(terrID){
                interactiveMap.visibleMap.mainLayer.context.drawImage(imgArmy,Territories.get(terrID).smallMapX-(imgArmy.width/2), Territories.get(terrID).smallMapY-(imgArmy.height/2));
        }
        
        function drawFleet(terrID){
                interactiveMap.visibleMap.mainLayer.context.drawImage(imgFleet,Territories.get(terrID).smallMapX-(imgFleet.width/2), Territories.get(terrID).smallMapY-(imgFleet.height/2));
        }
        
        function drawTransform(terrID)
        {
		var darkblue  = [40, 80,130];
		var lightblue = [70,150,230];
		
		var x = Territories.get(terrID).smallMapX;
                var y = Territories.get(terrID).smallMapY;
		
		var width=imgFleet.width+imgFleet.width/2;
		
                filledcircle(x,y,width,darkblue);
                filledcircle(x,y,width-2,lightblue);
                
                if(Territories.get(terrID).coastParent.Unit.type === 'Army')
                    drawFleet(terrID);
                else
                    drawArmy(terrID);
	}
        
        function filledcircle(x,y,width,color)
        {
                interactiveMap.visibleMap.mainLayer.context.beginPath();
                interactiveMap.visibleMap.mainLayer.context.arc(x,y,width/2,0,2*Math.PI);
                interactiveMap.visibleMap.mainLayer.context.closePath();
                interactiveMap.visibleMap.mainLayer.context.fillStyle = "rgb(" + color[0] + "," + color[1] + "," + color[2] + ")";
                interactiveMap.visibleMap.mainLayer.context.fill();
        }
        
        var draw2 = interactiveMap.draw;

        interactiveMap.draw = function() {
            draw2();


            for (var i = 0; i < MyOrders.length; i++) {
                if (MyOrders[i].isComplete && MyOrders[i].type.include('Transform')) {
                    drawTransform(parseInt(MyOrders[i].type.sub('Transform_',''))-1000);
                }
            }
        }
    }

    addTransformButton();
    addOrderMenuTransformButton();
    addSetTransform();
    addDrawTransform();
}


