<?php 
	
	class Admin extends CI_Controller {

		/**
		*	1) User needs to click Strava BTN to get code
		*	2) Take code and request access_tokken
		*	3) Take access_tokken and fetch activities
		**/

		var $access_token;
		var $api;

		public function __construct() {

			require_once( __DIR__."/../libraries/StravaApi.php" );

			parent::__construct();

			$this->load->helper( "url" );
			$this->load->model( "activities_model" );
			
			$client_id = "463";
    		$client_secret = "59f3b10cef9802ebdf9416fe5dd8524be2799007";
    		$this->api = new StravaApi( $client_id, $client_secret );

		}

		public function index() {

			$data[ "title" ] = "Title";
			$data[ "dates" ] = $this->activities_model->get_dates();

			$this->load->view( "templates/header" ,$data );
			$this->load->view( "pages/admin.php", $data );
			$this->load->view( "templates/footer", $data );

		}

		public function update() {
			
			if( isset( $_GET[ "code" ] ) ) {

				//received code from strava, fetch tokken
				$this->fetch_token( $_GET[ "code" ] );
				//have tokken now, can fetch activities - finally!
				if( !empty( $this->access_token ) ) {

					$this->fetch_activities();

				}

			}

			$data[ "title" ] = "Title";
			$data[ "dates" ] = $this->activities_model->get_dates();

			$this->load->view( "templates/header" ,$data );
			$this->load->view( "pages/admin.php", $data );
			$this->load->view( "templates/footer", $data );

		}

		//store dates
		public function dates() {

			$start_date = ( isset( $_REQUEST[ "start_date" ] ) ) ? $_REQUEST[ "start_date" ] : "";
			$end_date = ( isset( $_REQUEST[ "end_date" ] ) ) ? $_REQUEST[ "end_date" ] : "";
			$this->activities_model->store_dates( $start_date, $end_date );

			$data[ "title" ] = "Title";
			$data[ "dates" ] = $this->activities_model->get_dates();

			$this->load->view( "templates/header" ,$data );
			$this->load->view( "pages/admin.php", $data );
			$this->load->view( "templates/footer", $data );


		}

		private function fetch_token( $code ) {

	        $resp = $this->api->tokenExchange( $code );
	        $this->access_token = $resp->access_token;

		}

		private function fetch_activities() {

			$activities = $this->api->get( "activities", $this->access_token );
	       	$this->activities_model->update_activities( $activities );

		}

	}

?>