/*
    Copyright (C) 2004-2010 Kestas J. Kuliukas
	
	This file is part of webDiplomacy.

    webDiplomacy is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    webDiplomacy is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with webDiplomacy.  If not, see <http://www.gnu.org/licenses/>.
 */
// See doc/javascript.txt for information on JavaScript in webDiplomacy

function loadOrdersPhase() {
	MyOrders.map(function(OrderObj) {

			OrderObj.convoyPath=$A([ ]);
			
			OrderObj.postUpdate=function() {
				// We may need to generate the convoy path to help the server-side order validator
				
				var convoyPath = $A([ ]);
				
				if( this.isComplete )
				{
					if( this.type=='Move' && this.Unit.convoyOptions.any(function(c){ return (c==this.toTerrID); },this) )
					{
						convoyPath = this.Unit.ConvoyGroup.pathArmyToCoast(this.Unit.Territory, this.ToTerritory);
					}
					else if( this.type=='Support move' && this.Unit.convoyOptions.any(function(c){ return (c==this.fromTerrID); },this) )
					{
						if( this.Unit.type=='Fleet' )
						{
							convoyPath = this.ToTerritory.ConvoyGroup.pathArmyToCoastWithoutFleet(
									this.FromTerritory, this.ToTerritory, this.Unit.Territory
								);
						}
						else if( this.ToTerritory.convoyLink )
						{
							convoyPath = this.ToTerritory.ConvoyGroup.pathArmyToCoast(this.FromTerritory, this.ToTerritory);
						}
					}
					else if( this.type=='Convoy' && this.Unit.convoyOptions.any(function(c){ return (c==this.fromTerrID); },this) )
					{
						convoyPath = this.Unit.ConvoyGroup.pathArmyToCoastWithFleet(this.FromTerritory, this.ToTerritory, this.Unit.Territory);
						if( Object.isUndefined(convoyPath) )
							this.wipe(['toTerrID','fromTerrID']);
					}
				}
				
				this.convoyPath = convoyPath;
				
				
				/*
				 * Now we need to perform auto-fill functionality, if it is enabled
				 * (it may be disabled for DATC tests)
				 */  
				if( false && this.autoFill ) {
					
					var thisOrder=this;
					var filterSet=function(fFilter,fSet) { MyOrders.select(fFilter).map(fSet); };
					
					if( Object.isUndefined(this.setAndShow) ) {
						MyOrders.map(function(o){ 
							o.setAndShow=function(n,v){ o.inputValue(n,v); o.reHTML(n); };
						});
					}
					
					switch( this.type ) {
						case "Support hold":
							if( this.isComplete ) {
								filterSet(function(o) {
									return( o.Unit.Territory.id==thisOrder.ToTerritory.id && o.type=='Move' );
								}, function(o){
									o.setAndShow('type','Hold');
								});
							}
							break;
							
						case "Support move":
							if( !Object.isUndefined(this.ToTerritory) ) {
								// We have toTerr, where we are supporting to

								MyOrders.select(function(o) { 
									return( o.Unit.Territory.id==thisOrder.ToTerritory.id && o.type!='Move' );
								}).map(function(o){
									o.setAndShow('type','Move');
								});
							}
							
							if( !Object.isUndefined(this.FromTerritory) ) {
								// We have fromTerr, where we are supporting from

								MyOrders.map(function(o) {
									var convoyingArmyList=MyOrders.select(function(o) { 
										return( o.Unit.Territory.id==thisOrder.FromTerritory.id );
									});
									
									convoyingArmyList.map(function(o){
										o.setAndShow('type','Move');
									});
									
									convoyingArmyList.select(function(o) { 
										return( Object.isUndefined(o.ToTerritory)||o.ToTerritory.id!=thisOrder.ToTerritory.id );
									}).map(function(o){
										o.setAndShow('toTerrID',thisOrder.ToTerritory.id.toString());
									});
								},this);
							}
							
							break;
						
						case "Convoy":
							if( !Object.isUndefined(this.ToTerritory) ) {
								// We have toTerr, where we are convoying to
								
								// If it's one of ours it had better move (had it?)
								MyOrders.select(function(o) {
									return ( o.Unit.Territory.id==thisOrder.ToTerritory.id && o.type!='Move' );
								}).map(function(o){
									setVal(o,'type','Move');
								});
							}
							
							if( !Object.isUndefined(this.FromTerritory) ) {
								// We have fromTerr, where we are convoying from

								// If it's one of ours it had better move to where we're convoying it
								MyOrders.select(function(o){
									return (o.Unit.Territory.id==thisOrder.FromTerritory.id);
								}).map(function(o){
									setVal(o,'type','Move');
									setVal(o,'toTerrID',thisOrder.ToTerritory.id.toString());
									setVal(o,'viaConvoy','Yes');
								});
							}
							break;
					}
				}
			};
			
			OrderObj.updaterequirements = function () {
			
				var oldrequirements = this.requirements;
				
				switch( this.type ) {
					case 'Move': 
						this.requirements = [ 'type','toTerrID','viaConvoy' ];
						break;
					case 'Support hold':
						this.requirements = [ 'type','toTerrID' ];
						break;
					case 'Support move':
						this.requirements = [ 'type','toTerrID','fromTerrID' ];
						break;
					case 'Convoy':
						this.requirements = [ 'type','toTerrID','fromTerrID' ];
						break;
					default:
						this.requirements = ['type']; 
				}
				
				this.wipe(oldrequirements.reject(function(r){return this.requirements.member(r);},this));
				
			};
			
			OrderObj.updateTypeChoices = function () {
				this.typeChoices = {
					'Hold': 'hold', 'Move': 'move', 'Support hold': 'support hold', 'Support move': 'support move'
				};
				
				if( this.Unit.type == 'Fleet' && (this.Unit.Territory.type == 'Sea' || this.Unit.Territory.type == 'Strait') )
					this.typeChoices['Convoy']='convoy';
				
				return this.typeChoices;
			};
			
			OrderObj.updateToTerrChoices = function () {
				switch( this.type ) {
					case 'Move': 
						this.toTerrChoices = this.Unit.getMoveChoices();
						
						if( this.Unit.type=='Army' && (this.Unit.Territory.type=='Coast' || this.Unit.Territory.type=='Strait') )
						{
							var ttac = new Hash();
							var armylocalchoices = this.Unit.getMovableTerritories().pluck('id');
							this.toTerrChoices.map(
									function(c) {
										if( armylocalchoices.member(c) )
											ttac.set(c, Territories.get(c).name);
										else
											ttac.set(c, Territories.get(c).name+' (via convoy)');
									}
								);
							this.toTerrChoices = ttac;
							
							return this.toTerrChoices;
						}
						break;
					case 'Support hold': this.toTerrChoices = this.Unit.getSupportHoldChoices(); break;
					case 'Support move': this.toTerrChoices = this.Unit.getSupportMoveToChoices(); break;
					case 'Convoy': this.toTerrChoices = this.Unit.getConvoyToChoices(); break;
					default: this.toTerrChoices = undefined; return;
				}
				
				this.toTerrChoices=this.arrayToChoices(this.toTerrChoices);
				
				return this.toTerrChoices;
			};
			
			OrderObj.updateFromTerrChoices = function () {
				if( Object.isUndefined(this.ToTerritory) )
				{
					this.fromTerrChoices = undefined;
				}
				else
				{
					switch( this.type ) {
						case 'Support move': this.fromTerrChoices = this.Unit.getSupportMoveFromChoices(this.ToTerritory); break;
						case 'Convoy': this.fromTerrChoices = this.Unit.getConvoyFromChoices(this.ToTerritory); break;
						default: this.fromTerrChoices = undefined; return;
					}
				}
				
				this.fromTerrChoices=this.arrayToChoices(this.fromTerrChoices);
				
				return this.fromTerrChoices;
			};
			
			OrderObj.updateViaConvoyChoices = function () {
				if( this.type!='Move' || this.toTerrID=='' )
					this.viaConvoyChoices=undefined;
				else if( this.Unit.type!='Army' || !this.Unit.convoyLink || !this.Unit.ConvoyGroup.Coasts.member(this.ToTerritory) )
					this.viaConvoyChoices=new Hash({'No': 'via land'});
				else if( this.Unit.getMovableTerritories().member(this.ToTerritory) )
				{
					this.viaConvoyChoices=new Hash({'Yes': 'convoy', 'No': 'land'});
					if (this.viaConvoy == '')
						this.viaConvoy = 'No';
				}
				else
					this.viaConvoyChoices=new Hash({'Yes': 'via convoy'});
				
				return this.viaConvoyChoices;
			};
			
			OrderObj.beginHTML = function () {
				return 'The '+this.Unit.type.toLowerCase()+' at '+this.Unit.Territory.name+' ';
			};
			OrderObj.typeHTML = function () {
				return this.formDropDown('type',this.typeChoices,this.type);
			};
			OrderObj.toTerrHTML = function () {
				var toTerrID=this.formDropDown('toTerrID',this.toTerrChoices,this.toTerrID);
				
				if( toTerrID == '' ) return '';
				
				var ToUnitType;
				if( Object.isUndefined(this.ToTerritory) || Object.isUndefined(this.ToTerritory.Unit) )
					ToUnitType = 'unit';
				else
					ToUnitType = this.ToTerritory.Unit.type.toLowerCase();
					
				switch(this.type) {
					
					case 'Move': return ' to '+toTerrID;
					case 'Support hold': return ' the '+ToUnitType+' in '+toTerrID;
					case 'Support move': return ' to '+toTerrID;
					case 'Convoy': return ' an army to '+toTerrID;
					default: return '';
				}
			};
			OrderObj.fromTerrHTML = function () {
				if( this.toTerrID == '' ) return '';
				
				var fromTerrID=this.formDropDown('fromTerrID',this.fromTerrChoices,this.fromTerrID);
					
				switch(this.type) {
					case 'Support move': return ' from '+fromTerrID;
					case 'Convoy': return ' from '+fromTerrID;
					default: return '';
				}
			};
			OrderObj.viaConvoyHTML = function () {
				if( Object.isUndefined(this.viaConvoyChoices) )
					return '';
				else if ( this.viaConvoyChoices.values().length==1 )
					return '<input type="hidden" name="orderForm['+this.id+'][viaConvoy]" value="'+this.viaConvoyChoices.values()[0]+'" />';
				else
					return ' via ' + this.formDropDown('viaConvoy',this.viaConvoyChoices,this.viaConvoy);
			};
			
			OrderObj.load();
		});
}
