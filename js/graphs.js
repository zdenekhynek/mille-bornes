/**
*	MONEY CHART
**/
function createMoneyChart() {

	var $figure = $( ".figure" );
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

	//append bar
	svg.selectAll( "bar" )
		.data( dataPrices ).enter().append( "rect" )
	    .attr( "height", function( d ) { return $figure.height() - y( d.daySum ); })
	    .attr( "width", "14px" )
	    .attr( "x", function( d,i ) { return ( i * 15 ) + margin ; } )
	    .attr( "y", function( d ) { return y( d.daySum ) - bottomMargin } );

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

}

/**
*	TIME CHART
**/
function createTimeChart() {

	var $figure = $( ".figure" );
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
	svg.selectAll( "bar" )
		.data( dataDurationsTimes ).enter().append( "rect" )
	    .attr( "height", function( d ) { return $figure.height() - y( d.dayTime ); })
	    .attr( "width", "14px" )
	    .attr( "x", function( d,i ) { return ( i * 15 ) + margin ; } )
	    .attr( "y", function( d ) { return y( d.dayTime ) - bottomMargin } )
	    .style( "fill", "#858585" );

	//append activity bar
	svg.selectAll( "bar" )
		.data( dataActivityTimes ).enter().append( "rect" )
	    .attr( "height", function( d ) { return $figure.height() - y( d.dayTime ); })
	    .attr( "width", "14px" )
	    .attr( "x", function( d,i ) { return ( i * 15 ) + margin ; } )
	    .attr( "y", function( d ) { console.log( d, d.dayTime ); return y( d.dayTime ) - bottomMargin } );

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
	    .endAngle( distancePortion * 360 * ( Math.PI/180 ) );

	svg.append("path")
	    .attr("d", arc)
	    .attr( "class", "graph-overall-front-blue" )
	    .attr("transform", "translate(230,140)" );

	//make days circle
	var dayPortion = dataNumDays/30;
 	var arc = d3.svg.arc()
    	.innerRadius( 125 )
    	.outerRadius( 115 )
    	.startAngle( 0 ) //converting from degs to radians
	    .endAngle( dayPortion * 360 * ( Math.PI/180 ) );

	svg.append("path")
	    .attr("d", arc)
	    .attr( "class", "graph-overall-front-white" )
	    .attr("transform", "translate(230,140)" );
}

createMoneyChart();
createTimeChart();
createTotalChart();

