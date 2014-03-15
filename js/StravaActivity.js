var StravaActivity = function( data ) {

	if( data ) {
		this.id = data.id;
		this.data = data;
	
		if( data.map ) {
			console.log( data.map.summary_polyline );
			this.polyline = data.map.summary_polyline;
		} else {
			console.error( "Something wrong with data" );
		}
	}
	

}