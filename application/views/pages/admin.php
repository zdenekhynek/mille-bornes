<?php 
	
	$STRAVA_AUTHORIZE_URL = "https://www.strava.com/oauth/authorize?";
	$CLIENT_ID = "463";
	$REDIRECT_URI = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ."/update";

	$url = $STRAVA_AUTHORIZE_URL . "client_id=" .$CLIENT_ID. "&response_type=code&redirect_uri=".$REDIRECT_URI;

	$start_date = isset( $dates ) && isset( $dates[ "start_date" ] ) ? $dates[ "start_date" ] : "";
	$end_date = isset( $dates ) && isset( $dates[ "end_date" ] ) ? $dates[ "end_date" ] : "";

?>
<div class="admin">
	<h1>Admin</h1>
	<div class="date-settings admin-module">
		<form action="<?php echo site_url( "admin/dates" );?>">
			<p>
				<label>Start date:</label>
				<input type="date" name="start_date" value="<?php echo $start_date; ?>"/>
			</p>
			<p>
				<label>End date:</label>
				<input type="date" name="end_date" value="<?php echo $end_date; ?>"/>
			</p>
			<input type="submit" value="Save dates"/>
		</form>
	</div>
	<div class="update-settings admin-module">
		<label>Update data: </label><a href="<?php echo $url; ?>" class="login-with-strava-btn ir">Login-with-strava</a>
	</div>
 </div>