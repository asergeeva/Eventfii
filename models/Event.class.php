<?php
/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
 
require_once(realpath(dirname(__FILE__)).'/../models/User.class.php');
 
class Event {
	public $eid;
	public $organizer;
	public $title;
	public $goal;
	public $address;
	public $event_datetime;
	public $date;
	public $time;
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
	
	private $error;
	private $numErrors;
	
	function __construct( $eventInfo ) {
		if ( $eventInfo == NULL ) {
			$this->eid = NULL;
			$this->organizer = $_SESSION['user'];
			$this->title = $_POST['title'];
			$this->goal = $_POST['goal'];
			$this->address = $_POST['address'];
			$this->date = $_POST['date'];
			$this->time = $_POST['time'];
			$this->deadline = $_POST['deadline'];
			$this->description = $_POST['description'];
			$this->is_public = $_POST['is_public'];
			$this->type = $_POST['type'];
			$this->location_lat = $_POST['location_lat'];
			$this->location_long = $_POST['location_long'];
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
		$this->eid = $eventInfo['id'];
		
		// Store into private vars
		if ( isset($eventInfo['organizer']) ) {
			$this->organizer = new User($eventInfo['organizer']);
		}
		$this->title = $eventInfo['title'];
		$this->goal = $eventInfo['goal'];
		$this->address = $eventInfo['location_address'];
		$this->event_datetime = $eventInfo['event_datetime'];

		// Prepare date and time
		$eventDateTime = explode(" ", $eventInfo['event_datetime']);
		
		$this->date = EFCommon::$dbCon->dateToRegular($eventDateTime[0]);
		$this->deadline = EFCommon::$dbCon->dateToRegular($eventInfo['event_deadline']);
		
		$eventTime = explode(":", $eventDateTime[1]);
		$this->time = $eventTime[0] . ":" . $eventTime[1];

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
		$eventInfo['address'] = $this->address;
		$eventInfo['date'] = $this->date;
		$eventInfo['time'] = $this->time;
		$eventInfo['goal'] = $this->goal;
		$eventInfo['deadline'] = $this->deadline;
		$eventInfo['type'] = $this->type;
		$eventInfo['is_public'] = $this->is_public;
		$eventInfo['location_lat'] = $this->location_lat;
		$eventInfo['location_long'] = $this->location_long;
		
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
					"regexp" => "/^[A-Za-z0-9'\s]{5,100}$/"
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
	 				"regexp" => "/^[A-Za-z0-9'!\.\s]{10,500}$/"
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
		
		$this->time = date("H:i:s", strtotime($this->time));
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
	
	public function submitGuests() {
		$mailer = new EFMail();
		$csvFile = CSV_UPLOAD_PATH.'/'.$this->eid.'.csv';
		
		// text area check
		if (trim($_REQUEST['emails']) != "") {
			$this->setGuests($_REQUEST['emails']);
		// CSV file check
		} else if (file_exists($csvFile)) {
			$this->setGuestsFromCSV($csvFile);
		}
		
		$mailer->sendHtmlInvite($this);
	}
	
	public function setGuests($guest_email) {
		if (filter_var($guest_email, FILTER_VALIDATE_EMAIL)) {
			array_push($this->guests, $guest_email);
		} else {
			$this->guests = array_map('trim', explode(",", $guest_email));
		}
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
	
	public function getCalDate() {
		$dateTokens = explode("/", $this->date);
		$timeTokens = explode(":", $this->time);
	
		$date = new DateTime();
		$date->setTimezone(new DateTimeZone('PDT'));
		$date->setDate($dateTokens[2], $dateTokens[0], $dateTokens[1]);
		$date->setTime($timeTokens[0],$timeTokens[1], 00);
		$date->setTimezone(new DateTimeZone('UTC'));
	
		return $date->format('Ymd\THis\Z');
	}
	
	/* Generate the Google Calendar button */
	public function getGCAL() {
		$gCalButton = '<a href="http://www.google.com/calendar/event?action=TEMPLATE&text=' . $this->title . '&dates=' . $this->getCalDate() . '/' . $this->getCalDate() . '&details=' . $this->description . '&location=' . $this->address . '&trp=false' . '&sprop=' . EVENT_URL.$this->eid . '&sprop=' . $this->description . '" target="_blank">';
		$gCalButton .= '<img src="http://www.google.com/calendar/images/ext/gc_button6.gif" border="0" />';
		$gCalButton .= '</a>';
		return $gCalButton;
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
		$vCalOutput .= "DTSTART:".$this->getCalDate()."\n";
		$vCalOutput .= "LOCATION:".rawurldecode($vCalLocation)."\n";
		$vCalOutput .= "URL;VALUE=URI:".rawurldecode($link)."\n";
		$vCalOutput .= "DTEND:".$this->getCalDate()."\n";
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
		$vCalOutput .= "DTSTART;TZID=US/Pacific:".$this->getCalDate()."\n";
		$vCalOutput .= "DTSTAMP:".$this->getCalDate()."\n";
		$vCalOutput .= "SUMMARY:".rawurldecode($this->description)."\n";
		$vCalOutput .= "UID:EC9439B1-FF65-11D6-9973-003065F99D04\n";
		$vCalOutput .= "DTEND;TZID=US/Pacific:".$this->getCalDate()."\n";
		$vCalOutput .= "BEGIN:VALARM\n";
		$vCalOutput .= "TRIGGER;VALUE=DURATION:-P1D\n";
		$vCalOutput .= "ACTION:DISPLAY\n";
		$vCalOutput .= "DESCRIPTION:Event reminder\n";
		$vCalOutput .= "END:VALARM\n";
		$vCalOutput .= "END:VEVENT\n";
		$vCalOutput .= "END:VCALENDAR\n";
		
		print($vCalOutput);	
	}
}
