var TFLPriceProvider = {
}

TFLPriceProvider.getPriceForVehicleRide = function( type ) {

	var price = 0;
	switch( type ) {
		case "SUBWAY":
			price = 3.5;
			break;
		case "BUS":
			price = 2;
			break;
	}

	console.log( price );
	return price;

}