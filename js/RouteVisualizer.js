var RouteVisualizer = function( $el ) {

	this.$el = $el;

}

RouteVisualizer.prototype = {

	clear: function() {

		this.$el.empty();

	},

	showStep: function( step ) {

		console.log( step );
		var html = "<li><span>"+step.distance.text+","+step.duration.text+","+step.travel_mode+"</li>";
		this.$el.append( html );

	}

}