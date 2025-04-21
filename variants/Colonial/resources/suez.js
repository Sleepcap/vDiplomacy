function loadSuez() {
	// Take two arrays, join them into one longer array and return it
	function snapTogether(startArr, snapOnArr)
	{
		for (var i = 0; i < snapOnArr.length; i++)
			startArr.push(snapOnArr[i]);

		return startArr;
	}
	;

	var suezLoading = false;

	var updateSuezOrders = function () {
		MyOrders.each(
				function (o) {
					if (o.type == 'Move' && Array('99', '101').include(o.Unit.terrID)) {
						o.updateChoices(o.fromrequirements(['toTerrID', 'viaConvoy'])).map(function (c) {
							this.reHTML(c);
						}, o);
						if (!o.isChanged || suezLoading)
							o.setSelectsGreen();
						
						o.checkComplete();
					}
				});
	};

	updateSuezSupportOrders = function () {
		MyOrders.each(
				function (o) {
					if (o.type == 'Support move') {
						o.updateChoices(o.fromrequirements(['fromTerrID', 'viaConvoy'])).map(function (c) {
							this.reHTML(c);
						}, o);
						//o.updateValue('fromTerrID',ordersData.find(function(order){return order.id == o.id;}).fromTerrID);
						o.reHTML('fromTerrID');
						o.setSelectsGreen();
						
						o.checkComplete();
					}
				});
	};

	MyOrders.map(function (OrderObj) {
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

					if (this.Unit.terrID === '99' || this.Unit.terrID === '101')
					{
						var suezMove = new Hash();
						var fleetlocalchoices = this.Unit.Territory.getBorderTerritories().pluck('id');

						this.toTerrChoices.map(
								function (c) {
									if (fleetlocalchoices.member(c))
										suezMove.set(c, l_t(Territories.get(c).name));
									else
										suezMove.set(c, l_t(Territories.get(c).name) + ' ' + l_t('(via Suez Canal)'));
								}
						);

						this.toTerrChoices = suezMove;

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

			if (this.terrID === '99' || this.terrID === '101')
				choices = snapTogether(choices, Array('99', '101').filter(function (t) {
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

			if (Array('99', '101').include(AgainstTerritory.id))
				PossibleUnits = snapTogether(PossibleUnits, Array('99', '101').collect(function (terrID) {
					return Territories.get(terrID);
				}).pluck('Unit').compact());

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

	suezLoading = true;
	updateSuezOrders();
	updateSuezSupportOrders();
	suezLoading = false;



	var suezOrder = MyOrders.find(function (order) {
		return order.Unit.terrID === "126";
	});

	if (!Object.isUndefined(suezOrder))
	{
		suezOrder.beginHTML = function () {
			return '';
		};

		suezOrder.endHTML = function () {
			return l_t(' is allowed to use the Suez Canal.')/*( this.isComplete ? '.' : '...' )*/;
		};

		suezOrder.updateTypeChoices = function () {
			this.typeChoices = {
				'Hold': l_t('No fleet'), 'Support hold': l_t('The fleet in ')
			};
			return this.typeChoices;
		};

		suezOrder.toTerrHTML = function () {
			var toTerrID = this.formDropDown('toTerrID', this.toTerrChoices, this.toTerrID);

			return toTerrID;
		};

		suezOrder.updateToTerrChoices = function () {
			switch (this.type) {
				case 'Support hold':
					this.toTerrChoices = new Array();
					if (!Object.isUndefined(Territories.get('99').Unit))
						this.toTerrChoices.push('99');
					if (!Object.isUndefined(Territories.get('101').Unit))
						this.toTerrChoices.push('101');
					break;
				default:
					this.toTerrChoices = undefined;
					return;
			}

			this.toTerrChoices = this.arrayToChoices(this.toTerrChoices);

			return this.toTerrChoices;
		};


		suezOrder.reHTML('orderBegin');
		suezOrder.updateChoices(suezOrder.fromrequirements(['type', 'toTerrID'])).map(function (c) {
			this.reHTML(c);
		}, suezOrder);
		suezOrder.reHTML('orderEnd');
		suezOrder.setSelectsGreen();
		
		suezOrder.checkComplete();
	}
}