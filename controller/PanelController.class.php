<?php
/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
 
require_once('db/DBConfig.class.php');

class PanelController {
	private $smarty;
	private $dbCon;
	private $DEBUG = true;

	public function __construct($smarty) {
		$this->smarty = $smarty;
		$this->dbCon = new DBConfig();
	}

	public function __destruct() {

	}

	////////////////////
	// Validation
	// Obsolete but keeping until dependency is removed

	public function check_address($addr) {
		$a = urlencode($addr);
		$retVal = array();
		$geocodeURL = "http://maps.googleapis.com/maps/api/geocode/json?address=$a&sensor=false";
		$ch = curl_init($geocodeURL);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		$result = curl_exec( $ch );
		$httpCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
		curl_close( $ch );
		
		if ($httpCode == 200) {
			$geocode = json_decode($result);
			$lat = $geocode->results[0]->geometry->location->lat;
			$lng = $geocode->results[0]->geometry->location->lng; 
			$formatted_address = $geocode->results[0]->formatted_address;
			$geo_status = $geocode->status;
			$location_type = $geocode->results[0]->geometry->location_type;
			$retVal['location_type']=$location_type;
			$retVal['lat'] = $lat;
			$retVal['lng'] = $lng;	
		} else {
			$retVal['location_type']="error";
			// $retVal['lat'] = $lat;
			// $retVal['lng'] = $lng;
		}
		return $retVal;
	}

	public function validate_event_type( $val ) {
		$flag = 1;
		if ( $val <= 0 ) {
			$error['type'] = "Please select an event type";
			$flag = 0;
		}
		$this->smarty->append('error', $error, true);
		return $flag;
	}

	public function validate_address($addr) {
		//	die("5");
		$flag = 1;
		if ( $addr == "" ) {
			$error['address'] = "Please enter an address";
			$flag = 2;
			//	return $flag;
		}
		$retArr = $this->check_address($addr);
		if( ! ( $retArr['location_type']=="RANGE_INTERPOLATED" || $retArr['location_type']=="ROOFTOP" ) ) {
			$error['address'] = "Address entered is invalid";
			$flag = 2;
			//die($addr."=".$retArr['location_type']);
			//return $flag;
		}	
		$res = filter_var($addr, FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^[A-Za-z0-9\s-,*]*$/")));
		if(!($res)) {
			$error['address'] = "Address can only contain spaces, A-Z, 0-9 or -*,@&";
			$flag=2;
		}
		$this->smarty->append('error', $error, true);
		return $flag;
	}

	public function validate_title($title) {
		$flag = 1;
		$res = filter_var(
			$title, 
			FILTER_VALIDATE_REGEXP,
			array(
				"options" => array(
					"regexp" => "/^[A-Za-z0-9\s]{5,100}$/"
				)
			)
		);
		if( strtolower( $title ) == "i'm planning...") {
			$error['title'] = "Please enter an event title.";
			$flag = 3;
		}
		if( !($res) && $flag == 1 ) {
			$error['title'] = "Title can only contain spaces, characters A-Z or numbers 0-9";
			$flag = 2;
		}
		$this->smarty->append('error', $error, true);
		return $flag;
	}
	
	public function validate_desc($desc) {
		$flag = 1;
		$res = filter_var($desc, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[A-Za-z0-9\s]{10,500}$/")));
		if(!($res)) {
			$error['desc'] = "Description can only contain spaces, A-Z or 0-9";
			$flag=2;
		}
		$this->smarty->append('error', $error, true);
		return $flag;
	}

	public function validate_tm($tm) {
		$flag = 1;
		$res = filter_var($tm, FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^((0?[1-9]|1[012])(:[0-5]\d){0,2}(\ [AP]M))$|^([01]\d|2[0-3])(:[0-5]\d){0,2}$/")));
		if( !($res) ) {
			$error['time'] = "Please enter a time in 12 hour clock (12:30 PM) format.";
			$flag = 2;
		}
		$this->smarty->append('error', $error, true);
		return $flag;
	}

	public function validate_date($dt) {
		$flag = 1;
		$a_date = explode('/', $dt); 
		$month = $a_date[0];
		$day = $a_date[1];
		$year = $a_date[2]; 
		if( !@checkdate($month,$day,$year) ) {
			$error['date'] = "Please enter a valid date in mm/dd/yyyy format";
			$flag = 2;
		}
		$check = @mktime(0, 0, 0, $month, $day, $year,-1);
		$today = @mktime(0, 0, 0, date("m"), date("d"), date("y"),-1);
		if( $check < $today ) {
			$error['date'] = "Event date should be a date in the future.";
			$flag=3;
		}
		$this->smarty->append('error', $error, true);
		return $flag;
	}

	public function validate_ddt($ddt,$dt) {
		$flag = 1;
		$a_date = explode('/', $ddt); 
		$month = $a_date[0];
		$day = $a_date[1];
		$year = $a_date[2]; 
		$e_date = explode('/', $dt); 
		$evtMonth = $e_date[0];
		$evtDay = $e_date[1];
		$evtYear = $e_date[2]; 

		if( !@checkdate($month,$day,$year) ) {
			$error['deadline'] = "Please enter a valid date in mm/dd/yyyy format";
			$flag = 2;
		}
		$check = @mktime(0, 0, 0, $month, $day, $year,-1);
		$today = @mktime(0, 0, 0, date("m"), date("d"), date("y"),-1);
		if ( $check < $today ) {
			$error['deadline'] = "Deadline date should be a date in the future.";
			$flag = 2;
		}
		$evt_check = @mktime(0, 0, 0, $evtMonth, $evtDay, $evtYear,-1);
		if( $evt_check < $check ) {
			$error['deadline'] = "Deadline date cannot be greater than the event date.";
			$flag = 3;
		}
		$this->smarty->append('error', $error, true);
		return $flag;
	}

	public function validate_goal($goal) {
		$int_options = array(
			"options" => array(
				"min_range" => 1,
				"max_range" => 1000000
			)
		);

		if( ! filter_var($goal, FILTER_VALIDATE_INT, $int_options) ) {
			$error['goal'] = "Please enter a attendance goal between 1 and 1000000.";
			$this->smarty->append('error', $error, true);
			$flag = 2;
			return $flag;
		} else {
			$flag = 1;
			return $flag;
		}
	}

	public function validate_is_pub($isPub) {
		$flag = 1;
		if ( ! ( $isPub == 0 || $isPub == 1 ) ) {
			$error['pub'] = "Please Select the invite type.";
			$this->smarty->append('error', $error, true);
			$flag = 2;
		}
		return $flag;	
	}

	///////////////
	// End depreciated Validation

	/* validateEventInfo
	 * Makes sure event info is valid
	 *
	 * @param $newEvent | The event object
	 * @return true | The information is valid
	 * @return false | Infomration is bad
	 */
	public function validateEventInfo ( &$newEvent ) {
		// Check for errors
		$error = $newEvent->get_errors();
		$is_valid = ( $error === false ) ? true : false;

		// If there are errors
		if ( ! $is_valid ) {
			$this->smarty->assign('error', $error);
			return false;
		} 

		// Looks like it's valid ;)
		return true;
	}

	/* saveEventFields
     * Stores the current values for the new event
     * in an array that can be assigned in SMARTY
     * 
     * @param $newEvent | The event being saved
     * @return $event_field | The array of event information
	 */
	public function saveEventFields( $newEvent ) {

		// Save the current fields
		$event_field['title'] = $newEvent->get_title();
		$event_field['description'] = $newEvent->get_description();
		$event_field['address'] = $newEvent->get_address();
		$event_field['date'] = $newEvent->get_date();
		$event_field['time'] = $newEvent->get_time();
		$event_field['goal'] = $newEvent->get_goal();
		$event_field['deadline'] = $newEvent->get_deadline();
		$event_field['type'] = $newEvent->get_type();
		$event_field['permissions'] = $newEvent->get_permissions();
		$event_field['location_lat'] = $newEvent->get_lat();
		$event_field['location_long'] = $newEvent->get_long();
		
		$this->smarty->assign('event_field', $event_field);
	}

	/* makeNewEvent
	 * Adds the event to the database, then switches to step 2
	 *
	 * @param $newEvent | The VALIDATED event object
	 * @return true | The information is valid
	 * @return false | Infomration is bad
	 */
	public function makeNewEvent( $newEvent ) {
		// Make sure user is logged in before they can
		// create the event
		if ( ! isset($_SESSION['uid']) ) {
			header("Location: " . CURHOST . "/login?redirect=create");
			exit;
		} 

		$this->dbCon->createNewEvent($newEvent);

		$_SESSION['prev_eid'] = $_SESSION['newEvent']->title;
		// $_SESSION['prev_eid']
		
		unset($_SESSION['newEvent']);
		header("Location: " . CURHOST . "/create/guests");
		exit;
	}
	
	/* saveEvent
	 * Updates the event information in the databse
	 *
	 * @param $event | The VALIDATED event object
	 */
	public function saveEvent( $event ) {		
		$this->dbCon->updateEvent( $event );
		$this->smarty->assign("saved", true);
	}

	/*	MAIL FOR GUESTS
		require_once('models/EFMail.class.php');
		if ( is_array($newEvent) ) {
			$r = 0;
		} else {
			$newEvent = json_decode($_SESSION['newEvent'], true);
		}
		$addrss = $newEvent['address'];
		$addr = $this->check_address($addrss);	
		$newEvent['location_lat'] = $addr['lat'];
		$newEvent['location_long'] = $addr['lng'];	
		$this->dbCon->createNewEvent($newEvent);
		
		// INVITE GUESTS USING EMAIL
		$mailer = new EFMail();
		$eid = explode('/', $newEvent['url']);
		$newEvent['eid'] = $eid[sizeof($eid) - 1];

		$this->dbCon->storeGuests($newEvent['guests'], $newEvent['eid'], $_SESSION['uid']);
		$mailer->sendEmail($newEvent['guests'], $newEvent['eid'], $newEvent['title'], $newEvent['url']);
	*/

	/* function checkNewEvent
	 * Checks to see if the given event object
	 * is valid.
	 *
	 * @param $newEvent | The new event
	 * @param $loadCP | 
	 */
	public function checkNewEvent($newEvent, $loadCp) {
		if ( isset($newEvent) && $newEvent != NULL ) {
			$addr = $newEvent->address;
			$goal = $newEvent->goal;
			$title = $newEvent->title;
			$dt = $newEvent->date;
			$ddt = $newEvent->deadline;
			$isPub = $newEvent->is_public;
			$tm = $newEvent->time;
			$typ = $newEvent->type;
			$description = $newEvent->description;
			$aval = $this->validate_address($addr);
			$tval = $this->validate_title($title);
			$desc = $this->validate_desc($description);
			$gval = $this->validate_goal($goal);
			$dval = $this->validate_date($dt);
			$ddval = $this->validate_ddt($ddt,$dt);
			$tmval = $this->validate_tm($tm);
			$newEvent->time = date("H:i:s", strtotime($tm));
			$evtType = $this->validate_event_type($typ);
			$isPubVal = $this->validate_is_pub($isPub);
			// $_SESSION['newEvent'] = json_encode($newEvent);

			// Save the current event
			$_SESSION['newEvent'] = $newEvent;

			// die("here");

			// Validation error checks
			// Note: Need to either implement AJAX solution,
			// or 
			$err = "";

			if ( $isPubVal == 2 )
				$err .= "1,";
			else
				$err .= "0,";
			
			if ( $tmval == 2 )
				$err .= "1,";
			else
				$err .= "0,";

			if ( $ddval == 2 || $ddval == 3 )
				$err .= "$ddval,";
			else
				$err .= "0,";


			if ( $dval == 2 || $dval == 3 )
				$err .= "$dval,";
			else
				$err .= "0,";

			if ( $aval == 2 )
				$err .= "1,";
			else
				$err .= "0,";

			if ( $tval == 2 || $tval == 3 )
				$err .= "$tval,";
			else
				$err .= "0,";

			if ( $desc == 2 )
				$err .= "1,";
			else
				$err .= "0,";

			if ( $gval == 2 )
				$err .= "1,";
			else
				$err .= "0,";

			if ( $evtType == 0 )
				$err .= "1,";
			else
				$err .= "0,";

			if ( $err != "0,0,0,0,0,0,0,0,0," ) {
				// die($err);
				$this->smarty->assign('step1', ' class="current"');
				$this->smarty->display('create.tpl');
				return;
			}
		} else {
			$this->smarty->assign('step1', ' class="current"');
			$this->smarty-display('create.tpl');
			return;
		}

		// Make sure user is logged in before they can
		// create the event
		if (isset($_SESSION['uid'])) {
			if (isset($_SESSION['newEvent'])) {
				require_once('models/EFMail.class.php');
				if ( is_array($newEvent) ) {
					$r = 0;
				} else {
					$newEvent = json_decode($_SESSION['newEvent'], true);
				}
				$addrss = $newEvent['address'];
				$addr = $this->check_address($addrss);	
				$newEvent['location_lat'] = $addr['lat'];
				$newEvent['location_long'] = $addr['lng'];	
				$this->dbCon->createNewEvent($newEvent);
				
				// INVITE GUESTS USING EMAIL
				$mailer = new EFMail();
				$eid = explode('/', $newEvent['url']);
				$newEvent['eid'] = $eid[sizeof($eid) - 1];

				$this->dbCon->storeGuests($newEvent['guests'], $newEvent['eid'], $_SESSION['uid']);
				$mailer->sendEmail($newEvent['guests'], $newEvent['eid'], $newEvent['title'], $newEvent['url']);
			}

			$this->assignCPEvents($_SESSION['uid']);

			header("Location: http://localhost/Eventfii/create/guests");
			exit;

			$this->smarty->assign('step2', ' class="current"');
			$this->smarty->display('create.tpl');
		} else {
			$this->smarty->assign('error', "Please log in or create an account before you make a new event.");
			$this->smarty->display('login.tpl');
		}
	}

	public function checkGuests(&$eventInfo) {
		$eid = explode('/', $eventInfo->url);
		$eid = $eid[sizeof($eid) - 1];
		$csvFile = CSV_UPLOAD_PATH.'/'.$eid.'.csv';

		if ($_REQUEST['guest_email'] != '') {
			$eventInfo->setGuests($_REQUEST['guest_email']);
		} else if (file_exists($csvFile)) {
			$eventInfo->setGuestsFromCSV($csvFile);
		}
	}

	//checkUserCreationForm
	public function checkUserCreationForm($req) {
		$flag=1;
		$fname=$req['fname'];
		$lname=$req['lname'];
		$email=$req['email'];
		$phone=$req['phone'];
		$pass=$req['pass'];
		$zip=$req['zip'];

		if(strlen($zip)>0)
			$zipcode_val=$this->valUsingRegExp($zip,"/^\d{5}(-\d{4})?$/","user_create_zipcode","Please enter a valid zip code.");

		$f_name_val=$this->valUsingRegExp($fname,"/^[A-Za-z0-9']*$/","user_create_fname","First name can only contain A-Z 0-9 '");
		$l_name_val=$this->valUsingRegExp($lname,"/^[A-Za-z0-9']*$/","user_create_lname","Last name can only contain A-Z 0-9 '");
		$email_val=$this->valEmail($email,"user_create_email","Email entered is invalid.");
		$ph_val=$this->valUsingRegExp($phone,"/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/","user_create_phone","Phone number is not in valid format");
		$pass_val=$this->valUsingRegExp($pass,"/^[A-Za-z0-9]*$/","user_create_pass","Password can only contain A-Z 0-9");

		$email_exists=$this->dbCon->emailExistsCheck($email);
		if($f_name_val==2||$l_name_val==2||$email_val==2||
			 $pass_val==2||$ph_val==2||$zipcode_val==2) {
			$flag=2;
		}

		if(strlen($email_exists)>0) {
			$flag=2;
			$this->smarty->assign("user_create_email", "Email Id has been already registered once in the system.");
		}

		if(strlen($pass)<6) {
			$flag=2;
			$this->smarty->assign("user_create_pass","Please enter a password of atleast 6 characters in length");
		}
		return $flag;
	}


	public function validateSaveEmail($req) {
		$msg="<br>";
		$flag=0;
		$dt=$req['date'];
		$a_date = explode('/', $dt);
		$month = $a_date[0];
		$day = $a_date[1];
		$year = $a_date[2]; 
		if(!@checkdate($month,$day,$year)) {
			$msg.="Please enter a date in mm/dd/yyyy format. <br>";
			$flag=1;
		}

		$res=filter_var($req['subject'], FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^[A-Za-z0-9]*$/")));
		if (!($res)) {
			$flag=1;
			$msg.="Subject can only contain characters A-Z or numbers 0-9 <br>";
		}

		$res=filter_var($req['content'], FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^[A-Za-z0-9']*$/")));
		if(!($res)) {
			$flag=1;
			$msg.="Content can only contain characters A-Z or numbers 0-9 <br>";
		}

		if($flag==0) {
			$msg="Success";
		}

		return $msg;
	}

	public function valEmail($email,$tmp_var,$msg) {
		$flag=1;
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$this->smarty->assign($tmp_var,$msg);
			$flag=2;
		}
		return $flag;
	}

	public function valUsingRegExp($val,$regex,$tmp_var,$msg) {
		$flag=1;
		$res=filter_var($val, FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>$regex)));
		if(!($res)) {
			$this->smarty->assign($tmp_var,$msg);
			$flag=2;
		}
		return $flag;
	}

	////////////////
	// End Validate

	private function assignUserImage($userId) {
		if (file_exists('upload/user/'.$userId.'.png')) {
			$this->smarty->assign('userImage', CURHOST.'/upload/user/'.$userId.'.png');
		}	else if (file_exists('upload/user/'.$userId.'.jpg')) {
			$this->smarty->assign('userImage', CURHOST.'/upload/user/'.$userId.'.jpg');
		} else {
			$this->smarty->assign('userImage', CURHOST.'/images/default_thumb.jpg');
		}
	}
	
	private function checkHome() {
		if (isset($_SESSION['uid'])) {
			$this->assignCPEvents($_SESSION['uid']);
			$this->assignUserImage($_SESSION['uid']);
			$this->smarty->display('cp.tpl');
		} else {
			$this->smarty->display('index.tpl');
		}
	}

	private function assignCPEvents($uid) {
		// $this->smarty->assign('maxEventId', $this->dbCon->getMaxEventId());
		if ( ! $this->assignUserProfile($uid) )
			return false;
		
		$this->assignAttendingEvents($uid);
		$this->assignCreatedEvents($uid);
		
		return true;
	}

	private function assignUserProfile($uid) {
		/* if (isset($_SESSION['uid'])) {
			$currentUser = $this->dbCon->getUserInfo($_SESSION['uid']);
			$this->smarty->assign('currentUser', $currentUser);
		} */
		
		$userInfo = $this->dbCon->getUserInfo($uid);
		if ( ! $userInfo )
			return false;
		
		$paypalEmail = $this->dbCon->getPaypalEmail($uid);

		$this->smarty->assign('userInfo', $userInfo);
		$this->smarty->assign('paypalEmail', $paypalEmail['pemail']);
		return true;
	}

	private function assignAttendingEvents($uid) {
		$attendingEvents = $this->dbCon->getEventAttendingBy($uid);
		if ( sizeof($attendingEvents) == 0 )
			$this->smarty->assign('attendingExists', false);
		else 
			$this->smarty->assign('attendingEvents', $attendingEvents);
	}

	private function assignCreatedEvents($uid) {
		$createdEvents = $this->dbCon->getEventByEO($uid);
		if ( sizeof($createdEvents) == 0 )
			$this->smarty->assign('createdExists', false );
		else
			$this->smarty->assign('createdEvents', $createdEvents);
	}


	public function displayAttendeePage($eventId) {
		require_once('models/EFCore.class.php');
		$efCore = new EFCore();

		$eventAttendees = $this->dbCon->getAttendeesByEvent($eventId);
		$eventInfo = $this->dbCon->getEventInfo($eventId);

		for ($i = 0; $i < sizeof($eventAttendees); ++$i) {
			if ($eventAttendees[$i]['is_attending'] == 1) {
				$eventAttendees[$i]['checkedIn'] = 'checked = "checked"';
			}
		}

		$this->smarty->assign('trsvpVal', $efCore->computeTrueRSVP($eventId));
		$this->smarty->assign('eventAttendees', $eventAttendees);
		$this->smarty->assign('eventInfo', $eventInfo);
		$this->smarty->display('manage_event_on.tpl');
	}
	
	public function assignManageVars($eventId) {
		require_once('models/EFCore.class.php');
		$efCore = new EFCore();

		$eventInfo = $this->dbCon->getEventInfo($eventId);
		$numGuestConf1 = $this->dbCon->getNumAttendeesByConfidence($eventId, CONFOPT1);
		$numGuestConf2 = $this->dbCon->getNumAttendeesByConfidence($eventId, CONFOPT2);
		$numGuestConf3 = $this->dbCon->getNumAttendeesByConfidence($eventId, CONFOPT3);
		$numGuestConf4 = $this->dbCon->getNumAttendeesByConfidence($eventId, CONFOPT4);
		$numGuestConf5 = $this->dbCon->getNumAttendeesByConfidence($eventId, CONFOPT5);
		$numGuestConf6 = $this->dbCon->getNumAttendeesByConfidence($eventId, CONFOPT6);

		$numGuestNoResp = $this->dbCon->getNumAttendeesByConfidence($eventId, CONFELSE);

		$this->smarty->assign('eventInfo', $eventInfo);
		$this->smarty->assign('guestConf1', $numGuestConf1['guest_num']);
		$this->smarty->assign('guestConf2', $numGuestConf2['guest_num']);
		$this->smarty->assign('guestConf3', $numGuestConf3['guest_num']);
		$this->smarty->assign('guestConf4', $numGuestConf4['guest_num']);
		$this->smarty->assign('guestConf5', $numGuestConf5['guest_num']);
		$this->smarty->assign('guestConf6', $numGuestConf6['guest_num']);
		$this->smarty->assign('guestNoResp', $numGuestNoResp['guest_num']);

		$this->smarty->assign('guestimate', $efCore->computeGuestimate($eventId));
		$this->smarty->assign('trsvpVal', $efCore->computeTrueRSVP($eventId));
	}

	/* buildEvent
	 * Returns an Event Object given an Event ID
	 *
	 * @param $eventId | The event ID
	 * @return $event | The event object
	 */
	public function buildEvent($eventId) {
		$eventInfo = $this->dbCon->getEventInfo($eventId);
		
		$eventDateTime = explode(" ", $eventInfo['event_datetime']);
		
		$eventInfo['date'] = $this->dbCon->dateToRegular($eventDateTime[0]);
        $eventInfo['event_deadline'] = $this->dbCon->dateToRegular($eventInfo['event_deadline']);
		
		$eventTime = explode(":", $eventDateTime[1]);
		$eventInfo['time'] = $eventTime[0].":".$eventTime[1];
		
		$this->smarty->assign('eventInfo', $eventInfo);
		
		require_once('models/Event.class.php');
		$event = new Event( $_SESSION['uid'], $eventInfo['title'], $eventInfo['url'], $eventInfo['goal'], $eventInfo['location_address'], $eventInfo['date'], $eventInfo['time'], $eventInfo['event_deadline'], $eventInfo['description'], $eventInfo['cost'], $eventInfo['is_public'], $eventInfo['type'], $eventInfo['location_lat'], $eventInfo['location_long'] );
		$event->eid = $eventId;
		
		return $event;
	}

	/* function getEventIdByUri
	 * Gets the Event ID given a URI, returns
	 * false if invalid URI
	 *
	 * @param requestUri | The URI of the page to be displayed
	 * @return eventId | The event ID
	 * @return false | If invalid URI
	 */
	public function getEventIdByUri( $requestUri ) {
		$eventId = explode('/', $requestUri);
		
		// Verify that format of URL is http://{$CURHOST}/event/{$eventId}
		if (sizeof($eventId) != 3 ) {
			return false;
		}
		
		$eventId = $eventId[sizeof($eventId) - 1];
		
		return $eventId;
	}
	
	private function getUserIdByUri( $requestUri ) {
		$userId = explode('/', $requestUri);		

		// Verify that format of URL is http://{$CURHOST}/user/{$userId}
		if (sizeof($userId) != 3 ) {
			return false;
		}
		
		$userId = $userId[sizeof($userId)-1];
		// $userId = explode('?', $userId);
		// $userId = $eventId[0];
		
		return $userId;
	}
	
	/* function getView
	 * Determines which template files to display
	 * given a certain parameter.
	 *
	 * @param $requestUri | The URI of the current page
	 */
	public function getView($requestUri) {
	
		$requestUri = str_replace(PATH, '', $requestUri);
		
		// If mail invite reference, save in Session
		if (isset($_REQUEST['ref'])) {
			$_SESSION['ref'] = $_REQUEST['ref'];
		}
	
		// If /event in URI, display all event pages
		if (preg_match("/event\/\d+/", $requestUri) > 0) {
			require_once('models/EFTwitter.class.php');
			$twitter = new EFTwitter();
			$twitterHash = $twitter->getTwitterHash($eventId);
			$this->smarty->assign( 'twitterHash', $twitterHash );
			
			// Get a valid eventId
			$eventId = $this->getEventIdByUri( $requestUri );
			if ( ! $eventId ) {
				$this->smarty->display( 'error.tpl' );
				return;
			}
			
			$_SESSION['ceid'] = $eventId;
			$this->smarty->assign( 'eventId', $eventId );
			
			// Fetch event information from the database
			$eventInfo = $this->dbCon->getEventInfo($eventId);
			if ( ! $eventInfo ) {
				$this->smarty->display( 'error_event_notexist.tpl' );
				return;
			}
			
			// Make sure user is allowed to view the event
			if ( intval($eventInfo['is_public']) == 1 || ( isset( $_SESSION['uid'] ) && 
					 $this->dbCon->isInvited( $_SESSION['uid'], $eventId ) ) ) {
				$userInfo = $this->dbCon->getUserInfo($_SESSION['uid']);
				$this->smarty->assign('userInfo', $userInfo);
				
				$eventInfo['description'] = stripslashes($eventInfo['description']);
				$this->smarty->assign( 'eventInfo', $eventInfo );
				
				$organizerId = $eventInfo['organizer'];
				$organizerInfo = $this->dbCon->getUserInfo($organizerId);
				
				
				$this->smarty->assign( 'organizer', $organizerInfo );
				
				$curSignUp = $this->dbCon->getCurSignup($eventId);
				$this->smarty->assign( 'curSignUp', $curSignUp );
				
				$attending = $this->dbCon->getAttendeesByEvent($eventId);
				$this->smarty->assign( 'attending', $attending );
				
				// See if the user has responded
				$hasAttend = $this->dbCon->hasAttend($_SESSION['uid'], $eventId);
				
				$this->smarty->assign( 'conf' . $hasAttend['confidence'],  'checked = "checked"' );

				$this->smarty->display('event.tpl');
			} else {
				$this->smarty->display('error_event_private.tpl');
			}
			return;
		} // END /event
		
		// User public profile page
		if (preg_match("/user\/\d+/", $requestUri) > 0) {
			$userId = $this->getUserIdByUri( $requestUri );	
			$validUser = $this->assignCPEvents($userId);
			
			if ( ! $validUser )
				$this->smarty->display('error_user_notexist.tpl');
			else
				$this->smarty->display('profile.tpl');
			
			return;
		}
		
		// Remove GET parameters
		$getParamStartPos = strpos($requestUri, '?');
		if ($getParamStartPos) {
			$requestUri = substr($requestUri, 0, $getParamStartPos);
		}

		switch ($requestUri) {
			case '/':
				$this->checkHome();
				break;
			case '/contact':
				$this->smarty->display('contact.tpl');
				break;
			case '/share':
				$this->smarty->display('share.tpl');
				break;
			case '/method':
				$this->smarty->display('method.tpl');
				break;
			case '/settings':
			  $userInfo = $this->dbCon->getUserInfo($_SESSION['uid']);
				
				$this->smarty->assign('userInfo', $userInfo);	
				$this->smarty->display('settings.tpl');
				break;
			case '/settings/save':
				$this->dbCon->updateUserInfo($_REQUEST['fname'], $_REQUEST['lname'], $_REQUEST['email'], 
																		 $_REQUEST['phone'], $_REQUEST['zip'], $_REQUEST['twitter'], 
																     $_REQUEST['about'], $_REQUEST['features'], $_REQUEST['updates'], 
																		 $_REQUEST['attend']);
				
				if ($_REQUEST['curpass'] != '' &&
						$_REQUEST['newpass'] != '' &&
						$_REQUEST['confpass'] != '') {
					$this->dbCon->resetPassword(md5($_REQUEST['curpass']), 
																			md5($_REQUEST['newpass']), 
																			md5($_REQUEST['confpass']));
				}
				break;
			case '/create':
				require_once('models/Event.class.php');
			
				// Check to see if the user has submit the form yet
				if ( isset($_POST['submit']) ) {
					// Create an event object with the text from the form
					$newEvent = new Event( $_SESSION['uid'], $_POST['title'], $_POST['url'], $_POST['goal'], $_POST['address'], $_POST['date'], $_POST['time'], $_POST['deadline'], $_POST['description'], $_POST['cost'], $_POST['is_public'], $_POST['type'], '', '' );
				// See if it's their first time on the field
				} else if ( ! isset($_SESSION['newEvent']) ) {
					$this->smarty->assign('step1', ' class="current"');
					$this->smarty->display('create.tpl');
					break;
				// Check to see if they were working on the event before
				} else {
					$newEvent = unserialize($_SESSION['newEvent']);
				}				

				// Check to see if the new event is valid.
				if ( $this->validateEventInfo( $newEvent ) === false ) {
                    // Save the current information for the next visit
					$_SESSION['newEvent'] = serialize($newEvent);
                    
                    // Prepare the current values for display on the template
					$this->saveEventFields( $newEvent );
                    
					$this->smarty->assign('step1', ' class="current"');
					$this->smarty->display('create.tpl');
				} else {
					$this->makeNewEvent( $newEvent );
				}
				break;
			case '/create/guests':
				$this->smarty->assign('step2', ' class="current"');
				$this->smarty->display('create.tpl');
				break;
            /* Depreciated without JS
			case '/event/update':
				require_once('models/Event.class.php');
				$eventInfo = new Event(
					$_SESSION['uid'],
					$_REQUEST['title'], 
					$_REQUEST['url'], 
					$_REQUEST['goal'],
					$_REQUEST['address'], 
					$_REQUEST['date'],
					$_REQUEST['time'],
					$_REQUEST['deadline'],
					$_REQUEST['description'], 
					$_REQUEST['cost'],
					$_REQUEST['is_public'],
					$_REQUEST['type'], 
					0, 
					0 
				);

				$this->checkGuests($eventInfo);

				if ( $_REQUEST['eventId'] != -1 ) {
					$eventInfo->eid = $_REQUEST['eventId'];
					$this->smarty->assign('id', $_REQUEST['eventId']);
					$_SESSION['eventId'] = $_REQUEST['eventId'];
				} else {
					$this->smarty->assign('id', $_SESSION['eventId']);
					// $eventInfo['id']= $_SESSION['eventId'];
					$eventInfo->eid = $_SESSION['eventId'];
				}
				//$_SESSION['eventId']=$_REQUEST['eventId'];
				//print_r($_SESSION);
				//die();
				//////////////////////////////////////////
				$addr = $eventInfo->address;
				$goal = $eventInfo->goal;
				$title = $eventInfo->title;
				$dt = $eventInfo->date;
				$ddt = $eventInfo->deadline;
				$description = $eventInfo->description;
				$aval = $this->validate_address($addr);
				$tval = $this->validate_title($title);
				$desc = $this->validate_desc($description);
				$gval = $this->validate_goal($goal);
				$dval = $this->validate_date($dt);
				$ddval = $this->validate_ddt($ddt,$dt);

				if( $ddval == 2 || $dval == 2 || $aval == 2 || $tval == 2 || $tval == 3 || $desc == 2 || $gval == 2 ) {
					$this->smarty->display('manage_edit_form_errors.tpl');
					return;
				}

				$addrss = $eventInfo->address;
				$addr = $this->check_address($addrss);	
				$eventInfo->lat = $addr['lat'];
				$eventInfo->lng = $addr['lng'];		
				$eventInfo->time = date("H:i:s", strtotime($_REQUEST['time']));

				////////////////////////////////////////////
				//if($eventInfo->eid <=0)
				//$eventInfo->eid = $_SESSION['eventId'];
				//print_r($_SESSION);
				//die();

				 //  print_r($_REQUEST);
				 //die();
				 //echo("here");
				 //print_r($eventInfo->guests);
				 //die();
				 $this->dbCon->storeGuests($eventInfo->guests, $eventInfo->eid, $_SESSION['uid']);
				 //if(isset($_REQUEST['guest_email']))
				 //{
				 //  die("here12345");
				 require_once('models/EFMail.class.php');
				 $mailer = new EFMail();  
				 // die("here007");
				 $mailer->sendEmail(
					$eventInfo->guests, 
					$_REQUEST['eventId'], 
					$_REQUEST['title'], 
					$_REQUEST['url']
				);
				 //}
				$this->dbCon->updateEvent($eventInfo);
				$this->assignCPEvents($_SESSION['uid']);
				$this->smarty->display('cp_container.tpl');
				break;
            */
			case '/event/image/upload':
				require_once('models/FileUploader.class.php');
				// list of valid extensions, ex. array("jpeg", "xml", "bmp")
				$allowedExtensions = array("jpg");

				// max file size in bytes
				$sizeLimit = 10 * 1024 * 1024;

				$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
				$result = $uploader->handleUpload('upload/event/images/', TRUE);
				// to pass data through iframe you will need to encode all html tags
				echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
				break;
			case '/event/csv/upload':
				require_once('models/FileUploader.class.php');
				// list of valid extensions, ex. array("jpeg", "xml", "bmp")
				$allowedExtensions = array("csv");

				// max file size in bytes
				$sizeLimit = 10 * 1024 * 1024;

				$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
				$result = $uploader->handleUpload('upload/event/csv/', TRUE);
				// to pass data through iframe you will need to encode all html tags
				echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
				break;
			case '/event/attend':
				$_SESSION['attend_event'] = $this->dbCon->getEventInfo($_SESSION['ceid']);
				$this->dbCon->eventSignUp($_SESSION['uid'], $_SESSION['ceid'], $_REQUEST['conf']);
				break;
            case '/event/checkin':
				$isAttend = 1;
				if ($_REQUEST['checkin'] == 'false') {
					$isAttend = 0;
				}
				$this->dbCon->checkInGuest( $isAttend, $_REQUEST['guestId'], $_REQUEST['eventId'] );
				break;
			case '/event/print':
				$this->displayAttendeePage( $_REQUEST['eventId'] );
				break;
			case '/event/manage':                
        // Check the functionality of this, might be obsolete
				$eventAttendees = $this->dbCon->getAttendeesByEvent($_REQUEST['eventId']);
				for ($i = 0; $i < sizeof($eventAttendees); ++$i) {
					if ($eventAttendees[$i]['is_attending'] == 1) {
						$eventAttendees[$i]['checkedIn'] = 'checked = "checked"';
					}
				}
				$this->smarty->assign('eventAttendees', $eventAttendees);
				
				$this->assignManageVars( $_GET['eventId'] );
				$page['manage'] = ' class="current"';
				
				$_SESSION['manageEvent'] = $this->dbCon->getEventInfo($_REQUEST['eventId']);
				
				$this->smarty->assign('page', $page);
				$this->smarty->display('manage.tpl');
				break;
			case '/event/manage/edit':
                $page['edit'] = ' class="current"';
                $this->smarty->assign('page', $page);
				
				if ( ! isset ($_POST['submit']) ) {
					// $eventInfo = $this->dbCon->getEventInfo($_GET['eventId']);
					$editEvent = $this->buildEvent($_GET['eventId']);
					$this->saveEventFields( $editEvent );
					$this->smarty->display('manage_edit.tpl');
					break;
				}
				
				// Fill in event information
				require_once('models/Event.class.php');
				$editEvent = new Event( $_SESSION['uid'], $_POST['title'], $_POST['url'], $_POST['goal'], $_POST['address'], $_POST['date'], $_POST['time'], $_POST['deadline'], $_POST['description'], $_POST['cost'], $_POST['is_public'], $_POST['type'], $_POST['location_lat'], $_POST['location_long'] );
                $editEvent->eid = $_GET['eventId'];
				
                // Check to see if the new event information is valid.
				if ( $this->validateEventInfo( $editEvent ) === true ) {
					$this->saveEvent( $editEvent );
				}
				
				$this->saveEventFields( $editEvent );
				$this->smarty->display('manage_edit.tpl');
                
				break;
			case '/event/manage/guests':
				$eventAttendees = $this->dbCon->getAttendeesByEvent($_REQUEST['eventId']);
				$eventInfo = $this->dbCon->getEventInfo($_REQUEST['eventId']);

				$this->smarty->assign('eventInfo', $eventInfo);
				$page['manage'] = ' class="current"';
				$page['addguests'] = ' class="current"';
				$this->smarty->assign('page', $page);
				$this->smarty->display('manage_guests.tpl');
				break;
			case '/event/manage/guests/inviter':
				require_once('libs/OpenInviter/openinviter.php');
				$inviter = new OpenInviter();
				$oi_services = $inviter->getPlugins();

				if ( isset( $_REQUEST['oi_email'] ) && isset( $_REQUEST['oi_pass'] ) ) {
					$inviter->startPlugin($_REQUEST['oi_provider']);
					$internal = $inviter->getInternalError();
					if ( $internal && $this->DEBUG ) {
						print($internal);
					}
					$inviter->login( $_REQUEST['oi_email'], $_REQUEST['oi_pass'] );

					$_POST['oi_session_id'] = $inviter->plugin->getSessionID();
					$contactList = $inviter->getMyContacts();

					$this->smarty->assign('contactList', $contactList);
					$this->smarty->display('event_add_guest_import_contact_list.tpl');
				} else {
					$this->smarty->assign('provider', $_REQUEST['provider']);
					$this->smarty->display('event_add_guest_right.tpl');
				}
				break;
			case '/event/manage/guests/save':
				require_once('models/Event.class.php');
				require_once('models/EFMail.class.php');

				$eventInfoDB = $this->dbCon->getEventInfo($_REQUEST['eventId']);
				$eventInfo = new Event(
					$_SESSION['uid'],
					$eventInfoDB['title'], 
					$eventInfoDB['url'], 
					$eventInfoDB['goal'],
					$eventInfoDB['address'], 
					$eventInfoDB['date'],
					$eventInfoDB['time'],
					$eventInfoDB['deadline'],
					$eventInfoDB['description'], 
					$eventInfoDB['cost'],
					$eventInfoDB['is_public'],
					$eventInfoDB['gets'],
					0,
					0
				);
				$eventInfo->eid = $_REQUEST['eventId'];

				$this->checkGuests($eventInfo);

				$mailer = new EFMail();
				$mailer->sendEmail($eventInfo->guests, $eventInfo->eid, $eventInfo->title, $eventInfo->url);
				$this->dbCon->storeGuests($eventInfo->guests, $_REQUEST['eventId'], $_SESSION['uid']);
				break;
			case '/event/manage/email':
				$this->assignManageVars($_REQUEST['eventId']);
				
				$eventReminder = $this->dbCon->getEventEmail($_REQUEST['eventId'], EMAIL_REMINDER_TYPE);
				$eventFollowup = $this->dbCon->getEventEmail($_REQUEST['eventId'], EMAIL_FOLLOWUP_TYPE);

				if ( $eventReminder['is_activated'] == 1 ) {
					$eventReminder['isAuto'] = 'checked = "checked"';
				}
				if ( $eventFollowup['is_activated'] == 1 ) {
					$eventFollowup['isAuto'] = 'checked = "checked"';
				}

				$this->smarty->assign('eventReminder', $eventReminder);
				$this->smarty->assign('eventFollowup', $eventFollowup);
				$page['manage'] = ' class="current"';
				$page['email'] = ' class="current"';
				
				
				
				$this->smarty->assign('page', $page);
				$this->smarty->display('manage_email.tpl');
				break;
			case '/event/email/reminder':
				$eventInfo = $this->dbCon->getEventInfo($_REQUEST['eventId']);
				
				$eventReminder = $this->dbCon->getEventEmail($_REQUEST['eventId'], EMAIL_REMINDER_TYPE);
				if ($eventReminder['is_activated'] == 1) {
					$eventReminder['isAuto'] = 'checked = "checked"';
				}
				if ($eventFollowup['is_activated'] == 1) {
					$eventFollowup['isAuto'] = 'checked = "checked"';
				}
				
				$this->smarty->assign('eventInfo', $eventInfo);
				$this->smarty->assign('emailInfo', $eventReminder);
				
				$page['manage'] = ' class="current"';
				$page['email'] = ' class="current"';
				$this->smarty->assign('page', $page);
				
				$this->smarty->display('manage_event_email.tpl');
				break;
			case '/event/email/followup':
				$eventInfo = $this->dbCon->getEventInfo($_REQUEST['eventId']);
				
				$eventFollowup = $this->dbCon->getEventEmail($_REQUEST['eventId'], EMAIL_FOLLOWUP_TYPE);
				
				$this->smarty->assign('eventInfo', $eventInfo);
				$this->smarty->assign('emailInfo', $eventFollowup);
				
				$page['manage'] = ' class="current"';
				$page['email'] = ' class="current"';
				$this->smarty->assign('page', $page);
				
				$this->smarty->display('manage_event_email.tpl');
				break;
			case '/event/manage/email/save':			
				$sqlDate = $this->dbCon->dateToSql($_REQUEST['reminderDate']);
				$dateTime = $sqlDate." ".$_REQUEST['reminderTime'].":00";
				$autoReminder = 0;
				if ($_REQUEST['autoReminder'] == 'true') {
					$autoReminder = 1;
				}

				// TODO: more meaningful error messages
				//$req['content'] = $_REQUEST['reminderContent'];
				//$req['subject'] = $_REQUEST['reminderSubject'];
				//$req['date'] = $_REQUEST['reminderDate'];
				//$retval = $this->validateSaveEmail($req);
				//if( $retval != "Success" ) {
				//	die($retval);
				//}

				$this->dbCon->saveEmail($_SESSION['manageEvent']['id'], 
															  $_REQUEST['reminderContent'], 
																$dateTime, 
																$_REQUEST['reminderSubject'], 
																EMAIL_REMINDER_TYPE, 
																$autoReminder);
				echo("Success");
				break;
			case '/event/manage/email/send':
				require_once('models/EFMail.class.php');
				$mailer = new EFMail();
				$attendees = $this->dbCon->getAttendeesByEvent($_SESSION['manageEvent']['id']);
				$req['content'] = $_REQUEST['reminderContent'];
				$req['subject'] = $_REQUEST['reminderSubject'];
				$req['type'] = $_REQUEST['type'];
				$req['date'] = $_REQUEST['reminderDate'];
				
				// BUGGY, NEED BETTER ERROR MESSAGE
				//$retval = $this->validateSaveEmail($req);
				// if( $retval != "Success" ) {
				//	die($retval);
				//}
	
				$mailer->sendAutomatedEmail($_SESSION['manageEvent'], 
																		$_REQUEST['reminderContent'], 
																		$_REQUEST['reminderSubject'], 
																		$attendees);
				echo("Success");
				break;
			case '/event/manage/email/autosend':
				$isActivated = 0;
				if ($_REQUEST['autoSend'] == 'true') {
					$isActivated = 1;
				}
				$this->dbCon->setAutosend($_REQUEST['eventId'], $_REQUEST['type'], $isActivated);
				break;
			case '/event/manage/text':
				$eventInfo = $this->dbCon->getEventInfo($_REQUEST['eventId']);
				$this->smarty->assign('eventInfo', $eventInfo);				
				$page['manage'] = ' class="current"';
				$page['text'] = ' class="current"';
				$this->smarty->assign('page', $page);
				
				$this->smarty->display('manage_text.tpl');
				break;
			case '/event/manage/text/send':
				require_once('models/EFSMS.class.php');
				$sms = new EFSMS();
				
				$attendees = $this->dbCon->getAttendeesByEvent($_SESSION['manageEvent']['id']);
				$sms->sendSMSReminder($attendees, $_SESSION['manageEvent']['id']);
				print("Success");
				break;
			case '/event/manage/text/save':
				$sqlDate = $this->dbCon->dateToSql($_REQUEST['reminderDate']);
				$dateTime = $sqlDate." ".$_REQUEST['reminderTime'].":00";
				$autoReminder = 0;
				if ($_REQUEST['autoReminder'] == 'true') {
					$autoReminder = 1;
				}
				$this->dbCon->saveText($_SESSION['manageEvent']['id'], 
															 $_REQUEST['reminderContent'], 
															 $dateTime, 
															 SMS_REMINDER_TYPE, 
															 $autoReminder);
				break;
			case '/login':
				if (!isset($_SESSION['uid'])) {
					if (isset($_REQUEST['email']) && isset($_REQUEST['pass'])) {
						$userId = $this->dbCon->checkValidUser($_REQUEST['email'], $_REQUEST['pass']);
						if(!isset($userId)) {
								echo("1"); //login failed
								break;
						}
					}
					if (isset($userId)) {
						$_SESSION['uid'] = $userId;
						$usrPic=$this->dbCon->getUserPic($userId);
						if(isset($usrPic) && $usrPic!="")
						  $_SESSION['userProfilePic']="upload/user/".$usrPic;
						else
						  $_SESSION['userProfilePic']="images/default_thumb.jpg";
					}

					/*
					if (isset($_SESSION['newEvent']))  {
						$newEvent = json_decode($_SESSION['newEvent'], true);
						$newEvent['organizer'] = $userId;
						$this->checkNewEvent($newEvent, false);
						break;
					}
					*/
				    $this->smarty->display('login.tpl');
					break;
				}
				$this->checkHome();
				break;
			case '/login/reset':
				if ($this->dbCon->isValidPassResetRequest($_REQUEST['ref'])) {
					$this->smarty->assign('ref', $_REQUEST['ref']);
					$this->smarty->display('login_reset.tpl');
				} else {
					$this->smarty->display('login_reset_invalid.tpl');
				}
				break;
			case '/login/reset/submit':
				if ($_REQUEST['login_forgot_newpass'] == $_REQUEST['login_forgot_newpass_conf']) {
					$this->dbCon->resetPasswordByEmail($_REQUEST['login_forgot_newpass'], $_REQUEST['login_forgot_ref']);
					$this->smarty->display('login_reset_confirmed.tpl');
				} else {
					$this->smarty->assign('ref', $_REQUEST['ref']);
					$this->smarty->assign('errorMsg', 'New password is not confirmed');
					$this->smarty->display('login_reset.tpl');
				}
				break;
			case '/login/forgot':
				$this->smarty->display('login_forgot.tpl');
				break;
			case '/login/forgot/submit':
				require_once('models/EFMail.class.php');
				$mailer = new EFMail();

				$hash_key = md5(time().$_REQUEST['login_forgot_email']);

				if ($this->dbCon->requestPasswordReset($hash_key, $_REQUEST['login_forgot_email'])) {
					$mailer->sendResetPassLink('/login/reset', $hash_key, $_REQUEST['login_forgot_email']);
					$this->smarty->display('login_forgot_confirmed.tpl');
				} else {
					$this->smarty->display('login_forgot_invalid.tpl');
				}
				break;
			case '/user/create':
				$req['fname'] = $_REQUEST['fname'];
				$req['lname'] = $_REQUEST['lname'];
				$req['email'] = $_REQUEST['email'];
				$req['phone'] = $_REQUEST['phone'];
				$req['pass'] = $_REQUEST['pass'];
				$req['zip'] = $_REQUEST['zipcode'];
				$retVal = $this->checkUserCreationForm($req);
				
				if( $retVal == 2 ) {
					$this->smarty->display('login.tpl');
					break;
				}

				// Create the new user
				$userInfo = $this->dbCon->createNewUser(
					$_REQUEST['fname'], 
					$_REQUEST['lname'], 
					$_REQUEST['email'], 
					$_REQUEST['phone'], 
					$_REQUEST['pass'],
					$_REQUEST['zipcode']
				);

				// Assign user's SESSION variables
				$_SESSION['uid'] = $userInfo['id'];
				$_SESSION['userProfilePic']="images/default_thumb.jpg";

				/*
				if ( isset( $_SESSION['newEvent'] ) ) {	
					$newEvent = json_decode( $_SESSION['newEvent'], true );
					$newEvent['organizer'] = $userInfo['id'];
				}
				$this->checkNewEvent($newEvent, true);
				*/				
				break;
			case '/user/fb/create':
				$userInfo = $this->dbCon->createNewUser(
					$_REQUEST['fname'], 
					$_REQUEST['lname'], 
					$_REQUEST['email'], 
					$_REQUEST['phone'], 
					$_REQUEST['pass']
				);

				$usrPic  = $this->dbCon->getUserPic($userInfo['id']);

				// How would they have added a picture of they
				// just registered?
				if( ! isset( $usrPic ) || $usrPic == "" ) {
					$uppic = $_REQUEST['pic'];
					$this->smarty->assign('userProfilePic', $uppic);
					$_SESSION['userProfilePic']=$uppic;
				} else {
					$_SESSION['userProfilePic']="upload/user/".$usrPic;
				}

				$_SESSION['uid'] = $userInfo['id'];

				/* Requires login before creating now
				 *
				if ( isset( $_SESSION['newEvent'] ) ) {	
					$newEvent = json_decode( $_SESSION['newEvent'], true );
					$newEvent['organizer'] = $userInfo['id'];
					$this->checkNewEvent($newEvent, true);
				} */

				break;
			case '/user/image/upload':
				require_once('models/FileUploader.class.php');
				// list of valid extensions, ex. array("jpeg", "xml", "bmp")
				$allowedExtensions = array("jpg");

				// max file size in bytes
				$sizeLimit = 2 * 1024 * 1024;

				$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
				$result = $uploader->handleUpload('upload/user/', TRUE);
				// to pass data through iframe you 
				// will need to encode all html tags
				echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);

				$this->dbCon->saveUserPic();
				break;
			case '/user/status/update':
				$this->dbCon->updateUserStatus($_REQUEST['value']);
				echo($_REQUEST['value']);	
				break;
			case '/user/profile/update':
				$this->dbCon->updatePaypalEmail($_SESSION['uid'], $_REQUEST['paypal_email']);
				$this->assignUserProfile($_SESSION['uid']);

				$this->smarty->display('user_profile.tpl');
				break;
			case '/user/profile-dtls/update':
				$email = $_POST['email'];
				$zip = $_POST['zip'];
				$cell = $_POST['cell'];
				$res = "";
				if( ! filter_var($email, FILTER_VALIDATE_EMAIL) )
					$res = $res."1,";
				else
					$res = $res."0,";
				if( ! (filter_var($zip, FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^\d{5}(-\d{4})?$/")))) )
					$res = $res."1,";
				else
					$res=$res."0,";
				if( !(filter_var($cell, FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/")))) )
					$res = $res."1,";
				else
					$res = $res."0,";

				if( $res == "0,0,0," ) {
					$this->dbCon->updateUserProfileDtls($email,$zip,$cell);
					echo $res;
				} else {
					echo $res;
				}
				break;
			case '/event/payment/submit':
				require_once('models/PaypalPreapproveReceipt.class.php');

				$paypalPreapprove = new PaypalPreapproveReceipt();
				$paypalPreapprove->preapprove();
				break;
			case '/event/payment/success':
				require_once('models/PaypalPreapproveDetails.class.php');
				
				if ( $this->dbCon->eventSignUp($_SESSION['uid'], $_SESSION['attend_event']['id'] && isset($_SESSION['uid']) ) ) {
					$paypalPreapprove = new PaypalPreapproveDetails();
					$paypalPreapprove->preapprove();
					$this->dbCon->preapprovePayment(
						$_SESSION['uid'],
						$_SESSION['attend_event']['id'], 
						$paypalPreapprove->preapprovalKey, 
						$paypalPreapprove->response->senderEmail
					);

				  $userInfo = $this->dbCon->getUserInfo($_SESSION['uid']);
					$this->smarty->assign('userInfo', $userInfo);

					$this->smarty->display('payment_success.tpl');
					break;
				}
				$this->smarty->display('payment_failed.tpl');
				break;
			case '/event/payment/failed':
				$this->smarty->display('payment_failed.tpl');
				break;
			case '/payment/collect':
				require_once('models/PaypalPayReceipt.class.php');
				$paypalPay = new PaypalPayReceipt();

				$attendees = $this->dbCon->getAttendees($_REQUEST['eventId']);

				// TODO: NON-ATOMIC OPERATION
				// PayPal doesn't provide an API to receive payments from multiple senders
				// But it provides an API to send payments to multiple receivers
				// https://www.x.com/thread/52330?stqc=true
				for ($i = 0; $i < sizeof($attendees); ++$i) {
					$paypalPay->pay($attendees[$i]['pemail'], $_REQUEST['receiver_email'], 
													$attendees[$i]['cost'], $attendees[$i]['pkey']);
				}
				$this->dbCon->updateCollected($_REQUEST['eventId']);
				$this->assignCreatedEvents($_SESSION['uid']);
				$this->smarty->display('event_created.tpl');
				break;
			case '/logout':
				if ( ! isset($_SESSION['uid']) ) {
					$this->smarty->display('error.tpl');
					break;
				}
				session_unset();
				session_destroy();
				$this->smarty->display('index.tpl');
				break;
			default:
				$this->smarty->assign('requestUri', $requestUri);
				$this->smarty->display('error.tpl');
				break;
		}
	}
}
