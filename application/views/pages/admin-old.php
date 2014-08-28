<?php 
	
	$STRAVA_AUTHORIZE_URL = "https://www.strava.com/oauth/authorize?";
	$CLIENT_ID = "463";
	$REDIRECT_URI = "http://localhost:8888/mille-bornes/mille-bornes/index.php/admin/update";

	$url = $STRAVA_AUTHORIZE_URL . "client_id=" .$CLIENT_ID. "&response_type=code&redirect_uri=".$REDIRECT_URI;

?>
 <a href="<?php echo $url; ?>" class="login-with-strava-btn ir">Login-with-strava</a>
           