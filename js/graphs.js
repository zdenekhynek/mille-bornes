var tooltip = d3.select("body")
		.append("div")
		.attr( "class", "graph-tip" )
		.style("position", "absolute")
		.style("z-index", "10")
		.style("visibility", "hidden")
		.text("a simple tooltip");
var $tooltip = $( ".graph-tip" );
var monthNames = [ "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December" ];

/**
*	MONEY CHART
**/
function createMoneyChart() {

	var $figure = $( ".figure" );
	var offset = $figure.offset();
	var margin = 35;
	var bottomMargin = 10;

	var svg = d3.select( ".graph-money .figure" ).append( "svg" )
	    .attr( "width", $figure.width() )
	    .attr( "height", $figure.height() )
	 	.append( "g" );

	//scales
	var y = d3.scale
				.linear()
				.domain( [ 0, d3.max( dataPrices, function( d ) { return parseFloat( d.daySum ); } ) ] )
				.range( [ $figure.height() - bottomMargin, 0 ] );
	var x = d3.scale
				.linear()
				.domain( [ 0, 10 ] )
				.range( [ 0, $figure.width() ] );

	/*var tip = d3.tip()
		.attr('class', 'graph-tip')
		.offset([-10, 0])
		.html(function(d) {
			return "<strong>Frequency:</strong> <span style='color:red'>" + d.frequency + "</span>";
		});
	svg.call(tip);*/
	
	//append bar
	var bars = svg.selectAll( "bar" )
		.data( dataPrices ).enter().append( "rect" )
		.attr( "x", function( d,i ) { return ( i * 15 ) + margin ; } )
	    .attr( "y", function( d ) { return $figure.height() - bottomMargin; } )
	    .attr( "class", "bar" )
 		.attr( "width", "14px" )
	    .attr( "height", function( d ) { return 0; })
	    .on( "mouseover", function( d ) {
	    		
	    	var d3This = d3.select( this ); 
	    	d3This.attr( "class", "bar hover" );
	    	var date = new Date( d.day );
	    	var dateString = monthNames[ date.getMonth()-1 ] + " " + date.getDate();
	    	var savedText = "Saved £" + d.daySum + " on " + dateString + ".";
	    	tooltip.text( savedText ); 
	    	return tooltip.style( "visibility", "visible" );
	    
	    } )
		.on( "mousemove", function( d ) {
			
			var d3This = d3.select( this ); 
			var toolTipY = ( +this.getAttribute( "y" ) + offset.top - $tooltip.outerHeight( true ) - 10 ) + "px";
			var toolTipX = ( +this.getAttribute( "x" ) + offset.left - $tooltip.outerWidth( true )/2 + parseInt( this.getAttribute( "width" ) )/2 ) + "px";
			return tooltip.style( "top", toolTipY ).style( "left", toolTipX );
		
		} )
		.on( "mouseout", function(){
		
			d3.select( this ).attr( "class", "bar" ); 
	    	return tooltip.style( "visibility", "hidden" );
		
		} );
 
	    
	//append y-axis
	var yAxis = d3.svg.axis()
				.scale( y )
				.orient( "left" )
				.ticks( 2 )
				.tickPadding( 6 )
				.tickSize( -$figure.width(), 0, 0)
				.tickFormat( function( d ) { return "£" + d; } );

	svg.append( "g" )
		.attr( "transform", "translate( " +margin+ ", 0 )" )
		.attr( "class", "x axis" )
		.attr( "width", $figure.width() + "px" )
		.call( yAxis );
		
	//append x-axis
	var xAxis = d3.svg.axis()
				.scale( x )
				.orient( "bottom" )
				.ticks( 4 )
				.tickPadding( 0 )
				.tickSize( -$figure.height(), 0, 0)
				.tickFormat( " " );

	svg.append( "g" )
		.attr( "transform", "translate( " + margin + ", " + $figure.height() + " )" )
		.attr( "class", "y axis" )
		.call( xAxis );

	var animPlayed = false;
	var playAnim = function() {

		animPlayed = true;
		bars.transition().duration( 500 ).delay( function( d, i ) { return i*100; } )
		    .attr( "height", function( d ) { return $figure.height() - y( d.daySum ); })
		    .attr( "y", function( d ) { return y( d.daySum ) - bottomMargin } );

	};

	$doc.on( "scroll", function( evt ) {

		var docScrollTop = $doc.scrollTop();
		if( docScrollTop > 500 ) {

			if( !animPlayed ) {
				playAnim();
			}

		}

	} );

}

/**
*	TIME CHART
**/
function createTimeChart() {

	var $figure = $( ".graph-time .figure" );
	var offset = $figure.offset();
	var margin = 65;
	var bottomMargin = 10;

	var svg = d3.select( ".graph-time .figure" ).append( "svg" )
	    .attr( "width", $figure.width() )
	    .attr( "height", $figure.height() )
	 	.append( "g" );

	//scales
	var y = d3.scale
				.linear()
				.domain( [ 0, d3.max( dataActivityTimes, function( d ) { return parseFloat( d.dayTime ); } ) ] )
				.range( [ $figure.height() - bottomMargin, 0 ] );
	var x = d3.scale
				.linear()
				.domain( [ 0, 10 ] )
				.range( [ 0, $figure.width() ] );

	//append directions bar
	var grayBars = svg.selectAll( "bar" )
		.data( dataDurationsTimes ).enter().append( "rect" )
		.attr( "class", "bar gray-bar" )
	    .attr( "x", function( d,i ) { return ( i * 15 ) + margin ; } )
	    .attr( "y", function( d ) { return $figure.height() - bottomMargin; } )
 		.attr( "width", "14px" )
	    .attr( "height", function( d ) { return 0; })
	    .on( "mouseover", function( d ) {
	    		
	    	var d3This = d3.select( this ); 
	    	d3This.attr( "class", "bar hover" );
	    	var date = new Date( d.day );
	    	console.log( d );
	    	var dateString = monthNames[ date.getMonth()-1 ] + " " + date.getDate();
	    	var savedText = "Would spend " + parseInt( d.dayTime ) + " minutes on public transport on " + dateString + "."; 
	    	tooltip.text( savedText ); 
	    	return tooltip.style( "visibility", "visible" );
	    
	    } )
		.on( "mousemove", function( d ) {
			
			var d3This = d3.select( this ); 
			var toolTipY = ( +this.getAttribute( "y" ) + offset.top - $tooltip.outerHeight( true ) - 10 ) + "px";
			var toolTipX = ( +this.getAttribute( "x" ) + offset.left - $tooltip.outerWidth( true )/2 + parseInt( this.getAttribute( "width" ) )/2 ) + "px";
			return tooltip.style( "top", toolTipY ).style( "left", toolTipX );
		
		} )
		.on( "mouseout", function(){
		
			d3.select( this ).attr( "class", "bar gray-bar" ); 
	    	return tooltip.style( "visibility", "hidden" );
		
		} );
	    

	//append activity bar
	var bars = svg.selectAll( "bar" )
		.data( dataActivityTimes ).enter().append( "rect" )
		.attr( "class", "bar" )
	    .attr( "width", "14px" )
		.attr( "x", function( d,i ) { return ( i * 15 ) + margin ; } )
	    .attr( "y", function( d ) { return $figure.height() - bottomMargin; } )
 		.attr( "height", function( d ) { return 0; })
 		.on( "mouseover", function( d ) {
	    		
	    	var d3This = d3.select( this ); 
	    	d3This.attr( "class", "bar hover" );
	    	var date = new Date( d.day );
	    	var dateString = monthNames[ date.getMonth()-1 ] + " " + date.getDate();
	    	var savedText = "Spent " + parseInt( d.dayTime ) + " minutes on bike on " + dateString + "."; 
	    	tooltip.text( savedText ); 
	    	return tooltip.style( "visibility", "visible" );
	    
	    } )
		.on( "mousemove", function( d ) {
			
			var d3This = d3.select( this ); 
			var toolTipY = ( +this.getAttribute( "y" ) + offset.top - $tooltip.outerHeight( true ) - 10 ) + "px";
			var toolTipX = ( +this.getAttribute( "x" ) + offset.left - $tooltip.outerWidth( true )/2 + parseInt( this.getAttribute( "width" ) )/2 ) + "px";
			return tooltip.style( "top", toolTipY ).style( "left", toolTipX );
		
		} )
		.on( "mouseout", function(){
		
			d3.select( this ).attr( "class", "bar" ); 
	    	return tooltip.style( "visibility", "hidden" );
		
		} );
	    

	//append y-axis
	var yAxis = d3.svg.axis()
				.scale( y )
				.orient( "left" )
				.ticks( 2 )
				.tickPadding( 6 )
				.tickSize( -$figure.width(), 0, 0)
				.tickFormat( function( d ) { return d + "MIN"; } );

	svg.append( "g" )
		.attr( "transform", "translate( " +margin+ ", 0 )" )
		.attr( "class", "x axis" )
		.attr( "width", $figure.width() + "px" )
		.call( yAxis );
		
	//append x-axis
	var xAxis = d3.svg.axis()
				.scale( x )
				.orient( "bottom" )
				.ticks( 4 )
				.tickPadding( 0 )
				.tickSize( -$figure.height(), 0, 0)
				.tickFormat( " " );

	svg.append( "g" )
		.attr( "transform", "translate( " + margin + ", " + $figure.height() + " )" )
		.attr( "class", "y axis" )
		.call( xAxis );

	var animPlayed = false;
	var playAnim = function() {

		animPlayed = true;

		grayBars
			.transition().duration( 500 ).delay( function( d, i ) { return 1000 + i*100; } )
	   		.attr( "y", function( d ) { return y( d.dayTime ) - bottomMargin } )
	    	.attr( "height", function( d ) { return $figure.height() - y( d.dayTime ); });

		bars
			.transition().duration( 500 ).delay( function( d, i ) { return 2000 + i*100; } )
	    	.attr( "height", function( d ) { return $figure.height() - y( d.dayTime ); })
	    	.attr( "y", function( d ) { console.log( d, d.dayTime ); return y( d.dayTime ) - bottomMargin } );

	};

	$doc.on( "scroll", function( evt ) {

		var docScrollTop = $doc.scrollTop();
		if( docScrollTop > 500 ) {

			if( !animPlayed ) {
				playAnim();
			}

		}

	} );
	
}

function createTotalChart() {

	var $figure = $( ".figure" );
	var svg = d3.select( ".graph-overall .figure" ).append( "svg" )
	    .attr( "width", $figure.width() )
	    .attr( "height", $figure.height() )
	 	.append( "g" );

	var arc = d3.svg.arc()
	    .innerRadius(50)
	    .outerRadius(70)
	    .startAngle(45 * (Math.PI/180)) //converting from degs to radians
	    .endAngle(3) //just radians

    //make full circle 
    var arc = d3.svg.arc()
    	.innerRadius( 115 )
    	.outerRadius( 140 )
    	.startAngle( 0 ) //converting from degs to radians
	    .endAngle( 360 * ( Math.PI/180 ) );

	svg.append("path")
	    .attr("d", arc)
	    .attr( "class", "graph-overall-back" )
	    .attr("transform", "translate(230,140)" );

	//make distance circle
	var distancePortion = dataTotalDistance/1000;
	var arc = d3.svg.arc()
    	.innerRadius( 125 )
    	.outerRadius( 140 )
    	.startAngle( 0 ) //converting from degs to radians
	    .endAngle( 0 );//distancePortion * 360 * ( Math.PI/180 ) );
	    
	var arcBlueTween = function( d, i, a ) {

		return function( t ) {
			var arc = d3.svg.arc()
    			.innerRadius( 125 )
    			.outerRadius( 140 )
    			.startAngle( 0 ) //converting from degs to radians
	    		.endAngle( ( t * distancePortion ) * 360 * ( Math.PI/180 ) );
	    	return arc(t);
		}
		
	};

	var arcWhiteTween = function( d, i, a ) {

		return function( t ) {
			var arc = d3.svg.arc()
    			.innerRadius( 125 )
    			.outerRadius( 115 )
				.startAngle( 0 ) //converting from degs to radians
	    		.endAngle( ( t * dayPortion ) * 360 * ( Math.PI/180 ) );
	    	return arc(t);
		}
		
	};

	var animPlayed = false;
	var playAnim = function() {

		animPlayed = true;
		frontBlueArc 
			.transition()
	    	.duration( 1000 )
	    	.delay( 250 )
	    	.ease( "linear" )
	    	.attrTween( "d", arcBlueTween );

	    frontWhiteArc
	    	.transition()
	    	.duration( 1000 )
	    	.ease( "linear" )
	    	.attrTween( "d", arcWhiteTween );

	};

	var frontBlueArc = svg.append("path")
	    .attr( "class", "graph-overall-front-blue" )
	    .attr( "transform", "translate(230,140)" )
	    .attr( "d", arc )
	    .each( function(d) { this._endAngle = distancePortion * 360; } );
	    /*.transition()
	    	.duration( 1000 )
	    	.ease( "linear" )
	    	.attrTween( "d", arcTween );*/

	//make days circle
	var dayPortion = dataNumDays/30;
 	arc = d3.svg.arc()
    	.innerRadius( 125 )
    	.outerRadius( 115 )
    	.startAngle( 0 ) //converting from degs to radians
	    .endAngle( 0 ); //.endAngle( dayPortion * 360 * ( Math.PI/180 ) );

	var frontWhiteArc = svg.append("path")
	    .attr("d", arc)
	    .attr( "class", "graph-overall-front-white" )
	    .attr("transform", "translate(230,140)" );

	$doc.on( "scroll", function( evt ) {

		var docScrollTop = $doc.scrollTop();
		if( docScrollTop > 900 ) {

			if( !animPlayed ) {
				playAnim();
			}

		}

	} );

}

var $doc = $( document );

createMoneyChart();
createTimeChart();
createTotalChart();

