function IconsCorrect(VariantName) {
	MyOrders.map(
		function(OrderObj) {
			newIcon=OrderObj.currentUnitIcon
			OrderObj.unitIconArea.update('<img src="variants/'+VariantName+'/resources/Small'+newIcon.toLowerCase()+'.png" alt="'+newIcon+'" />');
		},this
	);
}

