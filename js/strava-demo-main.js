//get code param
var strava, codeParam = getURLParameter( "code" );
console.log( codeParam );
if( codeParam && codeParam.length > 0 ) {

	$.ajax( {
		url: "php/stravaProxy.php",
		type: "GET",
		dataType: "json",
		data: { "code" : codeParam, "action": "oauth_token" },
		success: function( data ) {
			initStrava( data );
			if( data && data.athlete ) {
				var name = data.athlete.firstname + " " + data.athlete.lastname;
				$logInStatus.text( "Logged in as " + name + ".");
			}
			
		},
		error: function( xhr ) {
			console.log( "xhr", xhr );
		}


	} );

}

function initStrava( data ) {
 	console.log( "data", data );
	if( data && data.access_token ) {
		
		strava = new Strava( data.access_token );

	} else {
		console.error( "Missing access_token from strava!" );
	}
	


}

function getURLParameter(name) {
    return decodeURI(
        (RegExp(name + '=' + '(.+?)(&|$)').exec(location.search)||[,null])[1]
    );
}


var directionsDisplay;
var directionsService = new google.maps.DirectionsService();
var map, firstMarker, secondMarker;
var $document = $( document );
var routeVisualizer = new RouteVisualizer( $( ".route-visualizer" ) );
var polylines = [];
var stravaPolylines = [];
var highlightedPolyline = null;
var $logInStatus = $( ".log-in-status" );
var $status = $( ".status" );

//https://developers.google.com/maps/documentation/javascript/directions#TransitInformation
function initialize() {
	
	var mapOptions = {
		center: new google.maps.LatLng( 51.548577, -0.120018 ),
		zoom: 11
    };
    map = new google.maps.Map( document.getElementById( "map-canvas" ), mapOptions );
    google.maps.event.addListener( map, "click", onMapClick );

	var origin = new google.maps.LatLng( 51.548577,-0.120018 );
	var destination = new google.maps.LatLng( 51.517436,-0.083172 );
	
	$document.on( "activity", onActivity );
	$document.on( "step", onStep );

	//disable request for now
	return;

	var route = new Route( origin, destination, google.maps.TravelMode.TRANSIT, directionsService );
	route.request();

	

}

function onActivity( event, activity ) {

	//add activity to map
	var decodedPath = google.maps.geometry.encoding.decodePath( activity.polyline );
	var polyline = new google.maps.Polyline( { 
		path: decodedPath,
		geodesic: true,
	    strokeColor: '#'+Math.floor(Math.random()*16777215).toString(16),
	    strokeOpacity: 1.0,
	    strokeWeight: 3
	});
	polyline.data = { id: activity.id };

	google.maps.event.addListener( polyline, "click", function( evt ) {
		
		var activity = strava.getActivity( this.data.id );
		console.log( "activity", activity );

		//if activity, get route from google directions
		if( highlightedPolyline ) {
			highlightedPolyline.setOptions( { strokeWeight: 3 } );
		}
		highlightedPolyline = this;
		highlightedPolyline.setOptions( { strokeWeight: 6 } );
		
		if( activity.data && activity.data["start_latlng"] && activity.data["end_latlng"] ) {

			console.log( activity.data["start_latlng"] );
			console.log( activity.data["end_latlng"] );
			
			routeVisualizer.clear();
			clearPolylines();

			var origin = new google.maps.LatLng( activity.data["start_latlng"][0],activity.data["start_latlng"][1]);
			var destination = new google.maps.LatLng( activity.data["end_latlng"][0], activity.data["end_latlng"][1]) ;
			var route = new Route( origin, destination, google.maps.TravelMode.TRANSIT, directionsService );
			route.request();

		}

	} );

	polyline.setMap( map );

}

function onMapClick( evt ) {

	console.log( evt.latLng );
	var latLng = evt.latLng;
	var createRequest = false;
	
	if( !firstMarker ) {
		
		//first click to map
		firstMarker = new google.maps.Marker( {

			position: latLng,
			map: map,
			title: "First Marker",
			draggable: true

		} );

	} else if( !secondMarker ) {
		
		//second click to map
		secondMarker = new google.maps.Marker( {

			position: latLng,
			map: map,
			title: "Second Marker",
			draggable: true

		} );

		createRequest = true;

	} else {

		//third or later click
		secondMarker.setPosition( latLng );
		createRequest = true;

	}

	if( createRequest ) { 

		clearPolylines();
		routeVisualizer.clear();

		createNewRoute( firstMarker.getPosition(),secondMarker.getPosition() ); 

	}

}

function createNewRoute( startPoint, endPoint ) {

	var route = new Route( startPoint, endPoint, google.maps.TravelMode.TRANSIT, directionsService );
	route.request();

}

function onStep( event, step ) {

	console.log( "onStep", step );
	routeVisualizer.showStep( step );
	addPolyline( step );

}

function addPolyline( step ) {

	var polyline = new google.maps.Polyline( {

		path: step.lat_lngs,
		geodesic: true,
	    strokeColor: '#000000',
	    strokeOpacity: 1.0,
	    strokeWeight: 1

	} );

	polyline.setMap( map );
	polylines.push( polyline );

}

function clearPolylines() {

	console.log( "clear polylines" );
	var len = polylines.length;
	for( var i = 0; i < len; i++ ) {

		var polyline = polylines[ i ];
		polyline.setMap( null );
	
	}

	polylines = [];

}

google.maps.event.addDomListener(window, 'load', initialize);

