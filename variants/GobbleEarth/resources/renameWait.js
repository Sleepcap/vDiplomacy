function RenameWait() {
	MyOrders.map(function(OrderObj) {
		OrderObj.updateTypeChoices = function () {
			switch(this.type)
			{
				case 'Build Army':
				case 'Build Fleet':
				case 'Wait':
						this.typeChoices = $H({'Build Army':l_t('Build an army'),
									'Build Fleet':l_t('Build a fleet'),
									'Wait':l_t('Save for colonial build next year')});
					break;
				case 'Destroy':
						this.typeChoices = $H({'Destroy':l_t('Destroy a unit')});
			}
			
			return this.typeChoices;
		};
		OrderObj.updateTypeChoices();	
		OrderObj.reHTML('type');
	}, this);
}

