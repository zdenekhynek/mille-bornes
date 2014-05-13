<?php 
	
	class Main extends CI_Controller {

		public function __construct() {

			parent::__construct();
			$this->load->helper( "url" );
			$this->load->model( "activities_model" );
			
		}

		public function index() {

			$dates = $this->activities_model->get_dates();
			$start_date = $dates[ "start_date" ];
			$end_date = $dates[ "end_date" ];

			$data[ "title" ] = "Mille bornes";
			$data[ "prices" ] = $this->activities_model->get_prices( $start_date, $end_date );
			$data[ "total_price" ] = $this->activities_model->get_total_price( $start_date, $end_date );
			$data[ "total_distance" ] = $this->activities_model->get_total_distance( $start_date, $end_date );
			$data[ "time_diff" ] = $this->activities_model->get_time_differences( $start_date, $end_date );
			$data[ "activity_times" ] = $this->activities_model->get_activity_times( $start_date, $end_date );
			$data[ "directions_times" ] = $this->activities_model->get_directions_times( $start_date, $end_date );
			$data[ "activities" ] = $this->activities_model->get_activities( $start_date, $end_date );
			
			$data[ "start_date" ] = $start_date;
			$data[ "end_date" ] = $end_date;

			$total_time_diff = 0;
			foreach( $data[ "time_diff" ] as $key=>$value ) {
				$total_time_diff = $total_time_diff + (float)$value->diff;
			}
			$data[ "total_time_diff" ] = $total_time_diff;
			
			$this->load->view( "templates/header" ,$data );
			$this->load->view( "pages/main.php", $data );
			$this->load->view( "templates/footer", $data );

		}

	}

?>