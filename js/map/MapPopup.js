/* global google, MapMarker, Tracking, track_data, dynamicData */
;( function( $ ) {

	"use strict";

	var that = this, $win, $doc, map, popup, $popupEl, $popupInnerWrapper, $popupOrder, $popupName, $popupRaceNumber, $popupTime, offset;

	var MapPopup = function( map_ ) {
		
		$win = $( window );
		$doc = $( document );
		map = map_;

	};

	MapPopup.prototype = {

		init: function() {

			//popup
			$popupEl = $( "<div class='map-popup'></div>" );
			
			popup = new MapMarker( {
				position: new google.maps.LatLng( 50, 15 ),
				element: $popupEl.get( 0 ),
				map: map,
				pane: "floatPane"
			} );

			offset = 25;

		},

		showTrack: function( name, time ) {

			$popupEl.show();
			$popupEl.text( name + ", " + time );

			offset = 15;
		},

		hide: function() {

			$popupEl.hide();
		
		},

		updatePosition: function( latLng ) {

			popup.updatePosition( latLng, new google.maps.Point( +$popupEl.outerWidth( true )/2, +$popupEl.outerHeight( true )*1.1 ) ); 
		
		}

	};

	window.MapPopup = MapPopup;

} )( jQuery );

