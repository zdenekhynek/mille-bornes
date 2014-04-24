var map, firstMarker, secondMarker;
var $document = $( document );
var polylines = [];

function initialize() {
	
	var mapOptions = {
		center: new google.maps.LatLng( 51.548577, -0.120018 ),
		zoom: 11,
		disableDefaultUI: true,
		zoomControl: true,
		zoomControlOptions: {
    		style: google.maps.ZoomControlStyle.SMALL
  		},
		styles: [
		  {
		    "stylers": [
		      { "saturation": -100 },
		      { "lightness": -50 }
		    ]
		  },
		  {
		  	"featureType": "all",
    		"elementType": "labels",
    		"stylers": [ { "visibility": "off" } ]
		  }
		]
    };
    map = new google.maps.Map( document.getElementById( "map-canvas" ), mapOptions );

    //display results
    if( dataActivities ) {
    	var len = dataActivities.length;
    	for( var i = 0; i < len; i++ ) {
    		displayActivity( dataActivities[ i ] );
    	}	
    }
    
}

function displayActivity( activity ) {

	//add activity to map
	var decodedPath = google.maps.geometry.encoding.decodePath( activity.polyline );
	var polyline = new google.maps.Polyline( { 
		path: decodedPath,
		geodesic: true,
	    strokeColor: '#d0c544',
	    strokeOpacity: 0.7,
	    strokeWeight: 2
	});
	polyline.data = { id: activity.id };

	google.maps.event.addListener( polyline, "click", function( evt ) {
		

	} );

	polyline.setMap( map );

}

google.maps.event.addDomListener(window, 'load', initialize);

