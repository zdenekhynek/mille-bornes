<?php 
	
	class Pages extends CI_Controller {

		public function __construct() {

			parent::__construct();

			$this->load->helper('url');
		}

		public function view( $page = "index" ) {

			if( !file_exists( "application/views/pages/" .$page. ".php" ) ) {
				
				show_404();
			}

			$data['title'] = ucfirst($page); // Capitalize the first letter
			$this->load->view( "templates/header" ,$data );
			$this->load->view( "pages/".$page, $data );
			$this->load->view( "templates/footer", $data );

		}

	}

?>