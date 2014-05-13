<?php

	//$num_of_days = 22;
	$start_date_unix = strtotime( $start_date );
  $end_date_unix = strtotime( $end_date );
  $date_diff = $end_date_unix - $start_date_unix;
  $num_of_days = floor( $date_diff/( 60 * 60 * 24 ) );
 
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

?>

<header class="main-header">
      <nav>
        <div class="logo" >
          <a href="index.html"><img src="img/nav/logo5.png"></a>
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
          <p>Those nice people helped me achieve this.<em>Thank you<br/>for ever.</em></p>
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

            <div class="graph graph-money">
             <h4>
              Since April 1st 2014 i earned
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

              <a href="" class="CTA">Detailed data</a>  


            <div class=" graph graph-time graph-overall center">
             <h4>
              I’m also trying very hard to reach
            </h4>
              <h3>1000km in 31 days</h3>
              <p>because if i do, I promissed to use
              The money saved in building a bike.
              </p>
              <div class="figure" style="width:450px; height:300px; display:block;">
              	<h4><?php echo $total_distance; ?>/<span clas="total">1000 KM</span></h4>
              	<h5><?php echo $num_of_days ?>/<span clas="total">30 days</span></h5>
              </div>
            </div>

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