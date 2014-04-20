<?php
	
	class Activity_Directions {

		public $activity_id;
		public $duration;
		public $price;

		public function __construct( $params = null ) {

			if( isset( $params->activity_id ) ) $this->activity_id = $params->activity_id;
			if( isset( $params->duration ) ) $this->duration = $params->duration;
			if( isset( $params->price ) ) $this->price = $params->price;

		}

	}

?>