<?php
    
	$client_id = "463";
    $client_secret = "59f3b10cef9802ebdf9416fe5dd8524be2799007";
    $strava_auth_url = "https://www.strava.com/oauth/token";
   	
    if( isset( $_GET[ "action" ] ) ) {

    	$action = $_GET[ "action" ];

    	switch( $action ) {

    		case "oauth_token":
    			oauth_token();
    		break;

    	}

    	

    }

    function oauth_token() {

    	global $client_id, $client_secret, $strava_auth_url;

    	if( isset( $_GET[ "code" ] ) ) {

    		$curl = curl_init();
    		curl_setopt_array( $curl, array(
	            CURLOPT_URL => $strava_auth_url,
	            CURLOPT_RETURNTRANSFER => 1,
	            CURLOPT_POST => 1,
	            CURLOPT_POSTFIELDS => array(
	                "client_id" => $client_id,
	                "client_secret" => $client_secret,
	                "code" => $_GET[ "code" ]
	            )
	        ) );
	        $resp = curl_exec( $curl );
	        echo $resp;
	        curl_close( $curl );

    	} else {

    		print( "Error: missing code" );

    	}
    	
    }


   
?>
