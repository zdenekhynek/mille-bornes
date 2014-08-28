<?php 

	class Activities_model extends CI_Model {

		public $activities = array();

		public function __construct() {
			
			require_once( "activity.php" );
			require_once( "googleDirectionsApi.php" );

			$this->load->database();
		
		}

		public function update_activities( $data ) {

			if( is_array( $data ) ) {

				$len = count( $data );
				
				//for now, always delete all rows
				$this->db->empty_table( "activities" );

				echo "<br />";
				foreach( $data as $activity ) {
					echo "updating activity: " . $activity->id;
					$this->update_activity( $activity );
					echo "<br />";
				}
				
			}

			$this->get_activities_directions();

		}

		private function update_activity( $activity ) {

			//flag if activity has been updated
			$updated = false;

			//check if activity exists

			//if not, add it to database
			$db_data = new Activity( $activity );
			$this->db->insert( "activities", $db_data );

			//check if activity is encoded with directional api
			$query = $this->db->get_where( "activities_directions", array( "activity_id" => $activity->id ) );
			echo $activity->id .",";

			if( count( $query->result() ) == 0 ) {
				array_push( $this->activities, $db_data );
			} else {
				echo ",already exists:";
				echo $activity->id;
			}
			
			return $updated;

		}

		public function get_activities() {

			return $this->db->get( "activities" )->result();

		}

		private function get_activities_directions() {

			$index = 0;
			$len = count( $this->activities );

			for( $i = 0; $i < $len; $i++ ) {

				$activity = $this->activities[ $i ];
				$result = Google_Directions_Api::get_directions( $activity->start_latlng, $activity->end_latlng, $activity->start_date );
				if( isset( $result ) ) {

					$result->activity_id = $activity->id;
					$this->db->insert( "activities_directions", $result );
					echo "getting directions for " .$activity->id;


				}

				sleep( 5 );

				//echo $result;

			}


		}

		/**
		*	
		* SELECT sum(time), CAST(start_date as DATE) as day FROM activities 
		* GROUP BY CAST(start_date AS DATE)
		*
		**/
		public function get_activity_times() {
			
			$query = $this->db->query( "SELECT activities.id, sum(time)/60 as dayTime, CAST(start_date as DATE) as day FROM activities GROUP BY day" );
			return $query->result();

		}

		/**
		*	
		* SELECT sum(duration) as dayDuration, CAST(start_date as DATE) as day FROM activities_directions, activities 
		* WHERE activities_directions.activity_id = activities.id 
		* GROUP BY CAST(start_date AS DATE)
		*
		**/
		public function get_directions_times() {
			
			$query = $this->db->query( "SELECT activities.id, sum(duration)/60 as dayTime, CAST(start_date as DATE) as day FROM activities_directions, activities WHERE activities_directions.activity_id = activities.id GROUP BY day" );
			return $query->result();

		}

		/**
		*	SELECT activities.id, activities.time, activities_directions.activity_id, 
		*	activities_directions.duration, (activities_directions.duration - activities.time) as diff 
		*	FROM activities_directions, activities WHERE activities_directions.activity_id = activities.id 
		*	AND activities_directions.duration != 0
		**/
		public function get_time_differences() {
			
			$query = $this->db->query( "SELECT activities.id, activities.time, activities_directions.activity_id, 
			activities_directions.duration, (activities_directions.duration - activities.time) as diff 
			FROM activities_directions, activities WHERE activities_directions.activity_id = activities.id 
			AND activities_directions.duration != 0" );
			return $query->result();

		}

		//SELECT SUM(price) as total FROM activities_directions
		public function get_total_price() {

			$query = $this->db->query( "SELECT SUM(price) as total FROM activities_directions" );
			return $query->row( "total" );

		}

		/**
		*	
		* SELECT name, sum(price), start_date FROM activities_directions, activities 
		* WHERE activities_directions.activity_id = activities.id 
		* GROUP BY CAST(start_date AS DATE)
		*
		**/
		public function get_prices() {
			
			//$this->db->select( "price" );
			//$query = $this->db->get( "activities_directions" );
			$query = $this->db->query( "SELECT sum(price) as daySum, CAST(start_date as DATE) as day FROM activities_directions, activities WHERE activities_directions.activity_id = activities.id GROUP BY day" );
			return $query->result();

		}

		//SELECT SUM(distance) as total FROM activities
		public function get_total_distance() {

			$query = $this->db->query( "SELECT SUM(distance) as total FROM activities" );
			return $query->row( "total" );

		}

		
	}

?>