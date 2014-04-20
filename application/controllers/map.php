<?php 

	class Map extends CI_Controller {
		
		public function __construct() {

			parent::__construct();

			$this->load->helper( "url" );
			$this->load->model( "activities_model" );

		}

		public function index() {

			$data[ "title"] = "Map";
			$data[ "activities" ] = $this->activities_model->get_activities();

			$this->load->view( "templates/header" ,$data );
			$this->load->view( "pages/map", $data );
			$this->load->view( "templates/footer", $data );

		}

	}

?>
