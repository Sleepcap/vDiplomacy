function OneWay_loadOrdersPhase()
{	
	MyOrders.map(function(OrderObj)
	{
			OrderObj.updateToTerrChoices = function () {
				if( this.type == 'Disband' )
				{
					this.toTerrChoices = undefined;
					return;
				}
				
				this.toTerrChoices = this.Unit.getMovableTerritories().select(function(t){
					
					if( !Object.isUndefined(t.coastParent.standoff) && t.coastParent.standoff )
						return false;
					else if ( !Object.isUndefined(t.coastParent.Unit) )
						return false;
					else if ( this.Unit.Territory.coastParent.occupiedFromTerrID == t.coastParent.id )
						return false;
					else
						return true;
				},this).pluck('id').uniq();
				
				var index = this.toTerrChoices.indexOf("9");
				if (index != -1) this.toTerrChoices.splice(index, 1);
				
				this.toTerrChoices=this.arrayToChoices(this.toTerrChoices);
				
				return this.toTerrChoices;
			};
		
		OrderObj.requirements.map(function(n){ OrderObj.reHTML(n); },OrderObj);
		
	}, this);
}