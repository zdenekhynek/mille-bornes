var map, firstMarker, secondMarker;
var $document = $( document );
var polylines = [];

if (!google.maps.Polyline.prototype.getBounds) {
   google.maps.Polyline.prototype.getBounds = function(latLng) {
      var bounds = new google.maps.LatLngBounds();
      var path = this.getPath();
      for (var i = 0; i < path.getLength(); i++) {
         bounds.extend(path.getAt(i));
      }
      return bounds;
   }
}

$( document ).on( "appear-grid", initSingleCommuteMaps );

var mapOptions, mapPopup;
function initialize() {
	
	mapOptions = {
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
    mapPopup = new MapPopup( map );
	mapPopup.init();

    //display results
    if( dataActivities ) {
    	var len = dataActivities.length;
    	for( var i = 0; i < len; i++ ) {
    		displayActivity( dataActivities[ i ] );
    	}	
    }
   	
}

var singleCommuteMapsInited = false;
function initSingleCommuteMaps() {

	if( singleCommuteMapsInited ) {
		return;
	}

	singleCommuteMapsInited = true;

	//initialize all grid maps
    var $singleCommuteMaps = $( ".single-commute-map" );
    var totalCommuteMaps = $singleCommuteMaps.length;
    var commuteMapIndex = 0;

    /*$.each( $singleCommuteMaps, function( i, v ) {

    	var $dom = $( v );
    	var singleCommuteMap = new google.maps.Map( $dom.get( 0 ), mapOptions );
    	var dataPolyline = $dom.attr( "data-polyline" );
    	var decodedPath = google.maps.geometry.encoding.decodePath( dataPolyline );
		var polyline = new google.maps.Polyline( { 
			path: decodedPath,
			geodesic: true,
		    strokeColor: '#d0c544',
		    strokeOpacity: 0.7,
		    strokeWeight: 2
		});
		polyline.setMap( singleCommuteMap );
		singleCommuteMap.fitBounds( polyline.getBounds() );

    } );*/

    var interval = setInterval( function() {

    	var $dom = $singleCommuteMaps.eq( commuteMapIndex );//$( v );
    	var singleCommuteMap = new google.maps.Map( $dom.get( 0 ), mapOptions );
    	var dataPolyline = $dom.attr( "data-polyline" );
    	var decodedPath = google.maps.geometry.encoding.decodePath( dataPolyline );
		var polyline = new google.maps.Polyline( { 
			path: decodedPath,
			geodesic: true,
		    strokeColor: '#d0c544',
		    strokeOpacity: 0.7,
		    strokeWeight: 2
		});
		polyline.setMap( singleCommuteMap );
		singleCommuteMap.fitBounds( polyline.getBounds() );

		commuteMapIndex++;
		if( commuteMapIndex == totalCommuteMaps ) {
			clearInterval( interval );
		}
		
    }, 100 );

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
	polyline.data = { id: activity.id, name: activity.name, time: activity.time };
	console.log( activity );

	google.maps.event.addListener( polyline, "click", function( evt ) {} );
	google.maps.event.addListener( polyline, "mouseover", function( evt ) {

		console.log( this.data );
		polyline.setOptions( { strokeColor: "#ffffff" } );
		mapPopup.showTrack( this.data.name, this.data.time );
		mapPopup.updatePosition( evt.latLng );

	} );
	google.maps.event.addListener( polyline, "mouseout", function( evt ) {

		polyline.setOptions( { strokeColor: "#d0c544" } );
		mapPopup.hide();

	} );

	polyline.setMap( map );

}

google.maps.event.addDomListener(window, 'load', initialize);

