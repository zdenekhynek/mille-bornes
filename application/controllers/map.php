<?php 

	class Map extends CI_Controller {
		
		public function __construct() {

			parent::__construct();

			$this->load->helper( "url" );
			$this->load->model( "activities_model" );

		}

		public function index() {

			$dates = $this->activities_model->get_dates();
			$start_date = $dates[ "start_date" ];
			$end_date = $dates[ "end_date" ];

			$data[ "title"] = "Map";
			$data[ "activities" ] = $this->activities_model->get_activities( $start_date, $end_date );

			print_r( $data[ "activities" ] );

			$this->load->view( "templates/header" ,$data );
			$this->load->view( "pages/map", $data );
			$this->load->view( "templates/footer", $data );

		}

	}

?>
