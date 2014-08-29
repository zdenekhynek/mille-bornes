<?php
  
	//var_dump( $durations );
	//var_dump( $prices );
	//var_dump( $directions );
	//var_dump( $activities );
	
	//var_dump( $time_diff );
	//var_dump( $total_price );
	//var_dump( $total_time_diff );
	
	$num_of_days = 22;
	$savings = $total_price;
	$savings_per_day = $total_price / $num_of_days; 
	//round up to two numbers
	$savings_per_day = round( $savings_per_day * 100 ) / 100;
	$savings_per_year = $savings_per_day * 360;

	$time_savings = $total_time_diff / 60;
	//round up to two numbers
	$time_savings = round( $time_savings * 100 ) / 100;
	$time_savings_per_day = $total_time_diff / $num_of_days / 60; 
	//round up to two numbers
	$time_savings_per_day = round( $time_savings_per_day * 100 ) / 100;
	$time_savings_per_year = ( $time_savings_per_day * 360 ) / 60;

  $total_distance = round( $total_distance/1000 );
  //$total_distance = round( $total_distance * 100 ) / 100;

  function seconds_to_time( $seconds ) {

    $hours = floor($seconds / 3600);
    $mins = floor(($seconds - ($hours*3600)) / 60);
    $secs = floor($seconds % 60);

    if( $hours > 0 ) {

      return add_zero( $hours ) . ":" .add_zero( $mins ). ":" .add_zero( $secs ); 

    } else {

      return add_zero( $mins ). ":" .add_zero( $secs );

    }
    
  }

  function add_zero( $string ) {

    if( strlen( $string ) == 1 ) {
      return "0" .$string;
    } 
    return $string;

  }

?>

<header class="main-header">
      <nav>
        <div class="logo" >
          <a href="index.php"><img src="img/nav/logo5.png"></a>
        </div>
        <div class="main-nav">
          <ul>
            <li class="active home"><a href="index.php" ><strong>Home</strong></a></li>
            <li class="what"><a href="about.html" ><strong>What</strong></a></li>
            <li class="support"><a href="support.html" ><strong>Support</strong></a></li>
            <li class="blog"><a href="blog.html" ><strong>Blog</strong></a></li>
          </ul>
        </div>
        <div class="sponsors">
          <em>Thank you<br/>for ever.</em></p>
          <ul style="margin-top:10px">
            <li>Jack | photos</li>
            <li>Zdenek | dev</li>
          </ul>
        </div>
       </nav>
    </header>

    <nav class="social">
      <ul>
        <li><a href="#" class="twitter">T</a></li>
        <li><a href="#" class="facebook">F</a></li>
        <li><a href="#" class="googleplus">G</a></li>
      </ul>
    </nav>
    <div class="map">
      <ul class=legend>
        <li class="commute">Commute</li>
        <li class="training">Training</li>
      </ul>
      <div id="map-canvas" class="map-canvas"></div>
    </div>
    <section class="page-content home ">
      <div id="home-scrollable">
      <header>
      <h1>
        Since the begining of May 2014,<br/>
        Cycling instead of using public transport,<br/>
        saved me <strong><?php echo $time_savings; ?> minutes and £<?php echo $savings; ?></strong>
      </h1>
      <h2>here is how:</h2>
      </header>
          <section id="data-graphs">
            <div class="graph graph-money">
             <h4>
              Since May 1st 2014 i earned
            </h4>
              <h3>£<?php echo $savings; ?></h3>
              <p>Average of <strong>£<?php echo $savings_per_day; ?> a day</strong>, so about <strong>£<?php echo $savings_per_year; ?> a year</strong><br/>
              Imagine the holidays. 
              </p>
              <div class="figure" style="width:450px; height:300px; display:block;"></div>
            </div>

            <div class=" graph graph-time">
             <h4>
              in <?php echo $num_of_days; ?> days, i already saved
            </h4>
              <h3><?php echo $time_savings; ?> mins</h3>
              <p>Average of <strong><?php echo $time_savings_per_day; ?> mins a day</strong>, so about <strong><?php echo $time_savings_per_year; ?> hours a year</strong><br/>
              you could watch all the Star Wars sixteen times.
              </p>
              <div class="figure" style="width:450px; height:300px; display:block;"></div>
            </div>

            <div class="cta-wrapper">
              <a href="#" class="CTA" id="open-detailed-data">Detailed data</a>  
            </div>

            <div class=" graph graph-time graph-overall center">
             <h4>
              I’m also trying very hard to reach
            </h4>
              <h3>1000km in 31 days</h3>
              <p>because if i do, I promissed to use
              The money saved in building a bike.
              </p>
              <div class="figure" style="width:450px; height:300px; display:block;">
              	<h4 style="color:#1cd1a0;"><?php echo $total_distance; ?>/<span clas="total">1000 KM</span></h4>
              	<h5><?php echo $num_of_days ?>/<span clas="total">30 days</span></h5>
              </div>
            </div>
          </section> <!-- end of data graph-->

         <section id="data-grid">

              <div class="cta-wrapper">
                <a href="#" class="CTA" id="close-detailed-data">back to graphs</a>  
              </div>
              <ul class="commute-list clearfix">
                
                <?php
                  
                  //go through directions and store them by id
                  $directions_by_id = array();
                  foreach( $directions as $direction ) {

                    $directions_by_id[ $direction->activity_id ] = $direction;

                  }
                  
                  //create objects we need to display
                  $activities_by_id = array();
                  foreach( $activities as $activity ) {

                    $data = array( 
                      "polyline" => $activity->polyline,
                      "elapsed_time" => $activity->time );

                    if( array_key_exists( $activity->id, $directions_by_id ) ) {

                      $direction = $directions_by_id[ $activity->id ]; 
                      $data[ "direction_time" ] = $direction->duration;
                      $data[ "direction_price" ] = $direction->price;

                    }

                    $activities_by_id[ $activity->id ] = $data;

                  }


                  foreach( $activities_by_id as $activity ) {

                    if( !empty( $activity ) ) {

                      $polyline = ( array_key_exists( "polyline", $activity ) ) ? $activity[ "polyline" ]: "";
                      $elapsed_time = ( array_key_exists( "elapsed_time", $activity ) ) ? seconds_to_time( $activity[ "elapsed_time" ] ): "-";
                      $direction_time = ( array_key_exists( "direction_time", $activity ) ) ? seconds_to_time( $activity[ "direction_time" ] ): "-";
                      $direction_price = ( array_key_exists( "direction_price", $activity ) ) ? "£". number_format( $activity[ "direction_price" ], 2 ): "-";

                    ?>  

                        <li class="single-commute clearfix">
                          <div class="single-commute-map" data-polyline="<?php echo $polyline; ?>"></div> 
                          <!--<img src="img/home/mini-map.jpg" / >-->
                          <ul>
                            <li class="bike">
                              <div class="icon "><img src="img/home/icons/bike.png"></div>
                              <div class="time "><?php echo $elapsed_time; ?></div>
                              <div class="price ">£0.00</div>
                            </li>

                            <li class="tfl">
                              <div class="icon "><img src="img/home/icons/transit-01.png"></div>
                              <div class="time "><?php echo $direction_time; ?></div>
                              <div class="price "><?php echo $direction_price; ?></div>
                            </li>
                            </ul>
                        </li>

                    <?

                    }

                  }

                ?>

                <!--
                <li class="single-commute">
                  <img src="img/home/mini-map.jpg" / >
                  <ul>
                    <li class="bike">
                      <div class="icon "><img src="img/home/icons/bike.png"></div>
                      <div class="time ">33:15</div>
                      <div class="price ">£0.00</div>
                    </li>

                    <li class="tfl">
                      <div class="icon "><img src="img/home/icons/transit-01.png"></div>
                      <div class="time ">51:00</div>
                      <div class="price ">£4.10</div>
                    </li>
                    </ul>
                </li>

                <li class="single-commute">
                  <img src="img/home/mini-map.jpg" / >
                  <ul>
                    <li class="bike">
                      <div class="icon "><img src="img/home/icons/bike.png"></div>
                      <div class="time ">33:15</div>
                      <div class="price ">£0.00</div>
                    </li>

                    <li class="tfl">
                      <div class="icon "><img src="img/home/icons/transit-02.png"></div>
                      <div class="time ">48:00</div>
                      <div class="price ">£2.10</div>
                    </li>
                    </ul>
                </li>
                <li class="single-commute">
                  <img src="img/home/mini-map.jpg" / >
                  <ul>
                    <li class="bike">
                      <div class="icon "><img src="img/home/icons/bike.png"></div>
                      <div class="time ">33:15</div>
                      <div class="price ">£0.00</div>
                    </li>

                    <li class="tfl">
                      <div class="icon "><img src="img/home/icons/transit-02.png"></div>
                      <div class="time ">51:00</div>
                      <div class="price ">£2.10</div>
                    </li>
                    </ul>
                </li>
                <li class="single-commute">
                  <img src="img/home/mini-map.jpg" / >
                  <ul>
                    <li class="bike">
                      <div class="icon "><img src="img/home/icons/bike.png"></div>
                      <div class="time ">33:15</div>
                      <div class="price ">£0.00</div>
                    </li>

                    <li class="tfl">
                      <div class="icon "><img src="img/home/icons/transit-01.png"></div>
                      <div class="time ">51:00</div>
                      <div class="price ">£2.10</div>
                    </li>
                    </ul>
                </li>                <li class="single-commute">
                  <img src="img/home/mini-map.jpg" / >
                  <ul>
                    <li class="bike">
                      <div class="icon "><img src="img/home/icons/bike.png"></div>
                      <div class="time ">33:15</div>
                      <div class="price ">£0.00</div>
                    </li>

                    <li class="tfl">
                      <div class="icon "><img src="img/home/icons/transit-01.png"></div>
                      <div class="time ">51:00</div>
                      <div class="price ">£4.10</div>
                    </li>
                    </ul>
                </li>

                <li class="single-commute">
                  <img src="img/home/mini-map.jpg" / >
                  <ul>
                    <li class="bike">
                      <div class="icon "><img src="img/home/icons/bike.png"></div>
                      <div class="time ">33:15</div>
                      <div class="price ">£0.00</div>
                    </li>

                    <li class="tfl">
                      <div class="icon "><img src="img/home/icons/transit-02.png"></div>
                      <div class="time ">48:00</div>
                      <div class="price ">£2.10</div>
                    </li>
                    </ul>
                </li>
                <li class="single-commute">
                  <img src="img/home/mini-map.jpg" / >
                  <ul>
                    <li class="bike">
                      <div class="icon "><img src="img/home/icons/bike.png"></div>
                      <div class="time ">33:15</div>
                      <div class="price ">£0.00</div>
                    </li>

                    <li class="tfl">
                      <div class="icon "><img src="img/home/icons/transit-02.png"></div>
                      <div class="time ">51:00</div>
                      <div class="price ">£2.10</div>
                    </li>
                    </ul>
                </li>
                <li class="single-commute">
                  <img src="img/home/mini-map.jpg" / >
                  <ul>
                    <li class="bike">
                      <div class="icon "><img src="img/home/icons/bike.png"></div>
                      <div class="time ">33:15</div>
                      <div class="price ">£0.00</div>
                    </li>

                    <li class="tfl">
                      <div class="icon "><img src="img/home/icons/transit-01.png"></div>
                      <div class="time ">51:00</div>
                      <div class="price ">£2.10</div>
                    </li>
                    </ul>
                </li>

                                <li class="single-commute">
                  <img src="img/home/mini-map.jpg" / >
                  <ul>
                    <li class="bike">
                      <div class="icon "><img src="img/home/icons/bike.png"></div>
                      <div class="time ">33:15</div>
                      <div class="price ">£0.00</div>
                    </li>

                    <li class="tfl">
                      <div class="icon "><img src="img/home/icons/transit-01.png"></div>
                      <div class="time ">51:00</div>
                      <div class="price ">£4.10</div>
                    </li>
                    </ul>
                </li>

                <li class="single-commute">
                  <img src="img/home/mini-map.jpg" / >
                  <ul>
                    <li class="bike">
                      <div class="icon "><img src="img/home/icons/bike.png"></div>
                      <div class="time ">33:15</div>
                      <div class="price ">£0.00</div>
                    </li>

                    <li class="tfl">
                      <div class="icon "><img src="img/home/icons/transit-02.png"></div>
                      <div class="time ">48:00</div>
                      <div class="price ">£2.10</div>
                    </li>
                    </ul>
                </li>
                <li class="single-commute">
                  <img src="img/home/mini-map.jpg" / >
                  <ul>
                    <li class="bike">
                      <div class="icon "><img src="img/home/icons/bike.png"></div>
                      <div class="time ">33:15</div>
                      <div class="price ">£0.00</div>
                    </li>

                    <li class="tfl">
                      <div class="icon "><img src="img/home/icons/transit-02.png"></div>
                      <div class="time ">51:00</div>
                      <div class="price ">£2.10</div>
                    </li>
                    </ul>
                </li>
                <li class="single-commute">
                  <img src="img/home/mini-map.jpg" / >
                  <ul>
                    <li class="bike">
                      <div class="icon "><img src="img/home/icons/bike.png"></div>
                      <div class="time ">33:15</div>
                      <div class="price ">£0.00</div>
                    </li>

                    <li class="tfl">
                      <div class="icon "><img src="img/home/icons/transit-01.png"></div>
                      <div class="time ">51:00</div>
                      <div class="price ">£2.10</div>
                    </li>
                    </ul>
                </li> -->

              </ul>
          </section> <!-- end of data grid-->


            </div>
          </section>



    <script>

    	//encode data for js
    	var dataActivities = <?php echo json_encode( $activities ); ?>;
    	var dataPrices = <?php echo json_encode( $prices ); ?>;
    	var dataActivityTimes = <?php echo json_encode( $activity_times ); ?>;
    	var dataDurationsTimes = <?php echo json_encode( $directions_times ); ?>;
      var dataTotalDistance = <?php echo $total_distance; ?>;
      var dataNumDays = <?php echo $num_of_days; ?>;
 
    </script>

