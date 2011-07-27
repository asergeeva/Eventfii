<?php
/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
class Event {
	public $organizer;
	public $title;
	public $url;
	public $goal;
	public $address;
	public $date;
	public $time;
	public $deadline;
	public $description;
	public $cost;
	public $is_public;
	public $type;
	public $location_lat;
	public $location_long;
	public $eid;
	public $guests = array();
	private $error;
	private $numErrors;
	
	public function __construct( $organizer, $title, $url, $goal, $address, $date, $time, $deadline, $description, $cost, $is_public, $type, $location_lat, $location_long ) {
		$this->organizer = $organizer;
		$this->title = $title;
		$this->url = $url;
		$this->goal = $goal;
		$this->address = $address;
		$this->date = $date;
		$this->time = $time;
		$this->deadline = $deadline;
		$this->description = $description;
		$this->cost = $cost;
		$this->is_public = $is_public;
		// $this->gets = $gets;
		$this->type = $type;
		$this->location_lat = $location_lat;
		$this->location_long = $location_long;
	}
	
	public function __destruct() {
		
	}

	// Accessor methods
	
	public function get_title() { return $this->title; }
	public function get_description() { return $this->description; }
	public function get_address() { return $this->address; }
	public function get_date() { return $this->date; }
	public function get_time() { return $this->time; }
	public function get_goal() { return $this->goal; }
	public function get_deadline() { return $this->deadline; }
	public function get_type() { return $this->type; }
	public function get_permissions() { return $this->is_public; }
	public function get_lat() { return $this->location_lat; }
	public function get_long() { return $this->location_long; }
	

	/* function get_errors
	 * Function that makes sure that all event fields
	 * are filled and valid.
	 *
	 * return $errors | If there are errors
	 * return false | If there are no errors
	 */
	public function get_errors() {
		// Reset
		$this->numErrors = 0;
		unset($this->error);
	
		// Check for errors
		$this->check_title();
		$this->check_description();
		$this->check_address();
		$this->check_date();
		$this->check_time();
		$this->check_goal();
		$this->check_deadline();
		$this->check_type();
		$this->check_permissions();	
		
		// Return if there are any errors
		if ( $this->numErrors == 0 )
			return false;
		else if ( $this->numErrors == 9 )
			return true;
		else
			return $this->error;
	}

	/* check_title
	 * Checks the event's title
	 *
	 * Requirements:
	 *  - Only alphanumeric characters
	 *  - 5-100 Characters
	 */
	private function check_title() {
		$valid_characters = filter_var(
			$this->title, 
			FILTER_VALIDATE_REGEXP,
			array(
				"options" => array(
					"regexp" => "/^[A-Za-z0-9\s]{5,100}$/"
				)
			)
		);

		// Set the error meessage if there is one
		if( strtolower( $this->title ) == "i'm planning..." ) {
			$this->error['title'] = "Please enter an event title.";
			$this->numErrors++;
		} else if ( ! $valid_characters ) {
			$this->error['title'] = "Title can only contain spaces, characters A-Z or numbers 0-9";
			$this->numErrors++;
		}
	}

	/* check_description
	 * Checks the event's address
	 *
	 * Requirements:
	 *  - Only alphanumeric characters
	 *  - 10-500 characters
	 */
	 private function check_description() {	 
	 	$valid_description = filter_var(
	 		$this->description, 
	 		FILTER_VALIDATE_REGEXP, 
	 		array(
	 			"options" => array(
	 				"regexp" => "/^[A-Za-z0-9\s]{10,500}$/"
	 			)
	 		)
	 	);
	 	
		if( ! $valid_description ) {
			$this->error['desc'] = "Description can only contain spaces, A-Z or 0-9";
			$this->numErrors++;
		}
	 }

	/* check_address
	 * Checks the event's address
	 *
	 * Also sets the location's latitude and longitude
	 * if valid, or removes them if not valid.
	 *
	 * Requirements:
	 *  - Can't contain illegal characters
	 *  - Valid address
	 */
	private function check_address() {
		if ( $this->address == "" ) {
			$this->error['address'] = "Please enter an address";
			$this->numErrors++;
			return;
		}

		$valid_address = filter_var(
			$this->address, 
			FILTER_VALIDATE_REGEXP,
			array(
				"options" => array(
					"regexp" => "/^[A-Za-z0-9\s-,*]*$/"
				)
			)
		);
		
		if( ! ($valid_address) ) {
			$this->error['address'] = "Address can only contain spaces, A-Z, 0-9 or -*,@&";
			$this->numErrors++;
			return;
		}

		// Verify with google that address exists
		require_once('models/Location.class.php');
		$a = urlencode($this->address);
		$event_address = array();
		$geocodeURL = "http://maps.googleapis.com/maps/api/geocode/json?address=$a&sensor=false";
		$ch = curl_init($geocodeURL);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		$result = curl_exec( $ch );
		$httpCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
		curl_close( $ch );
		
		if ($httpCode == 200) {
			$geocode = json_decode($result);
			$this->location_lat = $geocode->results[0]->geometry->location->lat;
			$this->location_long = $geocode->results[0]->geometry->location->lng; 
			$formatted_address = $geocode->results[0]->formatted_address;
			$geo_status = $geocode->status;
			$location_type = $geocode->results[0]->geometry->location_type;
		} else {
			$event_address['location_type'] = "error";
			$this->location_lat = "";
			$this->location_long = "";
		}

		if ( ! ( $location_type == "RANGE_INTERPOLATED" || $location_type == "ROOFTOP" ) ) {
			$this->error['address'] = "Address entered is invalid";
			$this->numErrors++;
		} else {
			$this->address = $formatted_address;
		}
	}

	/* check_date
	 * Checks the event's date
	 *
	 * Requirements:
	 *  - Valid date
	 *  - Date in the future
	 */
	private function check_date() {
		$event_date = explode('/', $this->date); 
		$month = $event_date[0];
		$day = $event_date[1];
		$year = $event_date[2]; 

		// Make sure date is valid
		if( !@checkdate($month, $day, $year) ) {
			$this->error['date'] = "Please enter a valid date in mm/dd/yyyy format";
			$this->numErrors++;
			return;
		}

		// Make sure date is in the future
		$check = @mktime(0, 0, 0, $month, $day, $year,-1);
		$today = @mktime(0, 0, 0, date("m"), date("d"), date("y"), -1);
		if( $check < $today ) {
			$this->error['date'] = "Event date should be a date in the future.";
			$this->numErrors++;
		}
	}
	
	/* check_time
	 * Checks the event's time
	 *
	 * Requirements:
	 *  - 12 hour time format
	 */
	private function check_time() {	
		$valid_time = filter_var(
			$this->time, 
			FILTER_VALIDATE_REGEXP, 
			array(
				"options" => array(
					"regexp" => "/^((0?[1-9]|1[012])(:[0-5]\d){0,2}(\ [AP]M))$|^([01]\d|2[0-3])(:[0-5]\d){0,2}$/"
				)
			)
		);
		
		if( ! $valid_time ) {
			$this->error['time'] = "Please enter a time in 12 hour clock (12:30 PM) format.";
			$this->numErrors++;
		}
	}

	/* check_goal
	 * Checks the event's attendance goal
	 *
	 * Requirements:
	 *  - Number between 0-1000000
	 */
	private function check_goal() {
		$int_options = array(
			"options" => array(
				"min_range" => 1,
				"max_range" => 1000000
			)
		);

		if( ! filter_var($this->goal, FILTER_VALIDATE_INT, $int_options) ) {
			$this->error['goal'] = "Please enter a attendance goal between 1 and 1000000.";
			$this->numErrors++;
		}
			
	}
	
	/* check_deadline
	 * Checks the event's deadine
	 *
	 * Requirements:
	 *  - Valid date
	 *  - In the future
	 *  - Date is before the event date
	 */
	private function check_deadline() {
		$deadline_date = explode('/', $this->deadline); 
		$deadline_month = $deadline_date[0];
		$deadline_day = $deadline_date[1];
		$deadline_year = $deadline_date[2];
		
		// Check for valid date
		if( !@checkdate( $deadline_month, $deadline_day, $deadline_year ) ) {
			$this->error['deadline'] = "Please enter a valid date in mm/dd/yyyy format";
			$this->numErrors++;
			return;
		}

		// Check for date in the future	
		$check = @mktime(0, 0, 0, $deadline_month, $deadline_day, $deadline_year, -1);
		$today = @mktime(0, 0, 0, date("m"), date("d"), date("y"), -1);
		if ( $check < $today ) {
			$this->error['deadline'] = "Deadline date should be a date in the future.";
			$this->numErrors++;
			return;
		}

		// See if the event date is valid
		if ( isset ( $this->error['date'] ) )
			return;

		// Check for date before event date		
		$event_date = explode('/', $this->date); 
		$event_month = $event_date[0];
		$event_day = $event_date[1];
		$event_year = $event_date[2];

		$event_check = @mktime(0, 0, 0, $event_month, $event_day, $event_year, -1);
		if( $event_check < $check ) {
			$this->error['deadline'] = "Deadline date must be before your event date.";
			$this->numErrors++;
			return;
		}
	}

	/* check_type
	 * Checks the event's type
	 *
	 * Requirements:
	 *  - Value is between 0 and 16
	 */
	 private function check_type() {
	 	if ( ! ( $this->type > 0 && $this->type <= 16 ) ) {
			$this->error['type'] = "Please select an event type";
			$this->numErrors++;
		}
	 }

	/* check_permissions
	 * Checks the event's permissions
	 *
	 * Requirements:
	 *  - Value is either 0 or 1
	 */
	 private function check_permissions() {
		 if ( !isset ( $this->is_public ) || ( $this->is_public != 0 && $this->is_public != 1 ) ) {
			$this->error['pub'] = "Please Select the invite type.";
			$this->numErrors++;
		}
	}
		
	public function setGuests($guest_email) {
		$this->guests = array_map('trim', explode(",", $guest_email));
	}
	
	public function setGuestsFromCSV($csvFile) {
		if (($handle = fopen($csvFile, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
				for ($i = 0; $i < sizeof($data); ++$i) {
					if (filter_var($data[$i], FILTER_VALIDATE_EMAIL)) {
						array_push($this->guests, $data[$i]);
					}
				}
			}
			fclose($handle);
		}	
	}
}
