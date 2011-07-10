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
	
	function __construct($smarty) {
		$this->smarty = $smarty;
		$this->dbCon = new DBConfig();
	}
	
	function __destruct() {
		
	}
	
	private function assignCPEvents($uid) {
		$this->smarty->assign('maxEventId', $this->dbCon->getMaxEventId());
		
		$this->assignCreatedEvents($uid);
		$this->assignUserProfile($uid);
		$this->assignAttendingEvents($uid);
	}
	
	private function assignAttendingEvents($uid) {
		$attendingEvents = $this->dbCon->getEventAttendingBy($uid);
		$this->smarty->assign('attendingEvents', $attendingEvents);
	}
	
	private function assignCreatedEvents($uid) {
		$createdEvents = $this->dbCon->getEventByEO($uid);
		$this->smarty->assign('createdEvents', $createdEvents);
	}
	
	private function assignUserProfile($uid) {
		$userInfo = $this->dbCon->getUserInfo($uid);
		if (isset($_SESSION['uid'])) {
			$currentUser = $this->dbCon->getUserInfo($_SESSION['uid']);
			$this->smarty->assign('currentUser', $currentUser);
		}
		$paypalEmail = $this->dbCon->getPaypalEmail($uid);
		
		$this->smarty->assign('paypalEmail', $paypalEmail['pemail']);
		$this->smarty->assign('userInfo', $userInfo);
	}
	
	private function checkHome() {
		if (isset($_SESSION['uid'])) {
			$this->assignCPEvents($_SESSION['uid']);
			
			$this->smarty->display('cp.tpl');
		} else {
			$newEvents = $this->dbCon->getNewEvents();

			$this->smarty->assign('newEvents', $newEvents);
			$this->smarty->display('home.tpl');
		}
	}
	
	////////////////////
	
	public function check_address($addr)
	{
	$a = urlencode($addr);
	$retVal=array();
	$geocodeURL = "http://maps.googleapis.com/maps/api/geocode/json?address=$a&sensor=false";
   $ch = curl_init($geocodeURL);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   $result = curl_exec($ch);
   $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
   curl_close($ch);
   if ($httpCode == 200) {
      $geocode = json_decode($result);
      $lat = $geocode->results[0]->geometry->location->lat;
      $lng = $geocode->results[0]->geometry->location->lng; 
      $formatted_address = $geocode->results[0]->formatted_address;
      $geo_status = $geocode->status;
      $location_type = $geocode->results[0]->geometry->location_type;
      $retVal['location_type']=$location_type;
      $retVal['lat']=$lat;
      $retVal['lng']=$lng;	
   } else {
     $retVal['location_type']="error";
     $retVal['lat']=$lat;
     $retVal['lng']=$lng;
	
   }
return $retVal;
}

	public function validate_event_type($val)
	{
		$flag=1;
		if($val<=0)
			$flag=0;
		return $flag;
	}
	
	
	
	/////////////////////
	public function validate_address($addr)
	{
//	die("5");
			$flag=1;
			if($addr == "")
			{
			$this->smarty->assign('error_address', "Please enter an address");
			$flag=2;
		//	return $flag;
			}
			$retArr=$this->check_address($addr);
			if(!($retArr['location_type']=="RANGE_INTERPOLATED" || $retArr['location_type']=="ROOFTOP"))
			{
				$this->smarty->assign('error_address', "Address entered is invalid");
				$flag=2;
				//die($addr."=".$retArr['location_type']);
				//return $flag;
			}	
			$res=filter_var($addr, FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^[A-Za-z0-9\s-,*]*$/")));
		if(!($res))
			{
			$this->smarty->assign('error_address', "Address can only contain spaces,  A-Z, 0-9 or -*,@&");
			$flag=2;
			}
		//die("manu=$addr=$flag");
		return $flag;
	}
	
	public function validate_title($title)
	{
	$flag=1;
	/*	if(strlen($title)<5)
			{
			$this->smarty->assign('error_title', "Please enter a title which is atleast 5 characters in length");
			$flag=2;
			//return 2;
			}*/
		$res=filter_var($title, FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^[A-Za-z0-9\s]{5,100}$/")));
		//die(strtolower($title));
		if(strtolower($title)=="i'm planning...")
			{
			$this->smarty->assign('error_title', "Please enter an event title.");
			$flag=3;
			}
		if(!($res) && $flag==1)
			{
			$this->smarty->assign('error_title', "Title can only contain spaces, characters A-Z or numbers 0-9");
			$flag=2;
			}
	return $flag;
	}
	
	
	public function validate_desc($desc)
	{
	$flag=1;
	$res=filter_var($desc, FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^[A-Za-z0-9\s]{25,500}$/")));
		if(!($res))
			{
			$this->smarty->assign('error_desc', "Description can only contain spaces,  A-Z or  0-9");
			$flag=2;
			}
	return $flag;
	}
	
	
	public function validate_tm($tm)
	{
	$flag=1;
	$res=filter_var($tm, FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^((0?[1-9]|1[012])(:[0-5]\d){0,2}(\ [AP]M))$|^([01]\d|2[0-3])(:[0-5]\d){0,2}$/")));
		if(!($res))
			{
			$this->smarty->assign('error_tm', "Please enter a time in 12 hour clock (12:30 PM) format.");
			$flag=2;
			}
			//die($tm);
	return $flag;
	}
	
	
	public function validate_date($dt)
	{
		  $flag=1;
		  $a_date = explode('/', $dt); 
		  $month = $a_date[0];
          $day = $a_date[1];
          $year = $a_date[2]; 
			if(!@checkdate($month,$day,$year))
				{
				$this->smarty->assign('error_dt', "Please enter a valid date in mm/dd/yyyy format");
				$flag=2;
				}
			$check = @mktime(0, 0, 0, $month, $day, $year,-1);
			$today = @mktime(0, 0, 0, date("m"), date("d"), date("y"),-1);
			   if($check < $today)
				{
					$this->smarty->assign('error_dt', "Event date should be a date in the future.");
					$flag=3;
				}
		return $flag;
	}
	
	public function validate_ddt($ddt,$dt)
	{
		  $flag=1;
		  $a_date = explode('/', $ddt); 
		  $month = $a_date[0];
          $day = $a_date[1];
          $year = $a_date[2]; 
		  
		  $e_date = explode('/', $dt); 
		  $evtMonth = $e_date[0];
          $evtDay = $e_date[1];
          $evtYear = $e_date[2]; 
		  
			if(!@checkdate($month,$day,$year))
				{
				$this->smarty->assign('error_ddt', "Please enter a valid date in mm/dd/yyyy format");
				$flag=2;
				}
			$check = @mktime(0, 0, 0, $month, $day, $year,-1);
			$today = @mktime(0, 0, 0, date("m"), date("d"), date("y"),-1);
			   if($check < $today)
				{
					$this->smarty->assign('error_ddt', "Deadline date should be a date in the future.");
					$flag=2;
				}
			$evt_check = @mktime(0, 0, 0, $evtMonth, $evtDay, $evtYear,-1);
				if($evt_check < $check)
				{
					$this->smarty->assign('error_ddt', "Deadline date cannot be greater than the event date.");
					$flag=3;
				}
		return $flag;
	}
	
	public function validate_goal($goal)
	{
	$int_options = array(
			"options"=>array
							(
							"min_range"=>1,
							"max_range"=>1000000
							)
						);

		if(!filter_var($goal, FILTER_VALIDATE_INT, $int_options))
		{
			$this->smarty->assign('error_goal', "Please enter a attendance goal between 1 and 1000000.");
			$flag=2;
			return $flag;
		}
		else
		{
			$flag=1;
			return $flag;
		}
	}
	
	public function validate_is_pub($isPub)
	{
	$flag=1;
		if(!($isPub==0 || $isPub==1))
		{
			$this->smarty->assign('error_is_pub', "Please Select the invite type.");
			$flag=2;
		}
	return $flag;	
	}
	
	public function checkNewEvent($newEvent, $loadCp) {
		if (isset($newEvent) && $newEvent!=NULL) {
			if(is_array($newEvent)) {
				$r=0;
			} else {
			//	die("here");
				$addr=$newEvent->address;
				$goal=$newEvent->goal;
				$title=$newEvent->title;
				$dt=$newEvent->date;
				$ddt=$newEvent->deadline;
				$isPub=$newEvent->is_public;
				$tm=$newEvent->time;
				$typ=$newEvent->type;
				$description=$newEvent->description;
				$aval=$this->validate_address($addr);
				$tval=$this->validate_title($title);
				$desc=$this->validate_desc($description);
				$gval=$this->validate_goal($goal);
				$dval=$this->validate_date($dt);
				$ddval=$this->validate_ddt($ddt,$dt);
				$tmval=$this->validate_tm($tm);
				$newEvent->time=date("H:i:s", strtotime($tm));
				//die($newEvent->time);
				$evtType=$this->validate_event_type($typ);
				$isPubVal=$this->validate_is_pub($isPub);
				$_SESSION['newEvent'] = json_encode($newEvent);
				$err="";
				
				if ($isPubVal==2) 
					$err.="1,";
				else
					$err.="0,";
				
				if ($tmval==2)
					$err.="1,";
				else
					$err.="0,";

				if ($ddval==2 || $ddval==3) 
					$err.="$ddval,";
				else
					$err.="0,";

				
				if ($dval==2 || $dval==3) 
					$err.="$dval,";
				else
					$err.="0,";
				
				if($aval==2) 
					$err.="1,";
				else
					$err.="0,";
				
				if ($tval==2 || $tval==3) 
					$err.="$tval,";
				else
					$err.="0,";

				if ($desc==2) 
					$err.="1,";
				else
					$err.="0,";
					
				if ($gval==2) 
					$err.="1,";
				else
					$err.="0,";
					
				if($evtType==0)
					$err.="1,";
				else
					$err.="0,";
					
				if($err!="0,0,0,0,0,0,0,0,0,")
					die($err);
				//else
				//	echo($err);
				
				
					
				}
			}
		
		if (isset($_SESSION['uid'])) {
			if (isset($_SESSION['newEvent'])) {
				require_once('models/EFMail.class.php');
				if(is_array($newEvent)) {
					$r=0;
				} else {
					$newEvent = json_decode($_SESSION['newEvent'], true);
				}
				$addrss=$newEvent['address'];
				$addr=$this->check_address($addrss);	
				$newEvent['location_lat']=$addr['lat'];
				$newEvent['location_long']=$addr['lng'];	
				$this->dbCon->createNewEvent($newEvent);
				
				// INVITE GUESTS USING EMAIL
				$mailer = new EFMail();

				$eid = explode('/', $newEvent['url']);
				$newEvent['eid'] = $eid[sizeof($eid) - 1];
				
				$this->dbCon->storeGuests($newEvent['guests'], $newEvent['eid'], $_SESSION['uid']);
				$mailer->sendEmail($newEvent['guests'], $newEvent['eid'], $newEvent['title'], $newEvent['url']);
			}
			
			$this->assignCPEvents($_SESSION['uid']);
			
			if ($loadCp) {
				$this->smarty->display('cp.tpl');
			} else {
				//mm $this->smarty->display('cp_container.tpl');
				$this->smarty->display('cp_middle.tpl');
			}
		} else {
			$this->smarty->display('login.tpl');
			//nn $this->smarty->display('login_form.tpl');
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
		if($f_name_val==2||$l_name_val==2||$email_val==2||$pass_val==2||$ph_val==2||$zipcode_val==2) {
			$flag=2;
		}
		
		if(strlen($email_exists)>0) {
			$flag=2;
			$this->smarty->assign("user_create_email","Email Id has been already registered once in the system.");
		}
		
		if(strlen($pass)<6) {
			//die($pass);
			$flag=2;
			$this->smarty->assign("user_create_pass","Please enter a password of atleast 6 characters in length");
		}
			
		//die($pass);
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
	
	public function assignEditEventVars($eventId) {
		$eventInfo = $this->dbCon->getEventInfo($eventId);
		
		$eventDateTime = explode(" ", $eventInfo['event_datetime']);
		$eventDate = $this->dbCon->dateToRegular($eventDateTime[0]);
		
		$eventTime = explode(":", $eventDateTime[1]);
		$eventTime = $eventTime[0].":".$eventTime[1];
		
		$eventInfo['event_datetime'] = $eventDate." ".$eventTime;
		$eventInfo['event_deadline'] = $this->dbCon->dateToRegular($eventInfo['event_deadline']);
		
		$isPublic = $eventInfo['is_public'];
		$isEventPrivate = '';
		$isEventPublic = '';
		if ($isPublic == 1) {
			$isEventPublic = 'checked = "checked"';
		} else {
			$isEventPrivate = 'checked = "checked"';
		}
		
		// Event type presentation
		$eventType = array(
			't1' => "",
			't2' => "",
			't3' => "",
			't4' => "",
			't5' => "",
			't6' => "",
			't7' => "",
			't8' => "",
			't9' => "",
			't10' => "",
			't11' => "",
			't12' => "",
			't13' => "",
			't14' => "",
			't15' => "",
			't16' => ""
		);
		$eventType['t'.$eventInfo['type']] = 'selected = "selected"';
		
		$this->smarty->assign('isEventPublic', $isEventPublic);
		$this->smarty->assign('isEventPrivate', $isEventPrivate);
		
		$this->smarty->assign('eventType', $eventType);
		$this->smarty->assign('eventDate', $eventDate);
		$this->smarty->assign('eventTime', $eventTime);
		$this->smarty->assign('eventInfo', $eventInfo);
	}
	
	public function getView($requestUri) {
		$requestUri = str_replace(PATH, '', $requestUri);
		
		// Check for email invite reference
		if (isset($_REQUEST['ref'])) {
			$_SESSION['ref'] = $_REQUEST['ref'];
		}
		
		// Event profile page
		if (preg_match("/event\/\d+/", $requestUri) > 0) {
			require_once('models/EFTwitter.class.php');
			$twitter = new EFTwitter();

			$eventId = explode('/', $requestUri);
			$eventId = $eventId[sizeof($eventId)-1];
			
			$eventId = explode('?', $eventId);
			$eventId = $eventId[0];
			
			$eventInfo = $this->dbCon->getEventInfo($eventId);
			$organizer = $this->dbCon->getUserInfo($eventInfo['organizer']);
			$curSignUp = $this->dbCon->getCurSignup($eventId);
			
			$_SESSION['ceid'] = $eventId;
			
			if (isset($_SESSION['uid'])) {
				$hasAttend = $this->dbCon->hasAttend($_SESSION['uid'], $eventId);
				
				$this->smarty->assign('conf'.$hasAttend['confidence'], 'checked = "checked"');
				$currentUser = $this->dbCon->getUserInfo($_SESSION['uid']);
				$this->smarty->assign('currentUser', $currentUser);
			}
			$eventInfo['description'] = stripslashes($eventInfo['description']);
			
			$this->smarty->assign('organizer', $organizer);
			$this->smarty->assign('eventInfo', $eventInfo);
			$this->smarty->assign('eventId', $eventId);
			$this->smarty->assign('curSignUp', $curSignUp);
			$this->smarty->assign('twitterHash', $twitter->getTwitterHash($eventId));

			
			if (isset($_SESSION['uid'])) {
				if (intval($eventInfo['is_public']) == 1 || $this->dbCon->isInvited($_SESSION['uid'], $eventId)) {
					$userInfo = $this->dbCon->getUserInfo($_SESSION['uid']);
					$this->smarty->assign('userInfo', $userInfo);
					$this->smarty->display('event.tpl');
				} else {
					$this->smarty->display('event_private.tpl');
				}
				return;
			}
			
			if (intval($eventInfo['is_public']) == 1) {
				$this->smarty->display('event_guest.tpl');
			} else {
				$this->smarty->display('event_guest_private.tpl');
			}
			return;
		}
		
		// User public profile page
		if (preg_match("/user\/\d+/", $requestUri) > 0) {
			$userId = explode('/', $requestUri);
			$userId = $userId[sizeof($userId)-1];
			
			$this->assignCPEvents($userId);
			if (isset($_SESSION['uid'])) {
				$this->smarty->display('user_public_profile.tpl');
				return;
			}
			$this->smarty->display('user_public_profile_guest.tpl');
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
			case '/home':
				$this->smarty->assign('eventTitle', $_REQUEST['eventTitle']);
				$this->smarty->assign('maxEventId', $this->dbCon->getMaxEventId());
				$this->smarty->display('create_event_home.tpl');
				break;
			case '/cp/event/create':
				$this->smarty->display('create_event_cp.tpl');
				break;
			case '/event/create':
				require_once('models/Event.class.php');
				
				$this->smarty->assign('eventTitle', $_REQUEST['eventTitle']);
				$this->smarty->assign('eventId', $this->dbCon->getMaxEventId());
				$this->smarty->assign('domain', CURHOST);
				$this->smarty->display('cp.tpl');
				break;
			case '/event/update':
				require_once('models/Event.class.php');
				$eventInfo = new Event($_SESSION['uid'],
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
															 $_REQUEST['type'],0,0);
				
				$this->checkGuests($eventInfo);
				
				if($_REQUEST['eventId']!=-1)
				{
					$eventInfo->eid = $_REQUEST['eventId'];
					$this->smarty->assign('id', $_REQUEST['eventId']);
					$_SESSION['eventId']=$_REQUEST['eventId'];
				}
				else
				{
					$this->smarty->assign('id', $_SESSION['eventId']);
					//$eventInfo['id']= $_SESSION['eventId'];
					$eventInfo->eid = $_SESSION['eventId'];
				}	
				//$_SESSION['eventId']=$_REQUEST['eventId'];
				//print_r($_SESSION);
				//die();
				//////////////////////////////////////////
				
			
		$addr=$eventInfo->address;
		$goal=$eventInfo->goal;
		$title=$eventInfo->title;
		$dt=$eventInfo->date;
		$ddt=$eventInfo->deadline;
		$description=$eventInfo->description;
		$aval=$this->validate_address($addr);
		$tval=$this->validate_title($title);
		$desc=$this->validate_desc($description);
		$gval=$this->validate_goal($goal);
		$dval=$this->validate_date($dt);
		$ddval=$this->validate_ddt($ddt,$dt);
		
			if($ddval==2)
						{
							$this->smarty->display('update_event_form_errors.tpl');
							return;
						}
			if($dval==2)
						{
							$this->smarty->display('update_event_form_errors.tpl');
							return;
						}
			if($aval==2)
						{
							$this->smarty->display('update_event_form_errors.tpl');
							return;
						}
			if($tval==2 || $tval==3)
					{
							$this->smarty->display('update_event_form_errors.tpl');
							return;
					}
			if($desc==2)
					{
							$this->smarty->display('update_event_form_errors.tpl');
							return;
					}	
			if($gval==2)
					{
							$this->smarty->display('update_event_form_errors.tpl');
							return;
					}
				
				
			$addrss=$eventInfo->address;
			$addr=$this->check_address($addrss);	
			$eventInfo->lat=$addr['lat'];
			$eventInfo->lng=$addr['lng'];		
				
				
				$eventInfo->time=date("H:i:s", strtotime($_REQUEST['time']));
				
				
				////////////////////////////////////////////
				//if($eventInfo->eid <=0)
				//$eventInfo->eid = $_SESSION['eventId'];
				//print_r($_SESSION);
				//die();
				$this->dbCon->updateEvent($eventInfo);
				$this->assignCPEvents($_SESSION['uid']);
				$this->smarty->display('cp_container.tpl');
				break;
			case '/event/submit':
			//die("here");
				require_once('models/Event.class.php');
				require_once('models/Location.class.php');
				$addr=$this->check_address($_REQUEST['address']);	
				$newEvent = new Event($_SESSION['uid'],
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
															$addr['lat'],
															$addr['lng']);

				$this->checkGuests($newEvent);
				$this->checkNewEvent($newEvent, false);
				
				break;
				
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
			case '/event/edit':
				$this->assignEditEventVars($_REQUEST['eventId']);
				$this->smarty->display('update_event_cp.tpl');
				break;
			case '/event/edit/save':
				$this->assignEditEventVars($_REQUEST['eventId']);
				$this->smarty->display('update_event_form.tpl');
				break;
			case '/event/edit/guest':
				$eventAttendees = $this->dbCon->getAttendeesByEvent($_REQUEST['eventId']);
				$eventInfo = $this->dbCon->getEventInfo($_REQUEST['eventId']);
	
				$this->smarty->assign('eventInfo', $eventInfo);
				$this->smarty->assign('prevPage', $_REQUEST['prevPage']);
				$this->smarty->display('event_invite_guest_update.tpl');
				break;
			case '/event/edit/guest/inviter':
				require_once('libs/OpenInviter/openinviter.php');
				$inviter = new OpenInviter();
				$oi_services = $inviter->getPlugins();
				
				if (isset($_REQUEST['oi_email']) && isset($_REQUEST['oi_pass'])) {
					$inviter->startPlugin($_REQUEST['oi_provider']);
					$internal = $inviter->getInternalError();
					if ($internal && $this->DEBUG) {
						print($internal);
					}
					$inviter->login($_REQUEST['oi_email'], $_REQUEST['oi_pass']);
					
					$_POST['oi_session_id'] = $inviter->plugin->getSessionID();
					$contactList = $inviter->getMyContacts();
					
					$this->smarty->assign('contactList', $contactList);
					$this->smarty->display('event_add_guest_import_contact_list.tpl');
				} else {
					$this->smarty->assign('provider', $_REQUEST['provider']);
					$this->smarty->display('event_add_guest_right.tpl');
				}
				break;
			case '/event/edit/guest/save':
				require_once('models/Event.class.php');
				require_once('models/EFMail.class.php');
				
				$eventInfoDB = $this->dbCon->getEventInfo($_REQUEST['eventId']);
				$eventInfo = new Event($_SESSION['uid'],
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
															 $eventInfoDB['gets'],0,0);
				$eventInfo->eid = $_REQUEST['eventId'];
				
				$this->checkGuests($eventInfo);
				
				$mailer = new EFMail();
				$mailer->sendEmail($eventInfo->guests, $eventInfo->eid, $eventInfo->title, $eventInfo->url);
				$this->dbCon->storeGuests($eventInfo->guests, $_REQUEST['eventId'], $_SESSION['uid']);
				break;
			case '/event/manage':
				$this->assignManageVars($_REQUEST['eventId']);
				$this->smarty->display('manage.tpl');
				break;
			case '/event/manage/before':
				$this->assignManageVars($_REQUEST['eventId']);
				$this->smarty->display('manage_event_form.tpl');
				break;
			case '/event/manage/on':
				$this->displayAttendeePage($_REQUEST['eventId']);
				break;
			case '/event/manage/email/save':
			
				$sqlDate = $this->dbCon->dateToSql($_REQUEST['reminderDate']);
				$dateTime = $sqlDate." ".$_REQUEST['reminderTime'].":00";
				$autoReminder = 0;
				if ($_REQUEST['autoReminder'] == 'true') {
					$autoReminder = 1;
				}
				
				$req['content']=$_REQUEST['reminderContent'];
				$req['subject']=$_REQUEST['reminderSubject'];
				$req['type']=$_REQUEST['type'];
				$req['date']=$_REQUEST['reminderDate'];
				$retval=$this->validateSaveEmail($req);
				if($retval!="Success")
				{
					die($retval);
				}
				
				$this->dbCon->saveEmail($_REQUEST['eventId'], 
															  $_REQUEST['reminderContent'],
																$dateTime,
																$_REQUEST['reminderSubject'],
																$_REQUEST['type'],
																$autoReminder);
				echo("Success");
				break;
			case '/event/manage/email/send':
				require_once('models/EFMail.class.php');
				$mailer = new EFMail();
				$eventInfo = $this->dbCon->getEventInfo($_REQUEST['eventId']);
				$attendees = $this->dbCon->getAttendeesByEvent($_REQUEST['eventId']);
				$req['content']=$_REQUEST['reminderContent'];
				$req['subject']=$_REQUEST['reminderSubject'];
				$req['type']=$_REQUEST['type'];
				$req['date']=$_REQUEST['reminderDate'];
				$retval=$this->validateSaveEmail($req);
				if($retval!="Success")
				{
					die($retval);
				}
				$mailer->sendAutomatedEmail($eventInfo, 
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
			case '/event/manage/after':
				require_once('models/EFCore.class.php');
				$efCore = new EFCore();
				
				$eventInfo = $this->dbCon->getEventInfo($_REQUEST['eventId']);
				
				$eventResult = $this->dbCon->getEventResult($_REQUEST['eventId']);
				
				$this->smarty->assign('eventInfo', $eventInfo);
				$this->smarty->assign('eventResult', $eventResult['guest_num']);
				$this->smarty->assign('trsvpVal', $efCore->computeTrueRSVP($_REQUEST['eventId']));
				
				$this->smarty->display('manage_event_after.tpl');
				break;
			case '/event/email':
				$eventReminder = $this->dbCon->getEventEmail($_REQUEST['eventId'], EMAIL_REMINDER_TYPE);
				$eventFollowup = $this->dbCon->getEventEmail($_REQUEST['eventId'], EMAIL_FOLLOWUP_TYPE);
				
				if ($eventReminder['is_activated'] == 1) {
					$eventReminder['isAuto'] = 'checked = "checked"';
				}
				if ($eventFollowup['is_activated'] == 1) {
					$eventFollowup['isAuto'] = 'checked = "checked"';
				}
				
				$this->smarty->assign('eventReminder', $eventReminder);
				$this->smarty->assign('eventFollowup', $eventFollowup);
				
				$this->smarty->display('manage_event_email.tpl');
				break;
			case '/event/checkin':
				$isAttend = 1;
				if ($_REQUEST['checkin'] == 'false') {
					$isAttend = 0;
				}
				$this->dbCon->checkInGuest($isAttend, $_REQUEST['guestId'], $_REQUEST['eventId']);
				break;
			case '/event/print':
				$this->displayAttendeePage($_REQUEST['eventId']);
				break;
			case '/user/fb/create':
				$userInfo = $this->dbCon->createNewUser($_REQUEST['fname'], 
														$_REQUEST['lname'], 
														$_REQUEST['email'], 
														$_REQUEST['phone'], 
														$_REQUEST['pass']);
				
				if (isset($_SESSION['newEvent'])) {	
					$newEvent = json_decode($_SESSION['newEvent'], true);
					$newEvent['organizer'] = $userInfo['id'];
				}
				
				$_SESSION['uid'] = $userInfo['id'];
				$this->checkNewEvent($newEvent, true);
				break;
			case '/user/image/upload':
				require_once('models/FileUploader.class.php');
				// list of valid extensions, ex. array("jpeg", "xml", "bmp")
				$allowedExtensions = array("jpg");
				
				// max file size in bytes
				$sizeLimit = 2 * 1024 * 1024;
				
				$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
				$result = $uploader->handleUpload('upload/user/', TRUE);
				// to pass data through iframe you will need to encode all html tags
				echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
				
				$this->dbCon->saveUserPic();
				break;
			case '/user/create':
				$req['fname']=$_REQUEST['fname'];
				$req['lname']=$_REQUEST['lname'];
				$req['email']=$_REQUEST['email'];
				$req['phone']=$_REQUEST['phone'];
				$req['pass']=$_REQUEST['pass'];
				$req['zip']=$_REQUEST['zipcode'];
				$retVal=$this->checkUserCreationForm($req);
				//die($retVal);
				if($retVal==2)
					{
					$this->smarty->display('login.tpl');
					return;
					
					}
				$userInfo = $this->dbCon->createNewUser($_REQUEST['fname'], 
																								$_REQUEST['lname'], 
																								$_REQUEST['email'], 
																								$_REQUEST['phone'], 
																								$_REQUEST['pass'],
																								$_REQUEST['zipcode']);
				
				if (isset($_SESSION['newEvent'])) {	
					$newEvent = json_decode($_SESSION['newEvent'], true);
					$newEvent['organizer'] = $userInfo['id'];
				}
				
				$_SESSION['uid'] = $userInfo['id'];
				$this->checkNewEvent($newEvent, true);
				break;
			case '/user/profile/update':
				$this->dbCon->updatePaypalEmail($_SESSION['uid'], $_REQUEST['paypal_email']);
				$this->assignUserProfile($_SESSION['uid']);
				
				$this->smarty->display('user_profile.tpl');
				break;
			case '/event/payment/submit':
				require_once('models/PaypalPreapproveReceipt.class.php');
				
				$paypalPreapprove = new PaypalPreapproveReceipt();
				$paypalPreapprove->preapprove();
				break;
			case '/event/payment/success':
				require_once('models/PaypalPreapproveDetails.class.php');

			  if ($this->dbCon->eventSignUp($_SESSION['uid'], $_SESSION['attend_event']['id']) &&
								isset($_SESSION['uid'])) {
					$paypalPreapprove = new PaypalPreapproveDetails();
					$paypalPreapprove->preapprove();
					$this->dbCon->preapprovePayment($_SESSION['uid'],
																					$_SESSION['attend_event']['id'], 
																					$paypalPreapprove->preapprovalKey, 
																					$paypalPreapprove->response->senderEmail);
									
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
			case '/login':
				if (!isset($_SESSION['uid'])) {
					if(isset($_REQUEST['email']) && isset($_REQUEST['pass']))
					{
						$userId = $this->dbCon->checkValidUser($_REQUEST['email'], $_REQUEST['pass']);
						if(!isset($userId))
							{
								echo("1"); //login failed
								break;
							}
					}
					if (isset($userId)) {
						$_SESSION['uid'] = $userId;
					}
					if (isset($_SESSION['newEvent']))  {
						$newEvent = json_decode($_SESSION['newEvent'], true);
						$newEvent['organizer'] = $userId;
						$this->checkNewEvent($newEvent, false);
						break;
					}
				    $this->smarty->display('login.tpl');
					//mm $this->checkNewEvent(NULL, false);
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
			case '/logout':
				session_unset();
				session_destroy();
				$newEvents = $this->dbCon->getNewEvents();

				$this->smarty->assign('newEvents', $newEvents);
				$this->smarty->display('home.tpl');
				break;
			default:
				$this->smarty->assign('requestUri', $requestUri);
				$this->smarty->display('error.tpl');
				break;
		}
	}
}