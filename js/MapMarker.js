function MapMarker( options ) {

	//initialize all properties.
	this.position_ = options.position;
	this.element = options.element;
	this.map_ = options.map;
	this.anchor_ = options.anchor;
	this.pane_ = ( options.pane ) ? options.pane : "overlayMouseTarget";

	//error messages
	if( !this.position_ || !this.map_ || !this.element ){
		console.error("Missing 'position', 'map' or 'element' in mapMarker options")
		return;
	} 

	//set position to absolute, so that we can set left and top 
	this.element.style.position = "absolute";

	//explicitly call setMap() on this overlay
	this.setMap( this.map_ );
}

MapMarker.prototype = new google.maps.OverlayView();

MapMarker.prototype.onAdd = function() {

	//TODO allow specifying to which pane put markers
	var panes = this.getPanes();
	if( this.pane_ === "overlayMouseTarget" ) {
		panes.overlayMouseTarget.appendChild( this.element );
	} else if( this.pane_ === "floatPane" ) {
		panes.floatPane.appendChild( this.element );
	} else if( this.pane_ === "mapPane" ) {
		panes.mapPane.appendChild( this.element );
	} else if( this.pane_ === "overlayLayer" ) {
		panes.overlayLayer.appendChild( this.element );
	} else if( this.pane_ === "overlayShadow" ) {
		panes.overlayShadow.appendChild( this.element );
	} else if( this.pane_ === "overlayImage" ) {
		panes.overlayImage.appendChild( this.element );
	} else {
		//default
		panes.overlayImage.appendChild( this.element );
	}

}

MapMarker.prototype.draw = function( offset_ ) {

	// retrieve the projection and convert latLng to screen pixels
	var overlayProjection = this.getProjection();
	var mapPosition = overlayProjection.fromLatLngToDivPixel( this.position_ );
	
	//take anchor into account
	if( this.anchor_ ) {
		mapPosition.x -= this.anchor_.x;
		mapPosition.y -= this.anchor_.y;
	}

	//posible offset in px
	if( offset_ ) {
		mapPosition.x -= offset_.x;
		mapPosition.y -= offset_.y;
	}

	//position element
	this.element.style.left = mapPosition.x + 'px';
	this.element.style.top = mapPosition.y + 'px';

}

MapMarker.prototype.onRemove = function() {
	//remove and everything
	this.element.parentNode.removeChild( this.element );
	this.element = null;
}

MapMarker.prototype.updatePosition = function( latLng, offset ) {

	this.position_ = latLng;
	this.draw( offset );

}

MapMarker.prototype.show = function() {
	if( this.element && this.element.style ) {
		this.element.style.display = "block";
	}
}

MapMarker.prototype.hide = function() {
	if( this.element && this.element.style ) {
		this.element.style.display = "none";
	}
}