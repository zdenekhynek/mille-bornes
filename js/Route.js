var Route = function( startPoint, endPoint, travelMode, directionsService ){

	this.$document = $( document );
	this.startPoint = startPoint;
	this.endPoint = endPoint;

	this.directionsService;
	this.travelMode;

	this.price = 0;
	this.callback;

	//check we have everything we need
	if( !travelMode ) {
		travelMode = google.maps.TravelMode.TRANSIT;
	}
	this.travelMode = travelMode;

	if( !directionsService ) {
		directionsService = new google.maps.DirectionsService();
	}
	this.directionsService = directionsService;

}

Route.prototype = {

	request: function( startPoint, endPoint, travelMode ) {

		//check if we should modife the request
		if( startPoint ) this.startPoint = startPoint;
		if( endPoint ) this.endPoint = endPoint;
		if( travelMode ) this.travelMode = travelMode;

		//build request
		var request = {
			origin: this.startPoint,
			destination: this.endPoint,
			travelMode: this.travelMode
		};
		
		var self = this;
		this.directionsService.route( request, function( response, status ) {

			if( status == google.maps.DirectionsStatus.OK ) {
				self.parseTransitResponse( response );
			} else {
				alert( "Slow down!" );	
			}
			
		} );

	},

	parseTransitResponse: function( response ) {

		var routes = response.routes;
		if( routes && routes.length > 0 ) {

			//take the first one
			var route = routes[0];

			//take leg
			var legs = route.legs;
			if( legs && legs.length > 0 ) {

				var leg = legs[0];
				console.log( "leg", leg );

				//get steps of the leg
				var steps = leg.steps;
				if( steps ) {

					var len = steps.length;
					for( var i = 0; i < len; i++ ) {

						this.parseStep( steps[i] );

					}

					console.log( "this.price", this.price );
					this.$document.trigger( "finish-step", this.price );
				}

			}
		}

		this.parseComplete();

	},

	parseStep: function( step ) {

		console.log( "step", step );
		this.$document.trigger( "step", step );
		//insterested in step only if it's travel_mode walking
		if( step.travel_mode == google.maps.TravelMode.TRANSIT ) {
			var transit = step.transit;
			var line = transit.line;
			//get vehicle
			var vehicle = line.vehicle;
			this.price += TFLPriceProvider.getPriceForVehicleRide( vehicle.type );
		}

	},

	parseComplete: function() {

		console.log( "parseComplete", this.price );

	}

}