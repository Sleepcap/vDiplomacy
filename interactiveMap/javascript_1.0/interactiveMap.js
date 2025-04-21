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
var interactiveMap = new Object();

//autosave
interactiveMap.autosave = false;

//save the IA settings made by the user via the interface in the usersettings on the database (only works with vdip implementation of IA settings!)
interactiveMap.saveIAoptionsOnDatabase = true;

//hidden map to detect territories
interactiveMap.hiddenMap = new Object();

//true: everything for the interface is loaded
interactiveMap.ready = false;
//true: the interface is activated and ready for use
interactiveMap.activated = false;

//parameters which could be easily changed
interactiveMap.parameters = {
    armyName: 'Army',
    fleetName: 'Fleet',
    imgHold: 'interactiveMap/images/Hold.png',
    imgMove: 'interactiveMap/images/Move.png',
    imgSupporthold: 'interactiveMap/images/SupportHold.png',
    imgSupportmove: 'interactiveMap/images/SupportMove.png',
    imgConvoy: 'interactiveMap/images/Convoy.png',
    imgDestroy: 'interactiveMap/images/Destroy.png',
    imgBuildArmy: 'interactiveMap/images/BuildArmy.png',
    imgBuildFleet: 'interactiveMap/images/BuildFleet.png',
    imgWait: 'interactiveMap/images/Hold.png',
    imgRetreat: 'interactiveMap/images/Retreat.png',
    imgDisband: 'interactiveMap/images/Destroy.png',
	imgReset: 'interactiveMap/images/Reset.png',
	
	smallButtonSize: 15,
	largeButtonSize: 30,
};

//Only for Fog-Variants
interactiveMap.fog = {
    variantName: '',
    verify: ''
};

//Options which could be set via the interface by the user
interactiveMap.options = {
    scrollbars: true,       //if false scrollbars on the map are removed
    greyOut: false,         //possible territories for an order are greyed out or not
    unitGreyOut: false,     //own units are highlighted if no order is submitted at the moment
    greyOutIntensity: 0.4,  //0 - now intensity; 1 - black
	buttonWidth: 15,		//width of the on-map buttons (in px)
	buttonWidthAutomatic: true //automatically choose large buttons for mobile devices (small screens)
};


//initializes interactiveMap
function loadIA(variantName, verify) {
	
	if(MyOrders.length == 0) return; // do not do anything if there are no orders in this phase

    interactiveMap.loadOrders();
    interactiveMap.fog.variantName = variantName;
    interactiveMap.fog.verify = verify;

    //the HTML-Element for the order-input (via drop-down)
    //orderEle = $("orderFormElement");

    interactiveMap.interface.create();
	interactiveMap.hiddenMap.load();

    //the HTML-Element of the map
    interactiveMap.visibleMap.oldMap = $("mapImage");
}

/*
 * A helper functions, that calculates the map coordinates a user clicked from
 * a given mouse event.
 */
interactiveMap.getMapCoordinatesFromMouseEvent = function(event){
	var imgOffset = interactiveMap.visibleMap.mainLayer.canvasElement.cumulativeOffset().toArray();
    var x = event.pointerX() - imgOffset[0] + interactiveMap.visibleMap.element.scrollLeft;
    var y = event.pointerY() - imgOffset[1] + interactiveMap.visibleMap.element.scrollTop;

    return{x: x, y: y};
};

/*
 * Handles the actions after the player clicked on the map to enter orders.
 * 
 * @param {type} event
 */
interactiveMap.onClick = function(event) {
    /*
     * Returns the territory for specific coordinates.
     * 
     * @param {type} x
     * @param {type} y
     * @returns {unresolved|@exp;@call;getCoastByCoords@pro;id}
     */
    function getTerritory(x, y) {
        var color = getColor(x, y); //color -> color of clicked pixel

        var territory = Territories.detect(function(t) {
            return (t[1].coast!="Child")&&sameColor(color, getColor(t[1].smallMapX, t[1].smallMapY));
        });
        if (Object.isUndefined(territory))
            return null;
        else
            territory = territory[1];

        if (typeof territory == 'undefined')
            return null;
        else //if (territory.coast == "No")
            return territory.coastParent.id;
        /*else
         return checkCoast(territory.coastParent.id, x, y);*/
    }

    /*
     * Checks if two colors are equal. 
     * @param {type} c1
     * @param {type} c2
     * @returns {Boolean}
     */
    function sameColor(c1, c2) {
        for (var i = 0; (i < c1.length) && (i < c2.length); i++) {
            if (c1[i] !== c2[i])
                return false;
        }
        return true;
    }

    /*
     * Returns the color of a pixel in an array.
     * 
     * @param {type} x
     * @param {type} y
     * @returns {Array}
     */
    function getColor(x, y) {
        var pixelPos = (y * interactiveMap.hiddenMap.imageData.width * 4 + x * 4);
        return [interactiveMap.hiddenMap.imageData.data[pixelPos], interactiveMap.hiddenMap.imageData.data[pixelPos + 1], interactiveMap.hiddenMap.imageData.data[pixelPos + 2], interactiveMap.hiddenMap.imageData.data[pixelPos + 3]];
    }

    
    /*
     * Returns the index for the current build-order / destroy-order which is not linked to a specific unit
     */
    function getOrderBuilds(terrID) {
        if (MyOrders[0].type != "Destroy") {
            var newOrder = MyOrders[0];
            for (var i = 0; i < MyOrders.length; i++) {
                if (MyOrders[i].ToTerritory != null) {
                    var terr = MyOrders[i].ToTerritory;
                    if (terr.coastParentID == terrID) {
                        return MyOrders[i];
                    }
                } else {
                    newOrder = MyOrders[i];
                }
            }
            return newOrder;
        } else {
            interactiveMap.orderCounter = (Object.isUndefined(interactiveMap.orderCounter) ? 0 : (interactiveMap.orderCounter + 1) % MyOrders.length);
            return MyOrders[interactiveMap.orderCounter];
        }
    }
    
    /*
     * onClick-function (main part)
     */
    if (interactiveMap.ready && interactiveMap.activated && !OrdersHTML.finalized) {
        var coor = interactiveMap.getMapCoordinatesFromMouseEvent(event);
        interactiveMap.selectedTerritoryID = getTerritory(coor.x, coor.y);
        if (interactiveMap.selectedTerritoryID != null) {
            if ((interactiveMap.currentOrder == null) || (interactiveMap.currentOrder.interactiveMap.orderType == null)) {
				interactiveMap.interface.orderMenu.element.hide();
				
                if ((!Object.isUndefined(Territories.get(interactiveMap.selectedTerritoryID).Unit) && (context.phase != "Builds")) || (!Object.isUndefined(MyOrders[0]) && (context.phase == "Builds"))) {
                    var currOrder = (context.phase == "Retreats") ? MyOrders.detect(function(o) {
                        return o.Unit.Territory.coastParentID == interactiveMap.selectedTerritoryID;
                    })
                            : (context.phase == "Builds") ? getOrderBuilds(interactiveMap.selectedTerritoryID)
                            : Territories.get(interactiveMap.selectedTerritoryID).Unit.Order;
                    if (!Object.isUndefined(currOrder))
                        interactiveMap.currentOrder = currOrder;
                    else
                        interactiveMap.currentOrder = null;
                    
                    if (interactiveMap.currentOrder != null) {
                        interactiveMap.currentOrder.interactiveMap.coordinates = coor;
                    }
                    interactiveMap.insertMessage(""); //cleares Order-Line
                    interactiveMap.insertMessage((Object.isUndefined(currOrder)) ? Territories.get(interactiveMap.selectedTerritoryID).Unit.Territory.name : (!Object.isUndefined(currOrder.Unit) ? currOrder.Unit.Territory.name : Territories.get(interactiveMap.selectedTerritoryID).name));

                    interactiveMap.interface.orderMenu.show(coor);
                } else {
                    interactiveMap.insertMessage("");
                    interactiveMap.insertMessage(Territories.get(interactiveMap.selectedTerritoryID).name);
                }
            } else {
				// toggle order menu if unit selected unit is clicked
				if( interactiveMap.selectedTerritoryID == interactiveMap.currentOrder.Unit.Territory.coastParentID)
					interactiveMap.interface.orderMenu.toggle();
				else
					interactiveMap.currentOrder.interactiveMap.setOrderPart(interactiveMap.selectedTerritoryID, coor);
            }
        }
    }
    ;
};

/*
 * Stores some often used error messages.
 */
interactiveMap.errorMessages = {
    noTerritory: function() {
        alert("No territory selected!");
    },
    noUnit: function(terrID) {
        alert("No unit selected (" + Territories.get(terrID).name + ")!");
    },
    noOwnUnit: function(terrID) {
        alert("No own unit selected (" + Territories.get(terrID).name + ")!");
    },
    uncompletedOrder: function() {
        alert(" Different order not finished!");
    },
    noArmy: function(terrID) {
        alert("No " + interactiveMap.parameters.armyName + " selected (" + Territories.get(terrID).name + ")!");
    },
    noCoast: function(terrID) {
        alert("No coast territory selected (" + Territories.get(terrID).name + ")!");
    },
    noFleet: function(terrID) {
        alert("No " + interactiveMap.parameters.fleetName + " selected (" + Territories.get(terrID).name + ")!");
    }
};




/*
 * loads a hidden blank map (without names and units) from the variant ressources that is used to detect the selected territory later
 * @param {string} IAmapPNG (the path for the blank map)
 */
interactiveMap.hiddenMap.load = function() {
    var imgIAmap = new Image();
    var imgIAmapSrc = 'interactiveMap/php/IAgetMap.php?gameID=' + context.gameID;
    imgIAmap.observe('load', function() {
        //new canvas element, which stores the blank map
        interactiveMap.hiddenMap.canvasElement = new Element("canvas", {'width': imgIAmap.width, 'height': imgIAmap.height});
        interactiveMap.hiddenMap.context = interactiveMap.hiddenMap.canvasElement.getContext("2d");
        interactiveMap.hiddenMap.context.drawImage(imgIAmap, 0, 0);
        interactiveMap.hiddenMap.imageData = interactiveMap.hiddenMap.context.getImageData(0, 0, imgIAmap.width, imgIAmap.height);
        //$("mapImage").replace(imgIAmap);
        /*if(colorSea)
         colorSeaTerritories();*/
        interactiveMap.interface.activateButton();
    });
    imgIAmap.onerror = function() {
        var alertWindow = window.open(imgIAmapSrc, '', 'height=100, width=500, scrollbars=yes');
        alertWindow.focus();
    };
    imgIAmap.src = imgIAmapSrc;
};

/*
 * activates/deactivates the interface (enables/disables buttons, loads and replaces map)
 */
interactiveMap.activate = function(activated) {
    if(interactiveMap.activated != activated){
        interactiveMap.activated = activated;
        interactiveMap.visibleMap.load();
        interactiveMap.interface.orderMenu.load();
		interactiveMap.interface.mapUI.adjust(); // shows or hides the mapUI buttons
        if(interactiveMap.activated) {
			 //detect if phase is Builds and sets the orders to "wait" so the user can save at any time.
			if (context.phase == "Builds") {
				interactiveMap.setWait();
		    }
			
            interactiveMap.resetOrder();
            interactiveMap.insertMessage("");
        } //reset IA if IA activated (redraws the map etc)
    }
};


/*
 * prints messages in the "Order-Line"-Element or if not available in the "sendbox"-Element (Game-Messages)
 */
interactiveMap.insertMessage = function(content, successful, skipContent) {
    if(Object.isUndefined(this.orderLineStart))
        if((context.phase=="Builds")&&(MyOrders.length > 0)&&(MyOrders[0].type != "Destroy"))
            this.orderLineStart = "The supply center ";
        else
            this.orderLineStart = "The unit in ";
    
    if (interactiveMap.interface.orderLine != null) {
        if ((content != "")) {
            interactiveMap.interface.orderLine.innerHTML += content;
            if (typeof successful != 'undefined') {
                
                if(!Object.isUndefined(skipContent)&&skipContent)
                    interactiveMap.interface.lastOrder.lastChild.innerHTML = " - "+content;
                else
                    interactiveMap.interface.lastOrder.lastChild.innerHTML = interactiveMap.interface.orderLine.innerHTML.replace("Order-Line: ", " - ");
                
                if (successful)
                    interactiveMap.interface.lastOrder.firstChild.setAttribute('src', 'images/icons/tick.png');
                else
                    interactiveMap.interface.lastOrder.firstChild.setAttribute('src', 'images/icons/cross.png');
                
                interactiveMap.interface.orderLine.innerHTML = "Order-Line: "+this.orderLineStart;
            }
        } else {
            interactiveMap.interface.orderLine.innerHTML = "Order-Line: "+this.orderLineStart;
        }
        interactiveMap.interface.orderLine.scrollTop = interactiveMap.interface.orderLine.scrollHeight;

    } else {
        $("sendbox").value += content;
    }
};