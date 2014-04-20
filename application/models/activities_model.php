<?php 

	class Activities_model extends CI_Model {

		public $activities = [];

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

		public function get_activity_directions( $id, $start_latlng, $end_latlng ) {

			//Google_Directions_Api::get_directions( $id, $start_latlng, $end_latlng );

		} 
	}

?>