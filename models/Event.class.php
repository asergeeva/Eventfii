<?php
/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
 
require_once(realpath(dirname(__FILE__)).'/User.class.php');
require_once(realpath(dirname(__FILE__)).'/../libs/QR/qrlib.php');
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
	public $created;
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
	public $time_left;
	
	public $description;
	public $is_public;
	public $type;
	public $location_lat;
	public $location_long;
	public $global_ref;
	
	public $guests = array();
	public $guest_emails = array();
	
	public $exists;
	
	public $twitter;
	
	public $error;
	public $numErrors;
	
	function __construct( $eventInfo, $step1 = false ) {
		// Creating new event
		if ( $eventInfo == NULL ) {
			$this->numErrors = 0;
			$this->eid = NULL;
			$this->organizer = ( isset($_SESSION['user']) ) ? $_SESSION['user'] : NULL;
			$this->set_title(NULL);
			$this->set_location(NULL);
			$this->set_address(NULL);
			$this->set_date(NULL);
			$this->set_time(NULL);
			$this->set_end_date(NULL);
			$this->set_end_time(NULL);
			$this->set_goal(NULL);
			$this->global_ref = NULL;
			
			// For event creation some fields aren't checked
			// until step 2. Keep those out of the validation loop.
			if ( ! $step1 ) {
				$this->set_description(NULL);
				$this->set_type(NULL);
				$this->set_deadline(NULL);
				$this->reach_goal = $_POST['reach_goal'];
				$this->is_public = $_POST['is_public'];
				$this->set_twitter(NULL);
			}

		// Event from database
		} else {
			// If event ID was passed, pull the event info from the database
			if ( ! is_array($eventInfo) ) {
				$this->eid = $eventInfo;
				$eventInfo = EFCommon::$dbCon->getEventInfo($this->eid);
			}
			
			// Make sure an event was pulled from the db
			if ( ! $eventInfo ) {
				$this->exists = false;
			// Fill the event object
			} else {
				$this->makeEventFromArray($eventInfo);
				$this->addGuests();
			}
		}
	}
	
	public function __destruct() {
		
	}
	
	/* set_title
	 * Sets the event title
	 *
	 * Requirements:
	 *  - Minimum length 5 chars
	 */
	private function set_title( $title = NULL ) {
		if ( $title == NULL ) {
			if ( isset ($_POST['title']) ) {
				$title = $_POST['title'];
			}
		}
		
		$this->title = stripslashes($title);
		
		if( strtolower( $this->title ) == "i'm planning..." || $this->title == "name of event" ) {
			$this->error['title'] = "Please enter an event title";
			$this->numErrors++;
		} else if ( strlen($this->title) < 5 ) {
			$this->error['title'] = "Title must be at least 5 characters";
			$this->numErrors++;
		}
	}

	/* set_description
	 * Sets the event's description
	 *
	 * Requirements:
	 *  - Only alphanumeric characters
	 *  - 5-500 characters
	 */
	private function set_description( $description = NULL ) {
		if ( $description == NULL ) {
			if ( isset($_POST['description']) ) {
				$description = $_POST['description'];
			}
		}
	
		$this->description = stripslashes($description);
		
		if ( $this->description == "What should your guests know?" ) {
			$this->description = NULL;
			$this->error['desc'] = "Please enter an event description";
			$this->numErrors++;
		}
		
		if( strlen($this->description) < 5 ) {
			$this->error['desc'] = "Event description must be at least 5 characters";
			$this->numErrors++;
		}
	}
	
	/* set_type
	 * Sets the event's type
	 *
	 * Requirements:
	 * - Can't be 0
	 */
	private function set_type( $type = NULL ) {
		if ( $type == NULL ) {
			if ( isset($_POST['type']) ) {
				$type = $_POST['type'];
			}
		}
		
		$this->type = $type;
		
		if ( $type == 0 ) {
			$this->error['type'] = "Please select an event type";
			$this->numErrors++;
		}
	}
	
	/* set_location
	 * Sets the event's location
	 *
	 * Requirements:
	 *  - Only alphanumeric characters
	 *  - 0-100 characters
	 */
	private function set_location( $location = NULL ) {
		if ( $location == NULL ) {
			if ( isset($_POST['location']) ) {
				$this->location = stripslashes($_POST['location']);
			}
		}
				
		// Optional
		if( $this->location == "Ex: Jim's House" ) {
			$this->location = NULL;
			return;
		}

		if ( strlen($this->location) > 100 ) {
			$this->error['desc'] = "Event location must be less than 100 characters";
			$this->numErrors++;
		}
	}
	
	
	
	/* set_address
	 * Sets the event's address
	 *
	 * Also sets the location's latitude and longitude
	 * if valid, or removes them if not valid.
	 *
	 * Requirements:
	 *  - Can't contain illegal characters
	 *  - Valid address
	 */
	private function set_address($address = NULL) {
		if ( $address == NULL ) {
			if ( isset($_POST['address']) ) {
				$address = $_POST['address'];
			}
		}
		
		$this->address = stripslashes($address);
		
		
		if ( $this->address == "Ex: 1234 Maple St, Los Angeles, CA 90007" ) {
			$this->address = NULL;
			$this->error['address'] = "Please enter an address";
			$this->numErrors++;
			return;
		}
		
		// Check the address using a geocoder
		$geocode = EFCommon::$google->getGeocode($this->address);
		
		if( ! is_numeric($geocode['lat']) || ! is_numeric($geocode['lon'])) {
			$this->error['address'] = "Address is invalid";
			$this->numErrors++;
			return;
		}
		
		$this->location_lat = $geocode['lat'];
		$this->location_long = $geocode['lon'];
	}

	/* set_date
	 * Sets the event's date
	 *
	 * Requirements:
	 *  - Valid date
	 *  - Date in the future
	 */
	private function set_date( $date = NULL ) {
		if ( $date == NULL ) {
			if ( isset($_POST['date']) ) {
				$date = $_POST['date'];
			}
		}
		
		$this->date = $date;
	
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
		$check = @mktime(0, 0, 0, $month, $day, $year, -1);
		$today = @mktime(0, 0, 0, date("m"), date("d"), date("y"), -1);
		if( $check < $today ) {
			$this->error['date'] = "Event date should be a date in the future";
			$this->numErrors++;
		}
	}
	
	/* set_time
	 * Checks the event's time
	 *
	 * Requirements:
	 *  - 12 hour time format
	 */
	private function set_time( $time = NULL ) {
		if ( $time == NULL ) {
			if ( isset($_POST['time']) ) {
				$time = $_POST['time'];
			}
		}
		
		$this->time = $time;
		
		if( $this->time == 0 && $this->time != "00:00" && $this->time != "00:30" ) {
			$this->error['time'] = "Please select a time for your event";
			$this->numErrors++;
		}
	}
	
	/* set_end_date
	 * Sets the event's end date
	 *
	 * Requirements:
	 *  - Valid date
	 *  - Date in the future
	 *  - After event date
	 */
	private function set_end_date( $end_date = NULL ) {
		if ( $end_date == NULL ) {
			if ( isset($_POST['end_date']) ) {
				$end_date = $_POST['end_date'];
			}
		}
		
		$this->end_date = $end_date;
	
		// Optional
		if ( strlen($this->end_date) == 0 ) {
			$this->end_date = NULL;
			return;
		}
		
		$event_end_date = explode('/', $this->end_date);
		$end_month = $event_end_date[0];
		$end_day = $event_end_date[1];
		$end_year = $event_end_date[2]; 

		// Make sure date is valid
		if( !@checkdate($end_month, $end_day, $end_year) ) {
			$this->error['end_date'] = "Please enter a valid date in mm/dd/yyyy format";
			$this->numErrors++;
			return;
		}
		
		if ( isset ( $this->date ) && ! isset ( $this->error['date'] ) ) {
			$event_date = explode('/', $this->date);
			$month = $event_date[0];
			$day = $event_date[1];
			$year = $event_date[2];
			
			// Make sure end date is on or after start date
			$check = @mktime(0, 0, 0, $end_month, $end_day, $end_year, -1);
			$event = @mktime(0, 0, 0, $month, $day, $year, -1);
			if( $check < $event ) {
				$this->error['end_date'] = "Event end date must be on or after start date";
				$this->numErrors++;
			}
		}
	}
	
	/* set_end_time
	 * Sets the event's time
	 *
	 * Requirements:
	 *  - 12 hour time format
	 */
	private function set_end_time( $end_time = NULL ) {
		if ( $end_time == NULL ) {
			if ( isset($_POST['end_time']) ) {
				$end_time = $_POST['end_time'];
			}
		}
		
		$this->end_time = $_POST['end_time'];
		
		// Optional
		if ( $this->end_time == 0 && $this->time != "00:00" && $this->time != "00:30" ) {
			$this->end_time = NULL;
			if ( strlen($this->end_date) == 0 ) {
				return;
			} else {
				$this->error['end_time'] = "Please select an end time";
				$this->numErrors++;
				return;
			}
		}
		
		if ( $this->date == $this->end_date && $this->time >= $this->end_time ) {
			$this->error['end_time'] = "End time must be after event time";
			$this->numErrors++;
		}
	}
	
	/* set_deadline
	 * Sets the event's deadline
	 *
	 * Requirements:
	 *  - Valid date
	 *  - After event creation date
	 *  - Before/on event date
	 */
	private function set_deadline( $deadline = NULL ) {
		if ( $deadline == NULL ) {
			if ( isset($_POST['deadline']) ) {
				$deadline = $_POST['deadline'];
			}
		}
		
		$this->deadline = $deadline;
		
		if ( $this->deadline == "" ) {
			$this->deadline = $this->date;
			return;
		}
		
		$event_deadline = explode('/', $this->deadline);
		$month = $event_deadline[0];
		$day = $event_deadline[1];
		$year = $event_deadline[2]; 

		// Make sure date is valid
		if( !@checkdate($month, $day, $year) ) {
			$this->error['deadline'] = "Please enter a valid date in mm/dd/yyyy format";
			$this->numErrors++;
			return;
		}
		
		if ( isset ( $this->date ) && ! isset ( $this->error['date'] )) {
			$event_date = explode('/', $this->date);
			$event_month = $event_date[0];
			$event_day = $event_date[1];
			$event_year = $event_date[2];
			
			// Make sure end date is on or after start date
			$check = @mktime(0, 0, 0, $month, $day, $year, -1);
			$event = @mktime(0, 0, 0, $event_month, $event_day, $event_year, -1);
			if( $check > $event ) {
				$this->error['deadline'] = "Deadline must be before event date";
				$this->numErrors++;
			}
		}
	}
	
	/* set_goal
	 * Sets the event's attendance goal
	 *
	 * Requirements:
	 *  - Number between 0-1000000
	 */
	private function set_goal( $goal = NULL ) {
		if ( $goal == NULL ) {
			if ( isset($_POST['goal']) ) {
				$goal = $_POST['goal'];
			}
		}
		
		$this->goal = $goal;
		
		$int_options = array(
			"options" => array(
				"min_range" => 1,
				"max_range" => 1000000
			)
		);

		if( ! filter_var($this->goal, FILTER_VALIDATE_INT, $int_options) ) {
			$this->error['goal'] = "Please enter a attendance goal";
			$this->numErrors++;
		}
			
	}
	
	/* set_twitter
	 * Sets the twitter hash tag
	 */
	private function set_twitter( $twitter = NULL ) {
		if ( $twitter == NULL ) {
			if ( isset($_POST['twitter']) ) {
				$twitter = $_POST['twitter'];
			}
		}
		
		$this->twitter = stripslashes($twitter);
		
		if( strlen($this->twitter) == 0 || $this->twitter == "Ex: #TurtlesRock" ) {
			$this->twitter = NULL;
			return;
		}
	}
	
	private function shortFriendlyTime($friendlyTime) {
		$friendly_event_time = explode(" ", $friendlyTime);
		
		$friendly_event_mid = $friendly_event_time[1];
		$friendly_event_time = explode(":", $friendly_event_time[0]);
		
		$this->friendly_time = $friendly_event_time[0].":".$friendly_event_time[1]." ".$friendly_event_mid;
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
		$this->created = $eventInfo['created'];
		$this->exists = true;
		
		$this->organizer = new AbstractUser($eventInfo['organizer']);
		$this->title = stripslashes($eventInfo['title']);
		$this->description = stripslashes($eventInfo['description']);
		$this->location = stripslashes($eventInfo['location_name']);
		$this->address = stripslashes($eventInfo['location_address']);
		if ( isset($eventInfo['location_lat']) ) {
			$this->location_lat = $eventInfo['location_lat'];
		}
		if ( isset($eventInfo['location_long']) ) {
			$this->location_long = $eventInfo['location_long'];
		}
		
		// Prepare date and time
		$this->datetime = $eventInfo['event_datetime'];
		$event_datetime = explode(" ", $eventInfo['event_datetime']);
		$this->date = EFCommon::$dbCon->dateToRegular($event_datetime[0]);
		$event_time = explode(":", $event_datetime[1]);
		$this->time = $event_time[0] . ":" . $event_time[1];

		$this->friendly_date = ( isset($eventInfo['friendly_event_date']) ) ? $eventInfo['friendly_event_date'] : EFCommon::$dbCon->getFriendlyDate($this->datetime);
		
		if ( isset($eventInfo['friendly_event_time']) ) {
			$this->shortFriendlyTime($eventInfo['friendly_event_time']);
		} else {
			$this->shortFriendlyTime(EFCommon::$dbCon->getFriendlyTime($this->datetime));
		}

		// If end time...
		if ( strlen($eventInfo['event_end_datetime']) != 0 ) {
			$this->end_datetime = $eventInfo['event_end_datetime'];
			$event_end_datetime = explode(" ", $eventInfo['event_end_datetime']);
			$this->end_date = EFCommon::$dbCon->dateToRegular($event_end_datetime[0]);
			$event_end_time = explode(":", $event_end_datetime[1]);
			$this->end_time = $event_end_time[0] . ":" . $event_end_time[1];
		}

		$this->deadline = EFCommon::$dbCon->dateToRegular($eventInfo['event_deadline']);

		$this->days_left = (isset($eventInfo['days_left'])) ? $eventInfo['days_left'] : NULL;
		$this->time_left = (isset($eventInfo['time_left'])) ? $eventInfo['time_left'] : NULL;

		if ( isset($eventInfo['rsvp_days_left']) ) {
			$this->rsvp_days_left = $eventInfo['rsvp_days_left'];
		} else {
			$this->rsvp_days_left = $this->days_left;
		}
		
		$this->type = $eventInfo['type'];
		$this->goal = $eventInfo['goal'];
		$this->reach_goal = $eventInfo['reach_goal'];
		$this->is_public = $eventInfo['is_public'];
		$this->twitter = $eventInfo['twitter'];
		$this->global_ref = $eventInfo['global_ref'];
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
		$eventInfo['deadline'] = $this->deadline;
		$eventInfo['goal'] = $this->goal;
		$eventInfo['reach_goal'] = $this->reach_goal;
		$eventInfo['type'] = $this->type;
		$eventInfo['is_public'] = $this->is_public;
		$eventInfo['location_lat'] = $this->location_lat;
		$eventInfo['location_long'] = $this->location_long;
		$eventInfo['twitter'] = $this->twitter;
		$eventInfo['alias'] = $this->alias;
		$eventInfo['global_ref'] = $this->global_ref;
		
		return $eventInfo;
	}

	/* addGuests
	 * Adds invited guests to the array
	 */
	private function addGuests() {
		$guest_array = EFCommon::$dbCon->getAttendeesByEvent($this->eid);
		if ( sizeof($guest_array) > 0 ) {
			foreach ( $guest_array as $guest ) {
				$this->addGuestEmail($guest['email']);
			}
		}
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
	
		if ( isset($_GET['gref'])) {
			$grefEvent = EFCommon::$dbCon->isValidGlobalRef($_GET['gref']);
			if (is_array($grefEvent)) {
				$_SESSION['gref'] = new Event($grefEvent);
				return true;
			}
			return false;
		}
			
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
	 * Makes sure that all event fields
	 * are filled and valid.
	 *
	 * return $errors | If there are errors
	 * return false | If there are no errors
	 */
	public function get_errors() {		
		// Return if there are any errors
		if ( $this->numErrors == 0 )
			return false;
		else
			return $this->error;
	}
	
	/**
	 * Abstracting the adding guest to the event
	 * $guest   AbstractUser   the user
	 */
	public function add_guest($guest) {
		array_push($this->guests, $guest);
	}
	
	public function submitGuests() {
		$csvFile = CSV_UPLOAD_PATH . '/' . $this->eid . '.csv';
		
		$newGuests = array();
		
		// text area check
		if (trim($_POST['emails']) != "") {
			$newGuests = $this->setGuests($_POST['emails']);		
		// CSV file check
		} else if (file_exists($csvFile)) {
			$newGuests = $this->setGuestsFromCSV($csvFile);
		} 
		
		// Send the email invites
		EFCommon::$mailer->sendHtmlInvite($this, $newGuests);
		
		return $newGuests;
	}
	
	/**
	 * Given the guest's email adddress, add guest to the Event guests collection
	 * @param    $guest_email    String    the email of the guest
	 */
	private function addGuestEmail($guest_email) {
		if ( filter_var($guest_email, FILTER_VALIDATE_EMAIL) &&
				!in_array($guest_email, $this->guest_emails)) {
			array_push($this->guest_emails, $guest_email);
			array_push($this->guests, new AbstractUser($guest_email));
			return true;
		}
		return false;
	}
	
	public function setGuests($guest_email) {
		$addGuest = array();
		
		// Inviting the guest organizer for new event
		if ( $guest_email == NULL ) {	
			$addGuest[0] = $_SESSION['user']->email;
			if ( $this->addGuestEmail($guest_email) ) {
				$addGuest[] = $guest_email;
			}
		} else {
		// Inviting any other user(s)
			$guests = explode(",", $guest_email);
			
			unset($this->error);
			$this->numErrors = 0;
			
			if (sizeof($guests) >= 1) {
				foreach($guests as $guest) {
					$guest = trim($guest);
					if ($this->addGuestEmail($guest)) {
						$addGuest[] = $guest;
					}
				}
			} 
		}
			
		if ( isset($addGuest) ) {
			EFCommon::$dbCon->storeGuests($addGuest, $this->eid);
		} else {
			return 0;
		}
		return $addGuest;
	}
	
	/**
	 * Generate the QR code for this event given the uid
	 * $uid    Integer    the user ID
	 * @return String the absolute URL of where the image for that user
	 */
	public function generateQR($uid) {
		// Generating the QR Code
		$qrKey = 'truersvp-' . $this->eid . '-' . $uid;
		$errorCorrectionLevel = 'H';
		$matrixPointSize = 10;
		$filename = realpath(dirname(__FILE__)).'/../temp/truersvp-'.md5($qrKey.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
		QRcode::png($qrKey, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
		
		return CURHOST.'/temp/'.basename($filename);
	}
	
	public function setGuestsFromCSV($csvFile) {
		$csv_contacts = array();
		if (($handle = fopen($csvFile, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
				for ($i = 0; $i < sizeof($data); ++$i) {
					if ($this->addGuestEmail($data[$i])) {
						array_push($csv_contacts, $data[$i]);
					}
				}
			}
			fclose($handle);
		}
		
		if (sizeof($csv_contacts) > 0) {
			EFCommon::$dbCon->storeGuests($csv_contacts, $this->eid, $_SESSION['user']->id);
			return $csv_contacts;
		}
		return 0;
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
