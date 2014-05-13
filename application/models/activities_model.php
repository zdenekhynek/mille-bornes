<?php 

	class Activities_model extends CI_Model {

		public $activities = array();

		public function __construct() {
			
			require_once( "activity.php" );
			require_once( "googleDirectionsApi.php" );

			$this->load->database();
		
		}

		public function get_dates() {

			$start_date_query = $this->db->query( "SELECT * FROM options WHERE key_name = 'start_date'" );
			$start_date_result = $start_date_query->result();
			$start_date = "";
			if( count( $start_date_result > 0 ) ) {
				$start_date = $start_date_result[ 0 ]->value; 
			}

			$end_date_query = $this->db->query( "SELECT * FROM options WHERE key_name = 'end_date'" );
			$end_date_result = $end_date_query->result();
			$end_date = "";
			if( count( $end_date_result > 0 ) ) {
				$end_date = $end_date_result[ 0 ]->value; 
			}

			return array( "start_date" => $start_date, "end_date" => $end_date );

		}

		public function store_dates( $start_date, $end_date ) {

			if( !empty( $start_date ) ) {

				$start_date_arr = array(
					"key_name" => "start_date",
					"value" => $start_date );

				//is start_date already inserted
				$query = $this->db->query( "SELECT * FROM options WHERE key_name = 'start_date'" );
				$result = $query->result();

				if( count( $result ) > 0 ) {
					//value exists, do update
					$this->db->where( "key_name", "start_date" );
					$this->db->update( "options", $start_date_arr );
				} else {
					//value doesn't exist
					$this->db->insert( "options", $start_date_arr );
				}

			}

			if( !empty( $end_date ) ) {
				
				$end_date_arr = array(
					"key_name" => "end_date",
					"value" => $end_date );

				//is start_date already inserted
				$query = $this->db->query( "SELECT * FROM options WHERE key_name = 'end_date'" );
				$result = $query->result();

				if( count( $result ) > 0 ) {
					//value exists, do update
					$this->db->where( "key_name", "end_date" );
					$this->db->update( "options", $end_date_arr );
				} else {
					//value doesn't exist
					$this->db->insert( "options", $end_date_arr );
				}

			}


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

		public function get_activities( $start_date, $end_date ) {

			//return $this->db->get( "activities" )->result();
			$query = $this->db->query( "SELECT * FROM activities WHERE start_date >= '" .$start_date. "' AND start_date <= '" .$end_date. "'" );
			return $query->result();
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
		public function get_activity_times( $start_date, $end_date ) {
			
			$query = $this->db->query( "SELECT activities.id, sum(time)/60 as dayTime, CAST(start_date as DATE) as day FROM activities WHERE start_date >= '" .$start_date. "' AND start_date <= '" .$end_date. "' GROUP BY day" );
			return $query->result();

		}

		/**
		*	
		* SELECT sum(duration) as dayDuration, CAST(start_date as DATE) as day FROM activities_directions, activities 
		* WHERE activities_directions.activity_id = activities.id 
		* GROUP BY CAST(start_date AS DATE)
		*
		**/
		public function get_directions_times( $start_date, $end_date ) {
			
			$query = $this->db->query( "SELECT activities.id, sum(duration)/60 as dayTime, CAST(start_date as DATE) as day FROM activities_directions, activities WHERE activities_directions.activity_id = activities.id AND start_date >= '" .$start_date. "' AND start_date <= '" .$end_date. "' GROUP BY day" );
			return $query->result();

		}

		/**
		*	SELECT activities.id, activities.time, activities_directions.activity_id, 
		*	activities_directions.duration, (activities_directions.duration - activities.time) as diff 
		*	FROM activities_directions, activities WHERE activities_directions.activity_id = activities.id 
		*	AND activities_directions.duration != 0
		**/
		public function get_time_differences( $start_date, $end_date ) {
			
			$query = $this->db->query( "SELECT activities.id, activities.time, activities_directions.activity_id, 
			activities_directions.duration, (activities_directions.duration - activities.time) as diff 
			FROM activities_directions, activities WHERE activities_directions.activity_id = activities.id 
			AND activities_directions.duration != 0 AND start_date >= '" .$start_date. "' AND start_date <= '" .$end_date. "'" );
			return $query->result();

		}

		//SELECT SUM(price) as total FROM activities_directions
		public function get_total_price( $start_date, $end_date ) {

			$query = $this->db->query( "SELECT SUM(price) as total FROM activities, activities_directions WHERE activities_directions.activity_id = activities.id AND start_date >= '" .$start_date. "' AND start_date <= '" .$end_date. "'" );
			return $query->row( "total" );

		}

		/**
		*	
		* SELECT name, sum(price), start_date FROM activities_directions, activities 
		* WHERE activities_directions.activity_id = activities.id 
		* GROUP BY CAST(start_date AS DATE)
		*
		**/
		public function get_prices( $start_date, $end_date ) {
			
			$query = $this->db->query( "SELECT sum(price) as daySum, CAST(start_date as DATE) as day FROM activities_directions, activities WHERE activities_directions.activity_id = activities.id AND start_date >= '" .$start_date. "' AND start_date <= '" .$end_date. "' GROUP BY day" );
			return $query->result();

		}

		//SELECT SUM(distance) as total FROM activities
		public function get_total_distance( $start_date, $end_date ) {

			$query = $this->db->query( "SELECT SUM(distance) as total FROM activities WHERE start_date >= '" .$start_date. "' AND start_date <= '" .$end_date. "'" );
			return $query->row( "total" );

		}
		
	}

?>