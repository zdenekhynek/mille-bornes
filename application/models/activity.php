<?php 

	class Activity {

		public $id;
		public $athlete_id;
		public $name;
		public $distance;
		public $time;
		public $start_date;
		public $start_latlng;
		public $end_latlng;
		public $polyline;
		public $raw;

		public function __construct( $params ) {

			if( isset( $params->id ) ) $this->id = $params->id;
			
			//dig to get athlete id
			if( isset( $params->athlete ) && isset( $params->athlete->id) ) $this->athlete_id = $params->athlete->id;
			
			if( isset( $params->name ) ) $this->name = $params->name;
			if( isset( $params->distance ) ) $this->distance = $params->distance;
			if( isset( $params->elapsed_time ) ) $this->time = $params->elapsed_time;
			if( isset( $params->private ) ) $this->private = $params->private;
			if( isset( $params->start_date ) ) $this->start_date = $params->start_date;
			if( isset( $params->start_latlng ) ) $this->start_latlng = json_encode( $params->start_latlng );
			if( isset( $params->end_latlng ) ) $this->end_latlng = json_encode( $params->end_latlng );

			//dig to get polyline 
			if( isset( $params->map ) && isset( $params->map->summary_polyline ) ) $this->polyline = $params->map->summary_polyline;
			
			//store the whole response, just in case
			if( isset( $params ) ) $this->raw = json_encode( $params );

		}



	}

?>