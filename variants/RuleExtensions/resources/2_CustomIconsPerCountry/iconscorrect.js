function IconsCorrect(VariantName, country) {
	MyOrders.map(
		function(OrderObj) {
			newIcon=OrderObj.currentUnitIcon
			OrderObj.unitIconArea.update('<img src="variants/'+VariantName+'/resources/'+newIcon.toLowerCase()+country+'.png" alt="'+newIcon+'" />');
		},this
	);
}

