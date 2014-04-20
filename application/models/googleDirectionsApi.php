<?php 
	
	class Google_Directions_Api {


		public static function get_directions( $start_latlng, $end_latlng, $departure_time ) {

			require_once( "activity_directions.php" );

			//convert location to proper string
			$start = Google_Directions_Api::format_location_string( $start_latlng );
			$end = Google_Directions_Api::format_location_string( $end_latlng );
			
			//departure_time specifies the desired time of departure as seconds since midnight, January 1, 1970 UTC. The departure time may be specified in two cases:
			//https://developers.google.com/maps/documentation/directions/#TravelModes
			$departure = strtotime( $departure_time );

			echo "<br />" .$start. "<br /><br />";
			echo "<br />" .$end. "<br /><br /><br />";
			echo "<br />" .$departure. "<br /><br /><br />";

			// Our parameters
			$params = array(
			    "origin" => $start,
			    "destination" => $end,
			    "departure_time" => $departure,
			    "mode" => "transit",
			    "sensor" => "false"
			);
			     
			// Join parameters into URL string
			$params_string = "";
			foreach( $params as $var => $val ) {
			    $params_string .= "&" . $var . "=" . urlencode( $val );  
			}
			     
			// Request URL
			$url = "http://maps.googleapis.com/maps/api/directions/json?".ltrim( $params_string, "&" );
			echo $url;

			// Make our API request
			$curl = curl_init();
			curl_setopt( $curl, CURLOPT_URL, $url );
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
			$return = curl_exec( $curl );
			curl_close( $curl );

			$response = Google_Directions_Api::parse_response( $return );
			return $response;

		}
		
		public static function format_location_string( $string ) {

			$arr = explode( ",", $string );
			$arr[ 0 ] = substr( $arr[ 0 ], 1 );
			$arr[ 1 ] = substr( $arr[ 1 ], 0, -1 );
			return implode( ",", $arr );

		}

		public static function parse_response( $response ) {

			$json = json_decode( $response );
			$price = 0;
			$duration = 0;
		
			if( isset( $json->routes ) && is_array( $json->routes ) && count( $json->routes ) > 0 ) {

				//grab first route
				$route = $json->routes[ 0 ];

				if( isset( $route->legs ) && is_array( $route->legs ) && count( $route->legs ) > 0 ) {

					$leg = $route->legs[ 0 ];

					if( isset( $leg->steps ) && is_array( $leg->steps ) ) {

						$steps = $leg->steps;
						$len = count( $steps );

						for( $i = 0; $i < $len; $i++ ) {

							$step = $leg->steps[ $i ];
							$step_price = Google_Directions_Api::parse_step( $step ).",";
							$step_duration = ( isset( $step->duration ) && isset( $step->duration->value ) ) ? $step->duration->value : 0;
							$price = $price + $step_price;
							$duration = $duration + $step_duration;

						}

						//
						$directions = new Activity_Directions();
						$directions->price = $price;
						$directions->duration = $duration;

						return $directions;
					}

				}

			}

			return;

		}

		public static function parse_step( $step ) {

			$price = 0;
			
			if( $step->travel_mode == "TRANSIT" ) {
				
				$transit = $step->transit_details;
				$line = $transit->line;
				//get vehicle
				$vehicle = $line->vehicle;
				$price = Google_Directions_Api::get_price_for_vehicle_ride( $vehicle->type );
			
			}

			return $price;

		}

		public static function get_price_for_vehicle_ride( $type ) {

			$price = 0;
			switch( $type ) {
				case "SUBWAY":
					$price = 3.5;
					break;
				case "BUS":
					$price = 2;
					break;
			}
			
			return $price;
		
		}

	}

?>