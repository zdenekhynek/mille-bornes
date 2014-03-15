var Strava = function( accessToken ) {

	this.$document = $( document );
	this.token = accessToken;
	this.activities = [];

	this.fetchActivities();

}

Strava.prototype = {

	fetchActivities: function() {

		$status.text( "Fetching activities..." );
		
		var self = this;
		var success = function( data ) {

			var len = i = data.length;
			while( i-- ) {

				var activityData = data[ i ];
				var activity = new StravaActivity( activityData );
				self.activities[ activityData.id ] = activity;
				self.$document.trigger( "activity", activity );

			}
		
			$status.text( " " );
		
		}

		this.ajax( "https://www.strava.com/api/v3/athlete/activities", success );

	},
	
	getActivity: function( id ) {
		return this.activities[ id ];
	},

	ajax: function( url, successCallback ) {

		console.log( "ajax" );
		$.ajax( {

			url: url,
			type: "POST",
			dataType: "jsonp",
			data: {
				access_token: this.token
			},
			success: successCallback,
			error: function( xhr ) {
			
				console.error( xhr );
			
			}

		} );
	}

}
