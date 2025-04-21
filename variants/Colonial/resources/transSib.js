transSibTerritories = ['28','35','29','39','40','30'];

function loadTransSib() {
	// Take two arrays, join them into one longer array and return it
	function snapTogether(startArr, snapOnArr)
	{
		for (var i = 0; i < snapOnArr.length; i++)
			startArr.push(snapOnArr[i]);

		return startArr;
	}
	;

	var transSibLoading = false;

	var updateTransSibOrders = function (currOrder) {
		if (context.countryID != '6')
			return;

		MyOrders.each(
				function (o) {
					if (o.type == 'Move' && transSibTerritories.include(o.Unit.terrID) && o != currOrder) {
						o.updateChoices(o.fromrequirements(['toTerrID', 'viaConvoy'])).map(function (c) {
							this.reHTML(c);
						}, o);
						if (!o.isChanged || transSibLoading)
							o.setSelectsGreen();
						
						o.checkComplete();
					}
				});
	};

	//update SupportChoices on orderloading so TSR-supports are shown correctly
	updateTransSibSupportOrders = function () {
		MyOrders.each(
				function (o) {
					if (o.type == 'Support move') {
						o.updateChoices(o.fromrequirements(['fromTerrID', 'viaConvoy'])).map(function (c) {
							this.reHTML(c);
						}, o);
						o.updateValue('fromTerrID', ordersData.find(function (order) {
							return order.id == o.id;
						}).fromTerrID);
						o.reHTML('fromTerrID');
						o.setSelectsGreen();
						
						o.checkComplete();
					}
				});
	};

	MyOrders.map(function (OrderObj) {
		
		OrderObj.isTransSibOrder = function() {
			return this.type == 'Move' 
					&& transSibTerritories.member(this.toTerrID) 
					&& transSibTerritories.member(this.Unit.terrID) 
					&& this.viaConvoy == 'Yes';
		}

		OrderObj.isTransSibOrderSet = function () {
			return MyOrders.any(function (o) {
				return o.isTransSibOrder();
			});
		};

		OrderObj.updateToTerrChoices = function () {
			switch (this.type) {
				case 'Move':
					this.toTerrChoices = this.Unit.getMoveChoices();

					if (this.Unit.type == 'Army' && (this.Unit.Territory.type == 'Coast' || (transSibTerritories.include(this.Unit.terrID) && this.Unit.countryID == '6')))
					{
						var ttac = new Hash();
						var armylocalchoices = this.Unit.Territory.getBorderTerritories().pluck('id');
						this.toTerrChoices.map(
								function (c) {
									if (armylocalchoices.member(c))
										ttac.set(c, l_t(Territories.get(c).name));
									else if (transSibTerritories.member(c) && transSibTerritories.member(this.Unit.terrID))
										ttac.set(c, l_t(Territories.get(c).name) + ' ' + l_t('(via TSR)'));
									else
										ttac.set(c, l_t(Territories.get(c).name) + ' ' + l_t('(via convoy)'));
								}, this
								);
						this.toTerrChoices = ttac;

						return this.toTerrChoices;
					}
					break;
				case 'Support hold':
					this.toTerrChoices = this.Unit.getSupportHoldChoices();
					break;
				case 'Support move':
					this.toTerrChoices = this.Unit.getSupportMoveToChoices();
					break;
				case 'Convoy':
					this.toTerrChoices = this.Unit.getConvoyToChoices();
					break;
				default:
					this.toTerrChoices = undefined;
					return;
			}

			this.toTerrChoices = this.arrayToChoices(this.toTerrChoices);

			return this.toTerrChoices;
		};

		OrderObj.updateViaConvoyChoices = function () {
			if (this.type != 'Move' || this.toTerrID == '')
				this.viaConvoyChoices = undefined;
			else if (this.viaTransSib())
			{
				this.viaConvoyChoices = new Hash({'Yes': l_t('via TSR')});
				if (this.Unit.getMovableTerritories().member(this.ToTerritory))
					this.viaConvoyChoices.set('No', l_t('via land'));
			}
			else if (this.Unit.type != 'Army' || !this.Unit.convoyLink || !this.Unit.ConvoyGroup.Coasts.member(this.ToTerritory))
				this.viaConvoyChoices = new Hash({'No': l_t('via land')});
			else if (this.Unit.getMovableTerritories().member(this.ToTerritory))
				this.viaConvoyChoices = new Hash({'Yes': l_t('via convoy'), 'No': l_t('via land')});
			else
				this.viaConvoyChoices = new Hash({'Yes': l_t('via convoy')});

			return this.viaConvoyChoices;
		};

		OrderObj.viaTransSib = function () {
			return (this.Unit.countryID == '6'
					&& this.Unit.terrID != this.toTerrID
					&& transSibTerritories.include(this.Unit.terrID)
					&& transSibTerritories.include(this.toTerrID)
					&& !this.isTransSibOrderSet());
		};

		OrderObj.updateValue = function (name, newValue) {
			if (Object.isUndefined(newValue))
				return;

			var updatedChoices = [];

			this.setChanged(true);

			switch (name) {
				case 'type':
					var updateOrders = false;
					if (this.isTransSibOrder())
						updateOrders = true;
					this.type = newValue;
					this.wipe(['toTerrID', 'fromTerrID', 'viaConvoy']);
					this.updaterequirements();
					updatedChoices = this.updateChoices(this.fromrequirements(['toTerrID', 'viaConvoy']));
					if (updateOrders)
						updateTransSibOrders(this);
					break;
				case 'toTerrID':
					var updateOrders = false;
					if (this.isTransSibOrder())
						updateOrders = true;
					this.toTerrID = newValue;
					this.ToTerritory = Territories.get(newValue);
					this.wipe(this.fromrequirements(['fromTerrID', 'viaConvoy']));
					updatedChoices = this.updateChoices(this.fromrequirements(['fromTerrID', 'viaConvoy']));
					if (updateOrders)
						updateTransSibOrders(this);
					break;
				case 'fromTerrID':
					this.fromTerrID = newValue;
					this.FromTerritory = Territories.get(newValue);
					break;
				case 'viaConvoy':
					var updateOrders = false;
					if (this.viaConvoy != newValue && 
							(this.isTransSibOrder() 
								|| (newValue == 'Yes' 
									&& this.type == "Move" 
									&& transSibTerritories.member(this.Unit.terrID) 
									&& transSibTerritories.member(this.toTerrID)
								)
							)
						)
						updateOrders = true;
					this.viaConvoy = newValue;
					if (updateOrders)
						updateTransSibOrders(this);
					break;
			}

			updatedChoices.map(function (c) {
				this.reHTML(c);
			}, this);
		};

	}, this);

	Units.map(function (UnitObj) {
		UnitObj = UnitObj.value;

		UnitObj.getMoveChoices = function () {
			var choices = this.getMovableTerritories().pluck('id');

			if (this.convoyLink && this.type == 'Army')
			{
				this.convoyOptions = this.ConvoyGroup.Coasts.select(this.canConvoyTo, this).pluck('id');
				choices = snapTogether(choices, this.convoyOptions).uniq();
			} else {
				this.convoyOptions = []
			}

			if (!MyOrders[0].isTransSibOrderSet() && transSibTerritories.include(this.terrID) && this.type == 'Army' && this.countryID == '6')
				choices = snapTogether(choices, transSibTerritories.filter(function (t) {
					return t != this.terrID
				}, this)).uniq();

			return choices;
		};

		UnitObj.getSupportMoveFromChoices = function (AgainstTerritory) {
			// Essentially a list of units which can move into the given territory

			// Units bordering the given territory which can move into it
			var PossibleUnits = AgainstTerritory.coastParent.getBorderUnits().select(function (u) {
				return u.canMoveInto(AgainstTerritory);
			}, this);

			if (transSibTerritories.include(AgainstTerritory.id))
				PossibleUnits = snapTogether(PossibleUnits, transSibTerritories.collect(function (terrID) {
					return Territories.get(terrID);
				}).pluck('Unit').compact().filter(function (u) {
					return u.type == 'Army' && u.countryID == '6';
				}));

			// Armies that could be convoyed into the given territory
			if( AgainstTerritory.convoyLink )
			{
				/*
				 * Resource intensive extra check, unnecessary 99% of the time. Leaving this disabled 
				 * means when an invalid support move is selected as a fleet the choice is undone once 
				 * it is selected and put through the check below.
				 * 
				 */ var ConvoyArmies;

				if( this.convoyLink && this.type=='Fleet' && 
					this.ConvoyGroup.Coasts.pluck('id').member(AgainstTerritory.id) )
				{
					// Make sure ConvoyArmies contains no armies which can only reach AgainstTerritory 
					// via a convoy containing this fleet. 
					ConvoyArmies = AgainstTerritory.ConvoyGroup.Armies.select(function(ConvoyArmy) {
						var path=AgainstTerritory.ConvoyGroup.pathArmyToCoastWithoutFleet(ConvoyArmy.Territory, AgainstTerritory, this.Territory);
						if( Object.isUndefined(path) )
							return false;
						else
							return true;
					},this);
				}
				else
				{
					ConvoyArmies = AgainstTerritory.ConvoyGroup.Armies;
				}//*/

				this.convoyOptions=AgainstTerritory.ConvoyGroup.Armies.pluck('Territory').pluck('id');

				PossibleUnits=snapTogether(PossibleUnits,ConvoyArmies);
			} else {
				this.convoyOptions = []
			}
				
			// Return names, excluding the current territory
			return PossibleUnits.pluck('Territory').pluck('coastParent').pluck('id').uniq().reject(
					function (n) {
						return (n == this.Territory.coastParent.id || n == AgainstTerritory.id);
					}, this);
		};
	}, this);

	transSibLoading = true;
	updateTransSibOrders();
	updateTransSibSupportOrders();
	transSibLoading = false;
}