<?php
/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
 
require_once(realpath(dirname(__FILE__)).'/User.class.php');
require_once(realpath(dirname(__FILE__)).'/../libs/QR/qrlib.php');
 
class Event {
	public $alias;
	public $eid;
	public $organizer;
	public $title;
	public $goal;
	public $reach_goal;
	public $location;
	public $address;
	public $datetime;
	public $date;
	public $time;
	
	public $friendly_date;
	public $friendly_time;
	
	public $end_datetime;
	public $end_date;
	public $end_time;
	public $deadline;
	public $rsvp_days_left;
	public $days_left;
	public $description;
	public $is_public;
	public $type;
	public $location_lat;
	public $location_long;
	public $guests = array();
	public $exists;
	
	public $twitter;
	
	public $error;
	public $numErrors;
	
	function __construct( $eventInfo ) {
		if ( $eventInfo == NULL ) {
			$this->eid = NULL;
			if ( isset ($_SESSION['user']) ) {
				$this->organizer = $_SESSION['user'];
			}
			$this->title = $_POST['title'];
			$this->goal = $_POST['goal'];
			$this->reach_goal = $_POST['reach_goal'];
			$this->location = $_POST['location'];
			$this->address = $_POST['address'];
			$this->date = $_POST['date'];
			$this->time = $_POST['time'];
			$this->end_date = $_POST['end_date'];
			$this->end_time = $_POST['end_time'];
			$this->deadline = $_POST['deadline'];
			$this->description = $_POST['description'];
			$this->is_public = $_POST['is_public'];
			$this->type = $_POST['type'];
			if ( isset($_POST['location_lat']) ) {
				$this->location_lat = $_POST['location_lat'];
			}
			if ( isset($_POST['location_long']) ) {
				$this->location_long = $_POST['location_long'];
			}
			$this->twitter = $_POST['twitter'];
		} else {
			if ( ! is_array($eventInfo) ) {
				$this->eid = $eventInfo;
				$eventInfo = EFCommon::$dbCon->getEventInfo($this->eid);
			}
			
			// Make sure an event was pulled from the db
			if ( ! $eventInfo ) {
				$this->exists = false;
			} else {
				$this->makeEventFromArray($eventInfo);
				$this->addGuests();
			}
		}
	}
	
	public function __destruct() {
		
	}
	
	/**
	 * Generate the QR code for this event given the uid
	 * $uid    Integer    the user ID
	 * @return String the absolute URL of where the image for that user
	 */
	public function generateQR($uid) {
		// Generating the QR Code
		$qrKey = 'truersvp-' . $this->eid . '-' . $uid;
		$errorCorrectionLevel = 'L';
		$matrixPointSize = 4;
		$filename = realpath(dirname(__FILE__)).'/../temp/truersvp-'.md5($qrKey.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
		QRcode::png($qrKey, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
		
		return CURHOST.'/temp/'.basename($filename);
	}

	/* addGuests
	 * Adds invited guests to the array
	 */
	private function addGuests() {
		$guest_array = EFCommon::$dbCon->getAttendeesByEvent($this->eid);
		if ( sizeof($guest_array) > 0 ) {
			foreach ( $guest_array as $guest ) {
				$this->guests[] = new User($guest);
			}
		}
	}

	/* makeEventFromArray
	 * Takes event info from an array and
	 * stores it in the current object.
	 *
	 * Expects valid event info.
	 */
	private function makeEventFromArray($eventInfo) {
		$this->alias = $eventInfo['url_alias'];
				
		$this->eid = $eventInfo['id'];
		
		// Store into private vars
		if ( isset($eventInfo['organizer']) ) {
			$this->organizer = new User($eventInfo['organizer']);
		}
		$this->title = $eventInfo['title'];
		$this->goal = $eventInfo['goal'];
		$this->reach_goal = $eventInfo['reach_goal'];
		$this->location = $eventInfo['location_name'];
		$this->address = $eventInfo['location_address'];
		
		// Prepare date and time
		$this->datetime = $eventInfo['event_datetime'];
		$event_datetime = explode(" ", $eventInfo['event_datetime']);
		$this->date = EFCommon::$dbCon->dateToRegular($event_datetime[0]);
		$event_time = explode(":", $event_datetime[1]);
		$this->time = $event_time[0] . ":" . $event_time[1];

		$this->friendly_date = ( isset($eventInfo['friendly_event_date']) ) ? $eventInfo['friendly_event_date'] : NULL;
		$this->friendly_time = ( isset($eventInfo['friendly_event_time']) ) ? $eventInfo['friendly_event_time'] : NULL;

		// If end time...
		if ( strlen($eventInfo['event_end_datetime']) != 0 ) {
			$this->end_datetime = $eventInfo['event_end_datetime'];
			$event_end_datetime = explode(" ", $eventInfo['event_end_datetime']);
			$this->end_date = EFCommon::$dbCon->dateToRegular($event_end_datetime[0]);
			$event_end_time = explode(":", $event_end_datetime[1]);
			$this->end_time = $event_end_time[0] . ":" . $event_end_time[1];
		}

		$this->deadline = EFCommon::$dbCon->dateToRegular($eventInfo['event_deadline']);

		if ( isset($eventInfo['rsvp_days_left']) ) {
			$this->rsvp_days_left = $eventInfo['rsvp_days_left'];
		}
		
		$this->days_left = $eventInfo['days_left'];
		$this->description = $eventInfo['description'];
		$this->is_public = $eventInfo['is_public'];
		
		if ( isset($eventInfo['type'] ) ) {
			$this->type = $eventInfo['type'];
		}
		if ( isset($eventInfo['location_lat']) ) {
			$this->location_lat = $eventInfo['location_lat'];
		}
		if ( isset($eventInfo['location_long']) ) {
			$this->location_long = $eventInfo['location_long'];
		}
		
		$this->reach_goal = $eventInfo['reach_goal'];
		$this->twitter = $eventInfo['twitter'];
		
		$this->exists = true;
	}

	// GOING TO BE REMOVED WHEN SMART ASSIGNMENTS ARE
	// THE OBJECTS RATHER THAN THE ARRAYS
	/* get_array
	 * Consolidates all of the Event object
	 * information into a single array
	 *
	 * return $eventInfo | An array with all Event Info
	 */
	public function get_array() {
		$eventInfo['id'] = $this->eid;
		$eventInfo['organizer'] = $this->organizer;
		$eventInfo['title'] = $this->title;
		$eventInfo['description'] = $this->description;
		$eventInfo['location'] = $this->location;
		$eventInfo['address'] = $this->address;
		$eventInfo['date'] = $this->date;
		$eventInfo['time'] = $this->time;
		$eventInfo['end_date'] = $this->end_date;
		$eventInfo['end_time'] = $this->end_time;
		$eventInfo['reach_goal'] = $this->reach_goal;
		$eventInfo['goal'] = $this->goal;
		$eventInfo['deadline'] = $this->deadline;
		$eventInfo['type'] = $this->type;
		$eventInfo['is_public'] = $this->is_public;
		$eventInfo['location_lat'] = $this->location_lat;
		$eventInfo['location_long'] = $this->location_long;
		$eventInfo['twitter'] = $this->twitter;
		
		return $eventInfo;
	}
	
	/* can_view
	 * Checks to see if a user can view the event
	 *
	 * @param $userId | The Id of the user trying to view the event
	 * @return $can_view | True if they have permission, false if not
	 */
	public function can_view($userId) {
		if ( $this->is_public ) 
			return true;
	
		if ( $userId == NULL ) {
			if ( isset($_SESSION['user']) ) {
				$userId = $_SESSION['user']->id;
			} else {
				if ( isset($_SESSION['ref']) ) {
					header("Location: " . CURHOST . "/login?ref=" . $_SESSION['ref']);
					exit;
				}
				return false;
			}
		}
		
		if ( $this->organizer->id == $userId )
			return true;
			
		if ( $this->is_guest($userId) )
			return true;
			
		return false;
	}
	
	/* is_guest
	 * Determines whether or not a given user is invited to the event
	 *
	 * @param $userId | The user being checked
	 * @return $is_guest | True if the user is a guest, false if not
	 */ 
	private function is_guest($userId) {
		foreach ( $this->guests as $guest ) {
			if ( $guest->id == $userId ) 
				return true;
		}
		return false;
	}
	
	/* get_errors
	 * Makes akes sure that all event fields
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
		$this->check_location();
		$this->check_address();
		$this->check_date();
		$this->check_time();
		$this->check_end_date();
		$this->check_end_time();
		$this->check_goal();
		$this->check_deadline();
		$this->check_type();
		$this->check_twitter();
		
		// Return if there are any errors
		if ( $this->numErrors == 0 )
			return false;
		else
			return $this->error;
	}

	/* check_title
	 * Checks the event's title
	 *
	 * Requirements:
	 *  - Minimum length
	 */
	private function check_title() {
		$this->title = stripslashes($this->title);
		// Set the error meessage if there is one
		if( strtolower( $this->title ) == "i'm planning..." ) {
			$this->error['title'] = "Please enter an event title.";
			$this->numErrors++;
		} else if ( strlen($this->title) < 5 ) {
			$this->error['title'] = "Title must be at least 5 characters";
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
		$this->description = stripslashes($this->description);
		if( strlen($this->description) < 5 ) {
			$this->error['desc'] = "Title must be at least 5 characters";
			$this->numErrors++;
		}
	}
	
	/* check_location
	 * Checks the event's location
	 *
	 * Requirements:
	 *  - Only alphanumeric characters
	 *  - 0-100 characters
	 */
	private function check_location() {
		$this->location = stripslashes($this->location);
		if( strlen($this->location) == 0 )
			return;	

		if ( $this->location == "Ex: Jim's House" ) {
			$this->location = "";
			return;
		}
	}
	
	/* check_twitter
	 * Checks the twitter hash tag
	 */
	private function check_twitter() {
		$this->twitter = stripslashes($this->twitter);	
		if( strlen($this->twitter) == 0 )
			return;

		if ( $this->twitter == "Ex: #TurtlesRock" ) {
			$this->twitter = "";
			return;
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
			$location_type = "";
			if ( $geocode->status != "ZERO_RESULTS" ) {
				$this->location_lat = $geocode->results[0]->geometry->location->lat;
				$this->location_long = $geocode->results[0]->geometry->location->lng; 
				$formatted_address = $geocode->results[0]->formatted_address;
				$location_type = $geocode->results[0]->geometry->location_type;
			}
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
		if ( strlen($this->date) == 0) {
			$this->date = NULL;
			$this->error['date'] = "Please enter a date for your event";
			$this->numErrors++;
			return;
		}
		
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
	
	/* check_end_date
	 * Checks the event's end date
	 *
	 * Requirements:
	 *  - Valid date
	 *  - Date in the future
	 *  - After event date
	 */
	private function check_end_date() {
		if ( ! isset( $this->end_date ) || strlen($this->end_date) == 0 ) {
			$this->end_date = NULL;
			return;
		}
			
		$event_date = explode('/', $this->date);
		$month = $event_date[0];
		$day = $event_date[1];
		$year = $event_date[2]; 
		$event_end_date = explode('/', $this->end_date);
		$end_month = $event_end_date[0];
		$end_day = $event_end_date[1];
		$end_year = $event_end_date[2]; 

		// Make sure date is valid
		if( !@checkdate($month, $day, $year) ) {
			$this->error['end_date'] = "Please enter a valid date in mm/dd/yyyy format";
			$this->numErrors++;
			return;
		}

		// Make sure date is in the future
		$check = @mktime(0, 0, 0, $end_month, $end_day, $end_year, -1);
		$event = @mktime(0, 0, 0, $month, $day, $year, -1);
		if( $check < $event ) {
			$this->error['end_date'] = "Event end date must be on or after start date";
			$this->numErrors++;
		}
	}
	
	/* check_end_time
	 * Checks the event's time
	 *
	 * Requirements:
	 *  - 12 hour time format
	 */
	private function check_end_time() {	
		if ( ! isset($end_time) || strlen($this->end_time) == 0 )
			return;
		
			$valid_time = filter_var(
			$this->end_time, 
			FILTER_VALIDATE_REGEXP, 
			array(
				"options" => array(
					"regexp" => "/^((0?[1-9]|1[012])(:[0-5]\d){0,2}(\ [AP]M))$|^([01]\d|2[0-3])(:[0-5]\d){0,2}$/"
				)
			)
		);
		
		if ( ! $valid_time ) {
			$this->error['end_time'] = "Please select a time.";
			$this->numErrors++;
		}
		
		if ( $this->date == $this->end_date && $this->time >= $this->end_time ) {
			$this->error['end_time'] = "End time must be after event time.";
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
	
	/**
	 * Abstracting the adding guest to the event
	 * $guest   AbstractUser   the user
	 */
	public function add_guest($guest) {
		array_push($this->guests, $guest);
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
		if ( strlen($this->deadline) == 0) {
			$this->error['deadline'] = "Please enter a deadline for RSVP.";
			$this->numErrors++;
			return;
		}
	
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
	
	public function submitGuests() {
		$csvFile = CSV_UPLOAD_PATH . '/' . $this->eid . '.csv';
		
		$numGuests = 0;
		
		// text area check
		if (trim($_POST['emails']) != "") {
			$numGuests = $this->setGuests($_POST['emails']);		
		// CSV file check
		} else if (file_exists($csvFile)) {
			$this->setGuestsFromCSV($csvFile);
		}
		
		// Send the email invites
		EFCommon::$mailer->sendHtmlInvite($this);
		
		if ( $numGuests == 0 ) {
			return "No guests added.";
		} else {
			$plural_guest = ($numGuests == 1) ? "guest" : "guests";
			return $numGuests . " " . $plural_guest . " added successfully";
		}
	}
	
	public function setGuests($guest_email) {
		$guests = explode(",", $guest_email);
		
		// Reset the errors
		unset($this->error);
		$this->error["add_guest"] = "";
		$this->numErrors = 0;
		
		if (sizeof($guests) > 1) {
			foreach($guests as $guest) {
				$guest = trim($guest);
				if ( filter_var($guest, FILTER_VALIDATE_EMAIL) ) {
					array_push($this->guests, new AbstractUser($guest));
					$addGuest[] = $guest;
				} else {
					$this->error["add_guest"] .= "<br />" . $guest;
					$this->numErrors++;
				}
			}
		} else {
			array_push($this->guests, new AbstractUser($guest_email));
			$addGuest[] = $guest_email;
		}
		
		if ( isset($addGuest) ) {
			EFCommon::$dbCon->storeGuests($addGuest, $this->eid, $_SESSION['user']->id);
			return sizeof($addGuest);
		} else {
			return 0;
		}
	}
	
	public function setGuestsFromCSV($csvFile) {
		$csv_contacts = array();
		if (($handle = fopen($csvFile, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
				for ($i = 0; $i < sizeof($data); ++$i) {
					if (filter_var($data[$i], FILTER_VALIDATE_EMAIL)) {
						array_push($csv_contacts, $data[$i]);
						array_push($this->guests, new AbstractUser($data[$i]));
					}
				}
			}
			fclose($handle);
		}
		EFCommon::$dbCon->storeGuests($csv_contacts, $this->eid, $_SESSION['user']->id);
	}
	
	public function getCalDate($date, $time) {
		$dateTokens = explode("/", $date);
		$timeTokens = explode(":", $time);
	
		$date = new DateTime();
		$date->setTimezone(new DateTimeZone('PDT'));
		$date->setDate($dateTokens[2], $dateTokens[0], $dateTokens[1]);
		$date->setTime($timeTokens[0],$timeTokens[1], 00);
		$date->setTimezone(new DateTimeZone('UTC'));
	
		return $date->format('Ymd\THis\Z');
	}
	
	/* Generate the Outlook file */
	public function getVCS() {
		header("Content-Type: text/x-vCalendar");
		header("Content-Disposition: inline; filename = TrueRSVP-".$this->eid.".vcs");
		
		/*The code for generating a .vcs file for outlook users*/	
		$vCalOutput = "BEGIN:	\n";
		$vCalDescription = str_replace("\r", "\\n", $this->description);
		$vCalLocation = str_replace("\r", "\\n", $this->address);
		$text = str_replace("\r", "\\n", $this->description);
		
		/* output the event */
		$vCalOutput .= "BEGIN:VEVENT\n";
		$vCalOutput .= "SUMMARY:".rawurldecode($text)."\n";
		$vCalOutput .= "DESCRIPTION:".rawurldecode($vCalDescription)."\n";
		$vCalOutput .= "DTSTART:".$this->getCalDate($this->date, $this->time)."\n";
		$vCalOutput .= "LOCATION:".rawurldecode($vCalLocation)."\n";
		$vCalOutput .= "URL;VALUE=URI:".rawurldecode($link)."\n";
		
		// Check whether there is an end_date and end_time
		if (isset($this->end_date) && isset($this->end_time)) {
			$vCalOutput .= "DTEND:".$this->getCalDate($this->end_date, $this->end_time)."\n";
		} else {
			$vCalOutput .= "DTEND:".$this->getCalDate($this->date, $this->time)."\n";
		}
		$vCalOutput .= "END:VEVENT\n";
		$vCalOutput .= "END:VCALENDAR\n";
		
		print($vCalOutput);
	}
	
	/* Generate the iCal file */
	public function getICS() {
		header('Content-Type: text/calendar; charset=utf-8');
		header('Content-Disposition: attachment; filename="TrueRSVP-'.$this->eid.'.ics"; ');
		
		$vCalOutput = "BEGIN:VCALENDAR\n";
		$vCalOutput .= "CALSCALE:GREGORIAN\n";
		$vCalOutput .= "X-WR-TIMEZONE;VALUE=TEXT:US/Pacific\n";
		$vCalOutput .= "METHOD:PUBLISH\n";
		$vCalOutput .= "PRODID:-//Apple Computer\, Inc//iCal 1.0//EN\n";
		$vCalOutput .= "X-WR-CALNAME;VALUE=TEXT:TrueRSVP\n";
		$vCalOutput .= "VERSION:2.0\n";
		$vCalOutput .= "BEGIN:VEVENT\n";
		$vCalOutput .= "DESCRIPTION:".rawurldecode($this->description)."\n";
		$vCalOutput .= "SEQUENCE:5\n";
		$vCalOutput .= "DTSTART;TZID=US/Pacific:".$this->getCalDate($this->date, $this->time)."\n";
		$vCalOutput .= "DTSTAMP:".$this->getCalDate($this->date, $this->time)."\n";
		$vCalOutput .= "SUMMARY:".rawurldecode($this->description)."\n";
		$vCalOutput .= "UID:EC9439B1-FF65-11D6-9973-003065F99D04\n";
		
		// Check whether there is an end_date and end_time
		if (isset($this->end_date) && isset($this->end_time)) {
			$vCalOutput .= "DTEND;TZID=US/Pacific:".$this->getCalDate($this->end_date, $this->end_time)."\n";
		} else {
			$vCalOutput .= "DTEND;TZID=US/Pacific:".$this->getCalDate($this->date, $this->time)."\n";
		}
		
		$vCalOutput .= "BEGIN:VALARM\n";
		$vCalOutput .= "TRIGGER;VALUE=DURATION:-P1D\n";
		$vCalOutput .= "ACTION:DISPLAY\n";
		$vCalOutput .= "DESCRIPTION:Event reminder\n";
		$vCalOutput .= "END:VALARM\n";
		$vCalOutput .= "END:VEVENT\n";
		$vCalOutput .= "END:VCALENDAR\n";
		
		print($vCalOutput);	
	}

	public function getHumanReadableEventTime(){
		$date = date_create($this->datetime);
		return date_format($date, 'F j, g:i A');
	}
}
