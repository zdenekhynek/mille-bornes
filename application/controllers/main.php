<?php 
	
	class Main extends CI_Controller {

		public function __construct() {

			parent::__construct();
			$this->load->helper( "url" );
			$this->load->model( "activities_model" );
			
		}

		public function index() {

			$data[ "title" ] = "Title";
			$data[ "prices" ] = $this->activities_model->get_prices();
			$data[ "total_price" ] = $this->activities_model->get_total_price();
			$data[ "total_distance" ] = $this->activities_model->get_total_distance();
			$data[ "time_diff" ] = $this->activities_model->get_time_differences();
			$data[ "activity_times" ] = $this->activities_model->get_activity_times();
			$data[ "directions_times" ] = $this->activities_model->get_directions_times();
			$data[ "activities" ] = $this->activities_model->get_activities();
			
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