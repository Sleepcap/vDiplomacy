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

interactiveMap.interface = new Object();

//HTML-Element where the orders, setted via the interface, are shown (and other informations as well)
interactiveMap.interface.orderLine;
//HTML-Element where completed / aborted orders are shown
interactiveMap.interface.lastOrder;

interactiveMap.interface.orderMenu = new Object();
interactiveMap.interface.options = new Object();
interactiveMap.interface.mapUI = new Object();


/*
 * creates the button interface above the map
 */
interactiveMap.interface.create = function() {
	// --- initialisations for mobile version support ---
	
	// add listener which checks for change of window size -> adjust map size for and switch from / to mobile verion if needed
	window.addEventListener("resize", function(){interactiveMap.interface.reload();});
	
	// check if mobile version might be needed
	this.mobileVersion = window.matchMedia( "only screen and (max-width: 800px)" ).matches && localStorage.getItem("desktopEnabled") != "true";

	
	// --- create button interface below the map --
	
    var orderDiv = $("orderDiv"+context.memberID);
    var IAswitch = orderDiv.insertBefore(new Element('div',{'id':'IAswitch', 'class':'gamelistings-tabs'}), $('orderFormElement'));
    //create Pseudolinks to use the tab-styles
    var dropDownInterface = IAswitch.appendChild(new Element('a',{'id':'dropDownInterface','href':'#mapstore','title':'View DropDown-OrderInterface', 'class':'current', 'onclick':'return false;'})).update('DropDown-OrderInterface');
    dropDownInterface.observe('click', function(){interactiveMap.interface.toggle(false);});
    var interactiveInterface = IAswitch.appendChild(new Element('a',{'id':'IAInterface','href':'#mapstore','title':'View InteractiveMap-OrderInterface', 'onclick':'return false;'})).update('InteractiveMap-OrderInterface (loading)');
    interactiveInterface.observe('click', function(){if(interactiveMap.ready) interactiveMap.interface.toggle(true);});
    
    var IADiv = new Element('div', {'id': 'IA', class:'chatWrapper'});
    var saveSubmit = $("ordersNoticeArea"+context.memberID).parentNode;
    $('orderFormElement').insertBefore(IADiv, saveSubmit).hide();
    
    saveSubmit.observe('click', function(){interactiveMap.interface.toggle(false);});    //When saved or submittet, return dropDown-Interface
    
//first row of table
    var tr1 = new Element('tr');
    var tr1td1 = tr1.appendChild(new Element('td', {'style': 'text-align:left'}));
    var tr1td2 = tr1.appendChild(new Element('td', {'style': 'text-align:center'}));
    var tr1td3 = tr1.appendChild(new Element('td', {'style': 'text-align:right'}));
    
    var resetButton = new Element("Button", {'id': 'ResetOrder', 'class':'buttonIA form-submit', 'onclick': 'interactiveMap.abortOrder();', 'disabled': 'true'}).update("Reset Order");
    tr1td1.appendChild(resetButton);
    
    tr1td2.appendChild(interactiveMap.interface.createOrderButtons());
    
    tr1td3.appendChild(new Element("Button", {'id': 'options', 'class':'buttonIA form-submit', 'onclick': 'interactiveMap.interface.options.show()', 'disabled': 'true', 'style': 'text-align:right'})).update("Options");
    tr1td3.appendChild(new Element('button',{'class':'buttonIA form-submit', 'onclick':'window.open("interactiveMap/html/help.html","_blank")'})).update("Help");
    
//second row of table
    var tr3 = new Element('tr');
    interactiveMap.interface.lastOrder = new Element('div',{'style':'text-align:center;'});
    interactiveMap.interface.lastOrder.appendChild(new Element('img', {'id': 'lastOrderSign'}));
    interactiveMap.interface.lastOrder.appendChild(new Element('span',{'id':'content', 'style':'color:rgb(68,68,68)'}).update("..."));
    interactiveMap.interface.lastOrder.hide();
    interactiveMap.interface.orderLine = new Element('p', {'id':'orderLineIA','style': 'background-color:white;text-align:left;'}).hide();
    var tr3td = tr3.appendChild(new Element('td',{'colspan':'3'}));
    tr3td.appendChild(interactiveMap.interface.orderLine);
    tr3td.appendChild(interactiveMap.interface.lastOrder);
    
    IADiv.appendChild(new Element('table', {'id': 'IAtable', 'class':'orders'})).appendChild(tr1).parentNode.appendChild(tr3);

    interactiveMap.interface.orderLine.setStyle({'height': '15px', 'overflow': 'auto'});
    
    $('mapstore').appendChild(new Element('p',{'id':'IAnotice','style':'font-weight: bold;text-align: center;'})).update('The shown orders are a PREVIEW of your currently entered orders!<br>'+((!interactiveMap.autosave)?'They are not saved immediately!':'They were saved immediately!')+"<br><br> Hint: Reset button of order menu covers target territories? <br>Try clicking the unit of the current order again to disable and hide the button.").hide();

	// --- alter eventlistener of DropDown orders to update interactive map if activated
	MyOrders.each(function(order){
		var onChangeOld = order.onChange
		
		order.onChange = function(event){
			onChangeOld.bind(order)(event);
			
			if(interactiveMap.activated)
				interactiveMap.resetOrder();
		}
	});

	// --- add button to mapUI to switch to interactive map
	interactiveMap.interface.mapUI.load();

};

/*
 * creates the specific order-buttons for each phase
 */
interactiveMap.interface.createOrderButtons = function() {
    var orderButtons = new Element('div',{'id':'orderButtons'});
    switch (context.phase) {
        case "Diplomacy":
            orderButtons.appendChild(new Element('button', {'id': 'hold', 'class':'buttonIA form-submit', 'onclick': 'interactiveMap.sendOrder("Hold")', 'disabled': 'true'}).update("HOLD"));
            orderButtons.appendChild(new Element('button', {'id': 'move', 'class':'buttonIA form-submit', 'onclick': 'interactiveMap.sendOrder("Move")', 'disabled': 'true'}).update("MOVE"));
            orderButtons.appendChild(new Element('button', {'id': 'sHold', 'class':'buttonIA form-submit', 'onclick': 'interactiveMap.sendOrder("Support hold")', 'disabled': 'true'}).update("SUPPORT HOLD"));
            orderButtons.appendChild(new Element('button', {'id': 'sMove', 'class':'buttonIA form-submit', 'onclick': 'interactiveMap.sendOrder("Support move")', 'disabled': 'true'}).update("SUPPORT MOVE"));
            orderButtons.appendChild(new Element('button', {'id': 'convoy', 'class':'buttonIA form-submit', 'onclick': 'interactiveMap.sendOrder("Convoy")', 'disabled': 'true'}).update("CONVOY"));
            break;
        case "Builds":
            if (MyOrders.length == 0) {
                orderButtons.appendChild(new Element('p').update("No orders this phase!"));
            } else if (MyOrders[0].type == "Destroy") {
                orderButtons.appendChild(new Element('button', {'id': 'destroy', 'class':'buttonIA form-submit', 'onclick': 'interactiveMap.sendOrder("Destroy")', 'disabled': 'true'}).update("DESTROY"));
            } else {
                orderButtons.appendChild(new Element('button', {'id': 'buildArmy', 'class':'buttonIA form-submit', 'onclick': 'interactiveMap.sendOrder("Build Army")', 'disabled': 'true'}).update("BUILD "+interactiveMap.parameters.armyName.toUpperCase()));
                orderButtons.appendChild(new Element('button', {'id': 'buildFleet', 'class':'buttonIA form-submit', 'onclick': 'interactiveMap.sendOrder("Build Fleet")', 'disabled': 'true'}).update("BUILD "+interactiveMap.parameters.fleetName.toUpperCase()));
                orderButtons.appendChild(new Element('button', {'id': 'wait', 'class':'buttonIA form-submit', 'onclick': 'interactiveMap.sendOrder("Wait")', 'disabled': 'true'}).update("WAIT"));
            }
            break;
        case "Retreats":
            if (MyOrders.length == 0) {
                orderButtons.appendChild(new Element('p').update("No orders this phase!"));
            } else {
                orderButtons.appendChild(new Element('button', {'id': 'retreat', 'class':'buttonIA form-submit', 'onclick': 'interactiveMap.sendOrder("Retreat")', 'disabled': 'true'}).update("RETREAT"));
                orderButtons.appendChild(new Element('button', {'id': 'disband', 'class':'buttonIA form-submit', 'onclick': 'interactiveMap.sendOrder("Disband")', 'disabled': 'true'}).update("DISBAND"));
            }
    }
    return orderButtons;
};

interactiveMap.interface.mobileVersion = false;
/*
 * Reloads the interface if a change in window size is observed.
 * 
 * If no change to mobile version happened -> just readjust srollbars
 * 
 * else: Do also reload orderMenu in case the button size has to be adjusted
 */
interactiveMap.interface.reload = function() {	
	var mobileVersionSwitch = !(interactiveMap.interface.mobileVersion == (window.matchMedia( "only screen and (max-width: 800px)" ).matches && localStorage.getItem("desktopEnabled") != "true"));
	
	if(mobileVersionSwitch){
		// change to mobile version happened
		interactiveMap.interface.mobileVersion = !interactiveMap.interface.mobileVersion;
	
		if(interactiveMap.options.buttonWidthAutomatic)
			// reload the ordermenu interface
			this.orderMenu.reload();
	}
	
	// readjust the scrollbars (only effect if srollbars are enabled in menu)
	if(!Object.isUndefined(interactiveMap.visibleMap.element))
		this.options.updateScrollbars();
}

interactiveMap.interface.orderMenu.reload = function() {
	// reload ordermenu interface (by removing the exisiting one and recall the load function)
	if(!(typeof this.element == "undefined")){
		this.element.remove();
		this.element = undefined;
	}
	
	if(interactiveMap.activated)
		this.load(); //the orderMenu itself can only be securely loaded, if the interactiveMap is activated
		// on activation load is called anyway
}

interactiveMap.interface.orderMenu.load = function() {	
	if(interactiveMap.options.buttonWidthAutomatic){
		if(interactiveMap.interface.mobileVersion)
			interactiveMap.options.buttonWidth = interactiveMap.parameters.largeButtonSize;
		else
			interactiveMap.options.buttonWidth = interactiveMap.parameters.smallButtonSize;
	}
	
	this.create();	// does nothing if menu is already created
}

/*
 * creates the menu that appears when a user clicks on the map
 */
interactiveMap.interface.orderMenu.create = function() {
	if (typeof interactiveMap.interface.orderMenu.element == "undefined") {
        interactiveMap.interface.orderMenu.element = new Element('div', {'id': 'orderMenu'});	
        interactiveMap.interface.orderMenu.element.setStyle({
            position: 'absolute',
            width: '10px'
            //width: '200px'
                    //backgroundColor: 'white'
        });
			
        switch (context.phase) {
            case "Diplomacy":
				interactiveMap.interface.orderMenu.createButtonSet('Hold','hold');
				interactiveMap.interface.orderMenu.createButtonSet('Move','move');
				interactiveMap.interface.orderMenu.createButtonSet('Support hold','support hold');
				interactiveMap.interface.orderMenu.createButtonSet('Support move','support move');
				interactiveMap.interface.orderMenu.createButtonSet('Convoy','convoy');
				break;
            case "Builds":
                if (MyOrders.length == 0) {
                    interactiveMap.interface.orderMenu.element.appendChild(new Element('p', {'style': 'background-color:LightGrey;border:1px solid Grey'}).update("No orders this phase!"));
                } else if (MyOrders[0].type == "Destroy") {
					interactiveMap.interface.orderMenu.createButtonSet('Destroy','destroy');
                } else {
					interactiveMap.interface.orderMenu.createButtonSet('Build Army','build '+interactiveMap.parameters.armyName);
					interactiveMap.interface.orderMenu.createButtonSet('Build Fleet','build '+interactiveMap.parameters.fleetName);
					interactiveMap.interface.orderMenu.createButtonSet('Wait','wait/postpone build');
				}
                break;
            case "Retreats":
                if (MyOrders.length == 0) {
                    interactiveMap.interface.orderMenu.element.appendChild(new Element('p', {'style': 'background-color:LightGrey;border:1px solid Grey'}).update("No orders this phase!"));
                } else {
					interactiveMap.interface.orderMenu.createButtonSet('Retreat','retreat');
					interactiveMap.interface.orderMenu.createButtonSet('Disband','disband');
                }
        }
		       
		$('mapCanDiv').appendChild(interactiveMap.interface.orderMenu.element).hide();
        
                    
        //var orderMenuElements = $A(interactiveMap.interface.orderMenu.element.childNodes);
        
        //orderMenuElements.each(function(element){element.hide(); interactiveMap.interface.orderMenu.showElement(element);});
    }
};

// creates a short name for orderMenu buttons that is used for ids and resource files
interactiveMap.interface.orderMenu.getShortName = function(ordertype){
	return ordertype.replace(/\s/,'');//remove spaces
}

/**
 * Creates a set of button and corresponding reset button. Buttons are added
 * to orderMenu.
 * 
 * @param ordertype: The order type as used everywhere else internally
 * @param name: The name that should be displayed as tooltip
 */
interactiveMap.interface.orderMenu.createButtonSet = function(ordertype, name){
	/*
	 * HTML Structure of order Menu:
	 * Top element: DIV orderMenu
	 * each button:
	 *	<span buttonWrapper for switching visibility of button>
	 *		<span button with actual responses to user input>
	 *			<img imgSources>[<img>]
	 *		</span>
	 *	</span>
	 */
	
	var shortname = interactiveMap.interface.orderMenu.getShortName(ordertype);
	
	var imgSrc = interactiveMap.parameters['img'+shortname];
	
	// the html attributes used for the button wrappers
	var buttonWrapperAttr = {
		'id': 'img'+shortname,
		'style': 'margin-left:5px;\n\
			display:none;'
	};
	
	// attributes and styles for buttons
	var buttonAttr = {
		'title': name,
		'onmouseover': 'this.setStyle({"backgroundColor":"GhostWhite"})',
		'onmouseout': 'this.setStyle({"backgroundColor":"LightGrey"})',
		'onmousedown': 'this.setStyle({"backgroundColor":"LightBlue"})',
		'onmouseup': 'interactiveMap.interface.orderMenu.element.hide()',
		'onclick': 'interactiveMap.sendOrder("'+ordertype+'")'
	};
	var buttonStyles = {
		'background-color': 'LightGrey',
		'border': '1px solid Grey',
		'position':'relative',
		'height':interactiveMap.options.buttonWidth+'px',
		'width':interactiveMap.options.buttonWidth+'px',
		'display':'inline-block',
	}
	
	//style of the image sources
	var imgStyles = {
		'position':'absolute',
		'top':'0px',
		'left': '0px',
		'width':interactiveMap.options.buttonWidth+'px'
	}
	
	// create the order button:
	var orderButton = new Element('span',buttonAttr).setStyle(buttonStyles);
	orderButton.appendChild(new Element('img', {'src':imgSrc}).setStyle(imgStyles));
	
	var orderButtonWrapper = new Element('span', buttonWrapperAttr);
	orderButtonWrapper.appendChild(orderButton);
	
	interactiveMap.interface.orderMenu.element.appendChild(orderButtonWrapper);
	
	// create the reset button:
	// update button specific data
	buttonWrapperAttr.id = 'imgReset'+shortname;
	buttonAttr.onclick = 'interactiveMap.abortOrder()';
	buttonAttr.title = 'reset order: '+ordertype;
	
	var resetButton = new Element('span', buttonAttr).setStyle(buttonStyles);
	// reset button consists of 2 img (reset img and orig order img)
	resetButton.appendChild(new Element('img', {'src': imgSrc}).setStyle(imgStyles));
	resetButton.appendChild(new Element('img', {'src': interactiveMap.parameters.imgReset}).setStyle(imgStyles));
	
	var resetButtonWrapper = new Element('span', buttonWrapperAttr);
	resetButtonWrapper.appendChild(resetButton);
	
	interactiveMap.interface.orderMenu.element.appendChild(resetButtonWrapper);
}

/*
 * adds the needed options and make the orderMenu visible
 */
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
			case 'Builds':
				if (MyOrders.length != 0) {
					if (MyOrders[0].type == "Destroy") {
						if (interactiveMap.currentOrder != null) {
							interactiveMap.interface.orderMenu.element.show();
						}
					} else {
						var SupplyCenter = SupplyCenters.detect(function(sc){return sc.id == interactiveMap.selectedTerritoryID});
						if ((!Object.isUndefined(SupplyCenter)) && (!interactiveMap.isUnitIn(interactiveMap.selectedTerritoryID))) {
							if (!["Coast","Strait"].includes(SupplyCenter.type))
								interactiveMap.interface.orderMenu.hideElement($("imgBuildFleet"));
							else
								interactiveMap.interface.orderMenu.showElement($("imgBuildFleet"));
							interactiveMap.interface.orderMenu.element.show();
						}
					}
				}
				break;
			case 'Diplomacy':
				if (interactiveMap.currentOrder != null) {//||(unit(interactiveMap.selectedTerritoryID)&&(Territories.get(interactiveMap.selectedTerritoryID).type=="Coast")&&(Territories.get(interactiveMap.selectedTerritoryID).Unit.type=="Army")))
					if ((interactiveMap.currentOrder.Unit.type == "Fleet") || !["Coast","Strait"].includes(Territories.get(interactiveMap.selectedTerritoryID).type))
						interactiveMap.interface.orderMenu.hideElement($("imgConvoy"));
					interactiveMap.interface.orderMenu.element.show();
				} else {
					if (["Coast","Strait"].includes(Territories.get(interactiveMap.selectedTerritoryID).type) && !Object.isUndefined(Territories.get(interactiveMap.selectedTerritoryID).Unit) && (Territories.get(interactiveMap.selectedTerritoryID).Unit.type == "Army")) {
						interactiveMap.interface.orderMenu.hideElement($("imgMove"));
						interactiveMap.interface.orderMenu.hideElement($("imgHold"));
						interactiveMap.interface.orderMenu.hideElement($("imgSupportmove"));
						interactiveMap.interface.orderMenu.hideElement($("imgSupporthold"));
						interactiveMap.interface.orderMenu.showElement($("imgConvoy"));
						interactiveMap.interface.orderMenu.element.show();
					}
				}
				break;
			case 'Retreats':
				if (MyOrders.length != 0) {
					if (interactiveMap.currentOrder != null)
						interactiveMap.interface.orderMenu.element.show();
				}
				break;
		}
		
	} 
	
	this.positionMenu(coor);
	this.toggle(true);
};

/*
 * Positions the orderMenu at the given coordinates on the canvas element. If the
 * coordinates are near the edge or outside the map they are adjusted to fit.
 */
interactiveMap.interface.orderMenu.positionMenu = function(coor){
	function getPosition(coor) {
        var width = interactiveMap.interface.orderMenu.element.getWidth();
        if (coor.x < width/2)
            return 0;
        else if (coor.x > (interactiveMap.visibleMap.mainLayer.canvasElement.width - width/2))
            return (interactiveMap.visibleMap.mainLayer.canvasElement.width - width);
        else
            return (coor.x - width/2);
    }
	
    
    var height = interactiveMap.interface.orderMenu.element.getHeight();
    interactiveMap.interface.orderMenu.element.setStyle({
        top: (((coor.y + height)>interactiveMap.visibleMap.mainLayer.canvasElement.height)?interactiveMap.visibleMap.mainLayer.canvasElement.height-height:coor.y+5) + 'px',
        left: getPosition(coor) + 'px'
    });
};
	
/*
 * Activates or deactivates the order menu. 
 * 
 * A deactived menu is shown with transparency and is not clickable. Instead the
 * user can click on territories behind the menu. Main use should be to enable 
 * territory selection for territories hidden behind the menu.
 *  
 * @param {bool} toggleOn : true if the interface should be activated
 */
interactiveMap.interface.orderMenu.toggleState = undefined;
interactiveMap.interface.orderMenu.toggle = function(toggleOn){
	if(Object.isUndefined(toggleOn)) toggleOn = !this.toggleState; // switch state if no arg is given
	else if(this.toggleState == toggleOn) return; // do nothing if state not changed
		
	this.toggleState = toggleOn;
	
	if(toggleOn) {
		interactiveMap.interface.orderMenu.element.setOpacity(1);
		interactiveMap.interface.orderMenu.element.setStyle({zIndex: interactiveMap.visibleMap.mainLayer.canvasElement.style.zIndex + 4});
	} else {
		interactiveMap.interface.orderMenu.element.setOpacity(0.5);
		interactiveMap.interface.orderMenu.element.setStyle({zIndex: interactiveMap.visibleMap.mainLayer.canvasElement.style.zIndex + 2});
	}
}

interactiveMap.interface.orderMenu.showElement = function(element){
    if(element.style.display == "none"){
        element.show();
        interactiveMap.interface.orderMenu.element.style.width = (interactiveMap.interface.orderMenu.element.getWidth()+interactiveMap.options.buttonWidth+parseInt(element.style.marginLeft))+"px";
    }
};

interactiveMap.interface.orderMenu.hideElement = function(element){
    if(element.style.display != "none"){
        element.hide();
        interactiveMap.interface.orderMenu.element.style.width = (interactiveMap.interface.orderMenu.element.getWidth()-interactiveMap.options.buttonWidth-parseInt(element.style.marginLeft))+"px";
    }
};

interactiveMap.interface.orderMenu.hideAll = function(){
	interactiveMap.interface.orderMenu.element.childElements().each(interactiveMap.interface.orderMenu.hideElement);
}

interactiveMap.interface.orderMenu.showAllRegular = function(){
	// display all regular buttons (no reset buttons)
	interactiveMap.interface.orderMenu.element.childElements().each(function(e){if(!e.id.includes("Reset")) interactiveMap.interface.orderMenu.showElement(e)});
}
/*
 * enables/disables the activate-Button
 */
interactiveMap.interface.activateButton = function() {
    interactiveMap.ready = true;
    $("IAInterface").innerHTML = "InteractiveMap-OrderInterface";
    //$("IAswitch").disabled = false;
};


/*
 * Switches between the drop down interface and the interactive interface (the button interface)
 * and toggles the interactive map.
 * detects if phase is Builds and sets the orders to "wait" so the user can save at any time
 */
interactiveMap.interface.toggle = function(switchOn) {
	interactiveMap.activate(switchOn);
	
    var buttons = $("orderButtons").childNodes;
    if (interactiveMap.activated) {
        for (var i = 0; i < buttons.length; i++) {
            buttons[i].disabled = false;
        }
        $("ResetOrder").disabled = false;
        interactiveMap.interface.orderLine.show();
        interactiveMap.interface.lastOrder.show();
        $("options").disabled = false;
        
        $("dropDownInterface").removeClassName('current');
        $("IAInterface").addClassName('current');

        $('IAnotice').show();
        
        $('orderFormElement').childElements().find(function(e){return e.firstDescendant() !== null && e.firstDescendant().tagName === 'TABLE';}).hide(); //ORDER-TABLE
        $('IA').show();
    } else {
        for (var i = 0; i < buttons.length; i++) {
            buttons[i].disabled = true;
        }
        $("ResetOrder").disabled = true;
        interactiveMap.interface.orderLine.hide();
        interactiveMap.interface.lastOrder.hide();
        $("options").disabled = true;
        
        $("dropDownInterface").addClassName('current');
        $("IAInterface").removeClassName('current');

        $('IAnotice').hide();
        
        $('IA').hide();
		$('orderFormElement').childElements().find(function(e){return e.firstDescendant() !== null && e.firstDescendant().tagName === 'TABLE';}).show(); //ORDER-TABLE
    }
};

/*
 * Add an interactive map button to the mapUI to switch to interactive map mode
 * without switching the interface.
 */
interactiveMap.interface.mapUI.load = function() {
	this.maindiv = $$("div.maptools")[0];
	this.buttonIDs = ['Start','Backward','Forward','End','Preview'];
	if($('NoMoves'))
		this.buttonIDs.push('NoMoves');
	
	this.IAbutton = new Element('div',{'class':'button','href':'#', 'onClick':'interactiveMap.interface.mapUI.toggleMap()'});
	this.IAbutton.appendChild(new Element('img',{'id':"InteractiveMap", 'src':"interactiveMap/images/interactive.png",'alt':"InteractiveMap", 'title':"Interactive map mode"}));
	this.IAbutton.insert(' Interactive');
	this.maindiv.insertBefore(this.IAbutton, $('Preview').up());
	this.IAbutton.insert({'after':"\n"});
	
	this.buttonState = new Hash();
	this.buttonIDs.each(function(id){
		this.buttonState.set(id, $(id).up().style.visibility);
	}.bind(this));
	
	this.buttonState.set('History',$('History').style.visibility);
};

/*
 * Toggles the interactive map mode. 
 * 
 * This function does NOT switch between the interactive map interface and the 
 * drop down interface so a mixture of both input modes is possible as well as
 * using the drop down list as a textual or the IA map as a graphical feedback to
 * the oter input mode.
 */
interactiveMap.interface.mapUI.toggleMap = function() {
	if(interactiveMap.ready && !interactiveMap.activated)
		interactiveMap.activate(true);
	else
		interactiveMap.activate(false);
};

/*
 * Check, if the interactive map is actived.
 * 
 * In this case, hide all other buttons (but store visibility for reactivation).
 * Ohterwise show the mapUI again.
 */
interactiveMap.interface.mapUI.adjust = function() {
	if(interactiveMap.activated){
		
		this.buttonIDs.each(function(id){
			this.buttonState.set(id, $(id).up().style.visibility);
			
			$(id).up().style.visibility = 'hidden';
		}.bind(this));
		
		this.buttonState.set('History',$('History').style.visibility);
		$('History').style.visibility = 'hidden';
		
	} else {
		
		this.buttonIDs.each(function(id){
			$(id).up().style.visibility = this.buttonState.get(id);
		}.bind(this));
		
		$('History').style.visibility = this.buttonState.get('History');
		
	}
		
}

/*
 * additional options
 */
interactiveMap.interface.options.show = function() {
    if (typeof interactiveMap.interface.options.element == 'undefined')
        this.load();
    
    this.updateGreyOutIntensity();
    this.updateGreyOut();
    this.updateUnitGreyOut();
    this.updateScrollbars();
	this.updateButtonSize();
    interactiveMap.insertMessage("Options",true, true);
    
    $('options').disabled = true;
    interactiveMap.interface.options.element.show();
};

interactiveMap.interface.options.load = function(){
    function buildSlider() {
        var track = $("track");
        interactiveMap.interface.options.sliderControl = new Control.Slider(track.firstChild, track, {
            range: $R(0.1,0.9),
            sliderValue: interactiveMap.options.greyOutIntensity,
            onChange: function(value) {
                interactiveMap.options.greyOutIntensity = value;
                $("colorBox").setStyle({'backgroundColor':'rgba(0,0,0,'+interactiveMap.options.greyOutIntensity+')'});
                interactiveMap.insertMessage("grey-out intensity changed",true,true);
                interactiveMap.greyOut.cache = new Hash();            
                interactiveMap.resetOrder();
            },
            onSlide: function(value) {
                interactiveMap.options.greyOutIntensity = value;
                $("colorBox").setStyle({'backgroundColor':'rgba(0,0,0,'+interactiveMap.options.greyOutIntensity+')'});
            }
        });
    }
    
    this.element = new Element('div');

    this.element.setStyle({
        position: 'fixed',
        top: "0%",
        left: "25%",
        right: "25%",
        backgroundColor: 'LightGrey',
        zIndex: '20',
        textAlign: 'center',
        display: 'none',
        border: '10px solid black'
    });
    
    interactiveMap.interface.options.element.appendChild(new Element("h1").update("InteractiveMap Options:"));
    this.scrollbarsButton = interactiveMap.interface.options.element.appendChild(new Element("p")).appendChild(new Element("button", {'id': 'largeMap', 'class':'buttonIA form-submit'})).update("Toggle scrollbars on map");
    this.scrollbarsButton.observe('click', this.largeMap.bind(this));
	
	this.buttonSizeButton = interactiveMap.interface.options.element.appendChild(new Element("p")).appendChild(new Element("button", {'id': 'buttonSize', 'class':'buttonIA form-submit'}));
	this.buttonSizeButton.observe('click', this.buttonSize.bind(this));
	
    this.greyOutButton = interactiveMap.interface.options.element.appendChild(new Element("p")).appendChild(new Element("Button", {'id': 'greyOut', 'class':'buttonIA form-submit'}));
    this.greyOutButton.observe('click', this.greyOut.bind(this));
        
    this.greyOutOptions = interactiveMap.interface.options.element.appendChild(new Element("p",{'id':'greyOutOptions','style':'border:1px solid black'})).hide();
        
    this.greyOutUnitButton = this.greyOutOptions.appendChild(new Element("p")).appendChild(new Element("Button", {'id': 'unitGreyOut', 'class':'buttonIA form-submit'}));
    this.greyOutUnitButton.observe('click', this.unitGreyOut.bind(this));
    
    this.greyOutIntensitySlider = this.greyOutOptions.appendChild(new Element("p"));
    this.greyOutIntensitySlider.appendChild(new Element("p", {'style':'color:rgb(68,68,68)'})).update("Change grey-out intensity:");
    this.greyOutIntensitySlider.appendChild(new Element("p", {'id':'colorBox', 'style':'margin-left:auto; margin-right:auto; width:50px; height:20px; background-color:rgba(0,0,0,'+interactiveMap.options.greyOutIntensity+');'}));     
    var track = this.greyOutIntensitySlider.appendChild(new Element("div",{'id':'track', 'class':'buttonIA', 'style':'margin-left:auto; margin-right:auto; width:256px; background-color:GhostWhite; height:10px; position: relative;'}));
    track.appendChild(new Element("div",{'id':'handle', 'style':'width:10px; height:15px; background-color:Red; cursor:move; position: absolute;'}));
        
    this.closeButton = interactiveMap.interface.options.element.appendChild(new Element("p")).appendChild(new Element("Button", {'id': 'close', 'class':'buttonIA form-submit'})).update("Close");
    this.closeButton.observe('click', function(){
		interactiveMap.interface.options.element.hide(); 
		$("options").disabled = false;
						
		/* update user options for interactive map in database (vdip options menu has to be installed!) */
		if(interactiveMap.saveIAoptionsOnDatabase)
			new Ajax.Request('usercp.php',{
				parameters: {
					"userForm[terrGrey]": (!interactiveMap.options.greyOut)?'off':(interactiveMap.options.unitGreyOut)?'all':'selected',
					"userForm[scrollbars]": interactiveMap.options.scrollbars?'Yes':'No',
					"userForm[greyOut]": Math.floor(interactiveMap.options.greyOutIntensity*100),
					"userForm[buttonWidth]":(interactiveMap.options.buttonWidthAutomatic)?'auto':(interactiveMap.options.buttonWidth <= interactiveMap.parameters.smallButtonSize)?'small':'large'
				}
			});
	});
    $('options').parentNode.appendChild(this.element).hide();
        
    buildSlider();
};

interactiveMap.interface.options.updateGreyOutIntensity = function(){
    this.sliderControl.setValue(interactiveMap.options.greyOutIntensity);
};

/*
 * removes scrollbars for large maps
 */
interactiveMap.interface.options.largeMap = function() {
    interactiveMap.options.scrollbars = !interactiveMap.options.scrollbars;
    this.updateScrollbars();
};

interactiveMap.interface.options.updateScrollbars = function(){
    //only adjust width (add srollbars), if really needed because of size of map
	var newWidth = ((interactiveMap.interface.mobileVersion)?$("mapstore").getWidth():new Number(interactiveMap.visibleMap.oldMap.width));	
	
	/*
	 * In case the scrollbars are activated and the available width of the map 
	 * field (newWidth) is smaller than the map, add srollbars to the map.
	 */
	if(interactiveMap.options.scrollbars && newWidth < interactiveMap.hiddenMap.canvasElement.width){		
		interactiveMap.visibleMap.element.setStyle({
			width: newWidth + 'px',
			height: (new Number(interactiveMap.visibleMap.oldMap.height) + 10) + 'px',
			overflow: 'auto',
			left: '0px'
		});
	/*
	 * Else if scrollbars are deactivated but the element still has such (because not
	 * updated yet), remove the scrollbars and print the map with full width (which
	 * might extend the normal size of the webpage).
	 */
    }else if(interactiveMap.visibleMap.element.style.overflow !== 'visible'){
        interactiveMap.visibleMap.element.scrollTop = 0;
        interactiveMap.visibleMap.element.scrollLeft = 0;
        interactiveMap.visibleMap.element.setStyle({
            width: interactiveMap.hiddenMap.canvasElement.width + 'px',
            height: interactiveMap.hiddenMap.canvasElement.height + 'px',
            overflow: 'visible',
            left: '0px'
        });
    }
};


/*
 * Switch between small and large order menu buttons
 */
interactiveMap.interface.options.buttonSize = function() {
	
	// switch between automatic, small, large
	if(interactiveMap.options.buttonWidthAutomatic) {
		interactiveMap.options.buttonWidthAutomatic = false;
		interactiveMap.options.buttonWidth = interactiveMap.parameters.smallButtonSize;
	} else if(interactiveMap.options.buttonWidth <= interactiveMap.parameters.smallButtonSize)
		interactiveMap.options.buttonWidth = interactiveMap.parameters.largeButtonSize;
	else
		interactiveMap.options.buttonWidthAutomatic = true;
	
	this.updateButtonSize();
}

interactiveMap.interface.options.updateButtonSize = function() {
	if(interactiveMap.options.buttonWidthAutomatic)
		interactiveMap.interface.options.buttonSizeButton.update("Switch to SMALL buttons");
	else if(interactiveMap.options.buttonWidth <= interactiveMap.parameters.smallButtonSize) 
		interactiveMap.interface.options.buttonSizeButton.update("Switch to LARGE buttons");
	else
		interactiveMap.interface.options.buttonSizeButton.update("Switch to AUTOMATIC mode");

	// reload order Menu
	interactiveMap.interface.orderMenu.reload();
}

/*
 * toggles the greyOut of territories during the orders
 */
interactiveMap.interface.options.greyOut = function() {
    interactiveMap.options.greyOut = !interactiveMap.options.greyOut;
    this.updateGreyOut();
};

interactiveMap.interface.options.updateGreyOut = function(){
    if(interactiveMap.options.greyOut){
        interactiveMap.interface.options.greyOutButton.update("Deactivate territory-grey-out").disabled = false;
        interactiveMap.interface.options.greyOutOptions.show();
        interactiveMap.insertMessage("territory-grey-out activated",true,true);
        interactiveMap.resetOrder();
    } else {
        interactiveMap.interface.options.greyOutButton.update("Activate territory-grey-out");
        interactiveMap.interface.options.greyOutOptions.hide();
        interactiveMap.insertMessage("territory-grey-out deactivated",true,true);
        interactiveMap.resetOrder();
    }
};

/*
 * toggles the unitGreyOut
 */
interactiveMap.interface.options.unitGreyOut = function() {
    interactiveMap.options.unitGreyOut = !interactiveMap.options.unitGreyOut;
    this.updateUnitGreyOut();
};

interactiveMap.interface.options.updateUnitGreyOut = function(){
    if(interactiveMap.options.unitGreyOut){
        interactiveMap.interface.options.greyOutUnitButton.update("Deactivate highlighting of own units");
        interactiveMap.insertMessage("highlighting of units activated",true,true);
    }else{
        interactiveMap.interface.options.greyOutUnitButton.update("Activate highlighting of own units");
        interactiveMap.insertMessage("highlighting of units deactivated",true,true);
    }
    interactiveMap.resetOrder();
};
