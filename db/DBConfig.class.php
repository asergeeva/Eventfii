<?php
/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
 
class DBConfig {
	public function __construct() {
		$this->openCon();
	}
	
	public function __destruct() {
		
	}
	
	public function openCon() {
		$dbLink = mysql_connect(DB_HOST, DB_USER, DB_PASS);
		mysql_select_db(DB_NAME);
		if (!$dbLink && DEBUG) {
			die('Could not connect: '.mysql_error());	
		}
		
		return $dbLink;
	}
	
	public function closeCon($dbLink) {
		mysql_close($dbLink);
	}
	
	/* function executeQuery
	 * Executes a query that has a result set
	 *
	 * @param $query | The query to be run on the db
	 * @return $results | Return valid results
	 * @return false | If invalid query
	 */
	public function executeQuery($query) {
		$dbLink = $this->openCon();
		$dbResult = mysql_query($query);
		if (!$dbResult && DEBUG) {
			print($query . "<br />");
			die('Invalid query: ' . mysql_error());
		}
		$resultArr = mysql_fetch_array($dbResult, MYSQL_ASSOC);
		mysql_free_result($dbResult);
		
		return $resultArr;
	}
	
	/* executeValidQuery
	 * Executes a query
	 *
	 * @param $query | The query to be run on the db
	 * @return $results | Return valid results
	 * @return false | If invalid query
	 */
	public function executeValidQuery($query) {
		$dbLink = $this->openCon();
		$dbResult = mysql_query($query);
		
		// Check if query is valid
		if ( ! $dbResult && DEBUG ) {
			$result = false;
		} else {
			$result = mysql_fetch_array($dbResult, MYSQL_ASSOC);
			mysql_free_result($dbResult);
		}
		
		return $result;
	}
	
	/* function executeUpdateQuery
	 * Executes an updates query. There is not result set.
	 *
	 * @param $query | The query to be run on the db
	 */
	public function executeUpdateQuery($query) {
		$dbLink = $this->openCon();
		$dbResult = mysql_query( $query );
		if (!$dbResult && DEBUG) {
			print($query . "<br />");
			die('Invalid query: ' . mysql_error());
		}
	}
	
	/* function getQueryResult
	 * Get a reference to the result of the query.
	 * Result reference could be post-processed.
 	 *
	 * @param $query | The query to be run on the db
	 */
	public function getQueryResult($query) {
		$dbLink = $this->openCon();
		$dbResult = mysql_query($query);
		if ( ! $dbResult && DEBUG ) {
			return 0;
		}
		return $dbResult;
	}
	
	/* function getMaxEventId
	 * Get the current maximum event ID. 
	 * Useful when trying to find latest event 
	 * added to the database.
 	 *
	 * @return Integer | the max event ID stored in the DB
	 */
	public function getMaxEventId() {
		$GET_MAX_EFID = "	SELECT	MAX(e.id) AS max_id 
							FROM 	ef_events e
							WHERE	e.organizer = " . $_SESSION['user']->id;
		
		$maxId = $this->executeQuery($GET_MAX_EFID);
		
		if ( is_null($maxId['max_id']) ) {
			return 1;
		}
		return $maxId['max_id'];
	}
	
	/**
	 * Get the next event ID
	 * @return   Integer  the next event ID
	 */
	public function getNextEventId() {
		$GET_MAX_EFID = "	SELECT	MAX(e.id) AS max_id 
							FROM 	ef_events e";
		
		$maxId = $this->executeQuery($GET_MAX_EFID);
		
		if ( is_null($maxId['max_id']) ) {
			return 1;
		}
		return intval($maxId['max_id']) + 1;
	}
	
	/**
	 * Get the next user ID
	 * @return   Integer  the next user ID
	 */
	public function getNextUserId() {
		$GET_MAX_UID = "	SELECT	MAX(u.id) AS max_id 
							FROM 	ef_users u";
		
		$maxId = $this->executeQuery($GET_MAX_UID);
		
		if ( is_null($maxId['max_id']) ) {
			return 1;
		}
		return intval($maxId['max_id']) + 1;
	}
	
	public function getMaxRecipientTokenId() {
		$GET_MAX_EFID = "SELECT MAX(e.id) AS max_id FROM ef_recipient_token e";
		$maxId = $this->executeQuery($GET_MAX_EFID);
		if (is_null($maxId['max_id'])) {
			return 1;
		}
		return $maxId['max_id'] + 1;
	}
	
	public function checkValidUser($email, $pass) {
		$CHECK_VALID_USER = "	SELECT	* 
								FROM 	ef_users
								WHERE 	email = '".$email."' 
								AND 	password = '".md5($pass)."'";
		$userInfo = $this->executeQuery($CHECK_VALID_USER);
		if (isset($userInfo['id'])) {
			return $userInfo['id'];
		}
		return NULL;
	}
	
	public function getUserInfo($uid) {
		$GET_USER_INFO = "	SELECT	* 
							FROM 	ef_users
							WHERE 	id = " . $uid;
		$userInfo = $this->executeValidQuery($GET_USER_INFO);
		return $userInfo;
	}
	
	public function getUserInfoByEmail( $email ) {
		$GET_USER_INFO = "	SELECT	* 
							FROM 	ef_users 
							WHERE 	email = '" . $email . "'";
		$userInfo = $this->executeQuery($GET_USER_INFO);
		return $userInfo;
	}
	
	public function getReferenceEmail($hashKey) {
		$GET_REF_EMAIL = "	SELECT	* 
							FROM 	ef_event_invites i 
							WHERE 	i.hash_key = '" . $hashKey . "'";
		$invitedEmail = $this->executeQuery($GET_REF_EMAIL);
		return $invitedEmail['email_to'];
	}
	
	public function isUserEmailExist($email) {
		if (isset($_SESSION['ref'])) {
			$email = $this->getReferenceEmail($_SESSION['ref']);
		}
		$GET_USER_EMAIL = "	SELECT	* 
							FROM 	ef_users e 
							WHERE 	e.email = '" . $email . "'";
		if ($this->getRowNum($GET_USER_EMAIL) == 0) {
			return false;
		}
		return true;
	}
	
	public function getUserContacts($uid) {
		$GET_CONTACT = "SELECT u.* FROM ef_addressbook a, ef_users u WHERE a.contact_id = u.id AND a.user_id <> u.id AND a.user_id = ".$uid;
		return $this->getQueryResultAssoc($GET_CONTACT);
	}
	
	public function saveUserPic($file)
	{
		$uid = $_SESSION['user']->id;
		$SAVE_USER_PIC = "	UPDATE 	ef_users
							SET 	pic='" . $file . "'
							WHERE	id=" . $_SESSION['user']->id;
		$this->executeUpdateQuery($SAVE_USER_PIC);
		$_SESSION['user']->pic = $file;
	}
	
	public function updateUserProfileDtls($email,$zip,$cell)
	{
		$uid = $_SESSION['user']->id;
		$UPDATE_USER_PROFILE = "UPDATE 	ef_users 
								SET		zip		= '$zip',
										email	= '$email',
										phone	= '$cell' 
								WHERE	id = $uid";
		$this->executeUpdateQuery($UPDATE_USER_PROFILE);
	}
	
	public function updateUserInfo( $fname, $lname, $email, $phone, $zip, $twitter, $notif_opt1 = 1, $notif_opt2 = 1, $notif_opt3 = 1 ) {
		$UPDATE_USER = "UPDATE	ef_users 
						SET 	fname 		= '" . mysql_real_escape_string($fname) . "', 
								lname 		= '" . mysql_real_escape_string($lname) . "',
								email 		= '".mysql_real_escape_string($email)."', 
								phone 		= '" . mysql_real_escape_string($phone) . "',
								zip 		= '" . mysql_real_escape_string($zip) . "',
								twitter 	= '" . mysql_real_escape_string($twitter) . "',
								about 		= 'I am " . mysql_real_escape_string($fname) . "',
								notif_opt1 	= " . $notif_opt1 . ",
								notif_opt2 	= " . $notif_opt2 . ",
								notif_opt3 	= " . $notif_opt3 . "
						WHERE	id = " . $_SESSION['user']->id;
		$this->executeUpdateQuery($UPDATE_USER);
	}
	
	private function checkNullOrValSql($val) {
		if (isset($val) && trim($val) != "") {
			return "'".mysql_real_escape_string($val)."'";
		}
		return "NULL";
	}
	
	public function createNewUser($fname = NULL, $lname = NULL, $email, $phone = NULL, $pass = NULL, $zip = NULL, 
								  $fbid = NULL, $access_token = NULL, $session_key = NULL) {

		// If Facebook session exists, use its data for the FB related variables
		if (isset($_SESSION['fb']) && ($email == $_SESSION['fb']->email)) {
			$fbid = $_SESSION['fb']->facebook;
			$access_token = $_SESSION['fb']->fb_access_token;
			$session_key = $_SESSION['fb']->fb_session_key;
			
			$fbFriends = EFCommon::$facebook->api('/me/friends', array('access_token' => $_SESSION['fb']->fb_access_token));
			$this->saveFBFriends($fbFriends['data'], $_SESSION['fb']->id);
		}
	
		// If the email hasn't yet been found in the system
		if ( ! $this->isUserEmailExist($email) ) {
			$CREATE_NEW_USER = "INSERT IGNORE INTO ef_users(fname, lname, email, phone, password, 
															zip, facebook, fb_access_token, fb_session_key, url_alias) 
								VALUES(".$this->checkNullOrValSql($fname).", 
									   ".$this->checkNullOrValSql($lname).", 
									   '".mysql_real_escape_string($email)."', 
									   ".$this->checkNullOrValSql($phone).", 
									   ".$this->checkNullOrValSql($pass).", 
									   ".$this->checkNullOrValSql($zip).",
									   ".$this->checkNullOrValSql($fbid).",
									   ".$this->checkNullOrValSql($access_token).",
									   ".$this->checkNullOrValSql($session_key).",
									   '".dechex(505 + $this->getNextUserId())."')";
			$this->executeUpdateQuery($CREATE_NEW_USER);
			return $this->getUserInfoByEmail($email);
			
		// Update the reference
		// The implementation is the same as without reference
		// Future: may be we want to give credits to the inviter
		} else if ( isset($_SESSION['ref']) ) {
			$refEmail = $this->getReferenceEmail($_SESSION['ref']);
			
			$UPDATE_USER = "UPDATE	ef_users SET
									fname = ".$this->checkNullOrValSql($fname).", 
									lname = ".$this->checkNullOrValSql($lname).",
									phone = ".$this->checkNullOrValSql($phone).",
									password = ".$this->checkNullOrValSql($pass).",
									zip = ".$this->checkNullOrValSql($zip).",
									facebook = ".$this->checkNullOrValSql($fbid).",
									fb_access_token = ".$this->checkNullOrValSql($access_token).",
									fb_session_key = ".$this->checkNullOrValSql($session_key)."
							WHERE	email = '" . $refEmail . "'";
											
			$email = $refEmail;
			$this->executeUpdateQuery($UPDATE_USER);
			return $this->getUserInfoByEmail($email);
		}
		
		return NULL;
	}
	
	public function facebookAdd($fbid) {
		$UPDATE_USER_FB = "UPDATE ef_users SET facebook = '".$fbid."' WHERE id = ".$_SESSION['user']->id;
		$this->executeUpdateQuery($UPDATE_USER_FB);
		
		$_SESSION['user']->facebook = $fbid;
	}
	
	/* facebookConnect
	 * Used to log in through facebook.
	 *
	 * NOTE: ACCOUNT WILL BE LOST IF FB USER CHANGES E-MAIL ADDRESS.
	 * FB ID SHOULD BE USED INSTEAD OF E-MAIL FOR WHERE CLAUSE
	 *
	 * @param $fname | First Name
	 * @param $lname | Last Name
	 * @param $email | Email Address
	 * @return $userInfo | Array containing user information 
	 */
	public function facebookConnect( $fname, $lname, $email, $fbid, $access_token, $session_key ) {
		if ( ! $this->isUserEmailExist($email) ) {
			// If the user is new, create their account
			$this->createNewUser( $fname, $lname, $email, NULL, NULL, NULL, $fbid, $access_token, $session_key );
		} else {
			// Check to see that current users info is up to date
			$UPDATE_USER = "	UPDATE	ef_users 
								SET 	fname = '" . mysql_real_escape_string($fname) . "',
										lname = '" . mysql_real_escape_string($lname) . "',
										facebook = '".mysql_real_escape_string($fbid)."',
										fb_access_token = '".mysql_real_escape_string($access_token)."',
										fb_session_key = '".mysql_real_escape_string($session_key)."' 
								WHERE	email = '" . mysql_real_escape_string($email) . "'";
			
			// The user must have already registered
			$this->executeUpdateQuery($UPDATE_USER);
			
			$_SESSION['user'] = new User($this->getUserInfoByEmail($email));
		}
		return $this->getUserInfoByEmail($email);
	}
	
	public function createUserToken($uid, $signature, $refundTokenId, $tokenId, $callerRef) {
		$CREATE_USER_TOKEN = "INSERT INTO ef_recipient_token (uid, token_id, refund_token_id, ref, sig)
														VALUES (".$uid.", '".$tokenId."', '".$refundTokenId."', '".$callerRef."', '".$signature."')";
		$this->executeUpdateQuery($CREATE_USER_TOKEN);
	}
	
	public function getUserTokenId($uid) {
		$GET_USER_TOKEN = "SELECT * FROM ef_recipient_token e WHERE e.uid = ".$uid;
		return $this->executeQuery($GET_USER_TOKEN);
	}
	
	public function getCurSignup($eid) {
		$GET_CUR_SIGNUP = "	SELECT COUNT(*) AS cur_signups
							FROM ef_attendance a, ef_events e 
							WHERE a.event_id = e.id AND a.event_id = " . $eid;
		
		$curSignUp = $this->executeQuery($GET_CUR_SIGNUP);
		return $curSignUp['cur_signups'];
	}
	
	public function emailExistsCheck($email) {
		$GET_EMAIL_ID = "SELECT email AS email_id FROM ef_users WHERE email = '$email'";
		$emailExists = $this->executeQuery($GET_EMAIL_ID);
		return $emailExists['email_id'];
	}
	
	
	public function dateToSql($date) {
		$dateElem = explode("/", $date);
		return $dateElem[2]."-".$dateElem[0]."-".$dateElem[1];
	}
	
	public function dateToRegular($date) {
		$dateElem = explode("-", $date);
		if ($dateElem[1] == "" || $dateElem[2] == "" || $dateElem[0] == "") {
			return "";
		}
		return $dateElem[1]."/".$dateElem[2]."/".$dateElem[0];
	}
	
	public function createNewEvent($newEvent) {
		$datetime = $this->dateToSql($newEvent->date) . " " . $newEvent->time;
		if ( strlen($newEvent->end_date) != 0 && strlen($newEvent->end_time) != 0 ) {
			$end_datetime = $this->dateToSql($newEvent->end_date) . " " . $newEvent->end_time;
		} else {
			$end_datetime = NULL;
		}
		$sqlDeadline = $this->dateToSql($newEvent->deadline);
		
		$twitter = $newEvent->twitter;
		if (is_numeric(strpos($newEvent->twitter, '#'))) {
			$twitter = substr($newEvent->twitter, strpos($newEvent->twitter, '#') + 1);
		}
		
		$CREATE_NEW_EVENT = "
			INSERT INTO ef_events (	
							created, 
							organizer, 
							title, 
							goal, 
							reach_goal,
							location_name,
							location_address, 
							event_datetime, 
							event_end_datetime,
							event_deadline, 
							description, 
							is_public, 
							type,
							location_lat,
							location_long,
							twitter,
							url_alias
						) 
			VALUES (	NOW(), 
						" . mysql_real_escape_string($newEvent->organizer->id) . ",
						'" . mysql_real_escape_string($newEvent->title) . "', 
						" . mysql_real_escape_string($newEvent->goal) . ",
						" . mysql_real_escape_string($newEvent->reach_goal). ",
						" . $this->checkNullOrValSql($newEvent->location) . ",
						'" . mysql_real_escape_string($newEvent->address) . "',
						'" . mysql_real_escape_string($datetime) . "',
						 " .$this->checkNullOrValSql($end_datetime).",
						'" . mysql_real_escape_string($sqlDeadline) . "',
						'" . mysql_real_escape_string($newEvent->description) . "',	
						" . $newEvent->is_public . ",
						" . $newEvent->type . ",
						" . mysql_real_escape_string($newEvent->location_lat) . ",
						" . mysql_real_escape_string($newEvent->location_long) . ",
						" . $this->checkNullOrValSql($twitter) . ",
						'" . dechex(403 + $this->getNextEventId()) . "'
			)";
		$this->executeUpdateQuery($CREATE_NEW_EVENT);
	}
	
	public function getLastEventCreatedBy($uid) {
		$GET_LAST_EVENT = "SELECT * FROM ef_events WHERE organizer = ".$uid." ORDER BY created DESC LIMIT 1";
		return new Event($this->executeQuery($GET_LAST_EVENT));
	}
	
	/**
	 * Check whether $uid is a valid host for $eid
	 * $eid - Event ID
	 * $uid - User ID
	 * @return true when it is a valid host, false otherwise
	 */
	public function checkValidHost($eid, $uid) {
		$CHECK_HOST = "SELECT * FROM ef_events WHERE id = ".$eid." AND organizer = ".$_SESSION['user']->id;
		if ($this->getRowNum($CHECK_HOST) == 0) {
			return false;
		}
		return true;
	}
	
	/**
	 * Delete the event with the ID on param
	 * $eid - Event ID
	 * @return true when successful, false otherwise
	 */
	public function deleteEvent($eid) {
		if ($this->checkValidHost($eid, $_SESSION['user']->id)) {
			$DELETE_EVENT = "UPDATE ef_events SET is_active = 0 WHERE id = ".$eid." AND organizer = ".$_SESSION['user']->id;
			$this->executeUpdateQuery($DELETE_EVENT);
			return true;
		}
		return false;
	}
	
	public function updateEvent($eventInfo) {
		$datetime = $this->dateToSql($eventInfo->date) . " " . date("H:i:s", strtotime($eventInfo->time));
		if ( strlen($eventInfo->end_date) != 0 && strlen($eventInfo->end_time) != 0 ) {
			$end_datetime = $this->dateToSql($eventInfo->end_date) . " " . $eventInfo->end_time;
		} else {
			$end_datetime = NULL;
		}
		
		$sqlDeadline = $this->dateToSql($eventInfo->deadline);
		
		$twitter = $eventInfo->twitter;
		if (is_numeric(strpos($eventInfo->twitter, '#'))) {
			$twitter = substr($eventInfo->twitter, strpos($eventInfo->twitter, '#') + 1);
		}
		
		$UPDATE_EVENT = "	UPDATE	ef_events e 
							SET		e.title = '".mysql_real_escape_string($eventInfo->title)."', 
									e.goal = ".mysql_real_escape_string($eventInfo->goal).",
									e.reach_goal = ".mysql_real_escape_string($eventInfo->reach_goal).",
									e.location_name = ".$this->checkNullOrValSql($eventInfo->location).", 
									e.location_address = '".mysql_real_escape_string($eventInfo->address)."', 
									e.event_datetime = '".mysql_real_escape_string($datetime)."', 
									e.event_end_datetime = ".$this->checkNullOrValSql($end_datetime).",  
									e.event_deadline = '".mysql_real_escape_string($sqlDeadline)."', 
									e.description = '".mysql_real_escape_string($eventInfo->description)."',
									e.is_public = ".mysql_real_escape_string($eventInfo->is_public).", 
									e.location_lat=".mysql_real_escape_string($eventInfo->location_lat).",
									e.location_long=".mysql_real_escape_string($eventInfo->location_long).",
									e.type = ".$eventInfo->type.",
									e.twitter = ".$this->checkNullOrValSql($twitter)." 
							WHERE	e.id = ".mysql_real_escape_string($eventInfo->eid);
		$this->executeUpdateQuery($UPDATE_EVENT);
	}
	
	/* Get the list of the query results multiple than one row */
	public function getQueryResultAssoc($sqlQuery) {
		$sqlResult = $this->getQueryResult($sqlQuery);
		$sqlRows = array();
		if ( $sqlResult != 0 ) {
			while ($row = mysql_fetch_array($sqlResult, MYSQL_ASSOC)) {
				array_push($sqlRows, $row);
			}
			mysql_free_result($sqlResult);
		}
		return $sqlRows;
	}
	
	public function getRowNum($sqlQuery) {
		$sqlResult = $this->getQueryResult($sqlQuery);
		return mysql_num_rows($sqlResult);
	}
	
	/***** CONTROL PANEL ASSIGN EVENTS ********/
	public function getEventByEO($uid) {	
		$GET_EVENTS = "	SELECT	* 
						FROM (
							SELECT	e.id, 
									TIMEDIFF( e.event_datetime, NOW() ) AS days_left,
									e.created, 
									e.title, 
									e.goal,
									e.reach_goal, 
									e.location_name,
									e.location_address, 
									e.event_datetime, 
									e.event_end_datetime,
									e.event_deadline, 
									e.description, 
									e.is_public,
									e.type,
									e.url_alias
							FROM	ef_events e 
							WHERE	e.organizer = " . $uid . " AND e.is_active = 1
						) el
						ORDER BY el.days_left ASC";
		return $this->getQueryResultAssoc($GET_EVENTS);
	}
	
	public function getEventAttendingByUid($uid) {
		$GET_EVENTS = "	SELECT	* 
						FROM (
								SELECT 	e.id, 
										DATEDIFF(e.event_datetime, CURDATE()) AS days_left,
										e.created, 
										e.title, 
										e.goal, 
										e.reach_goal,
										e.location_name,
										e.location_address, 
										e.event_datetime, 
										e.event_end_datetime,
										e.event_deadline, 
										e.description, 
										e.is_public,
										e.type,
										e.url_alias 
								FROM 	ef_attendance a, 
										ef_events e 
								WHERE 	a.event_id = e.id 
								AND 	a.user_id = " . $uid . " 
								AND 	a.confidence <> " . CONFOPT6 . " AND e.is_active = 1
						) el
						ORDER BY el.days_left ASC";
		return $this->getQueryResultAssoc($GET_EVENTS);
	}
	
	/***** USER PROFILE ASSIGN EVENTS ********/
	public function getEventByEOProfile($uid) {		
		$GET_EVENTS = "	SELECT	* 
						FROM (
							SELECT	e.id, 
									TIMEDIFF( e.event_datetime, NOW() ) AS days_left,
									e.created, 
									e.title, 
									e.goal,
									e.reach_goal, 
									e.location_name,
									e.location_address, 
									e.event_datetime, 
									e.event_end_datetime,
									e.event_deadline, 
									e.description, 
									e.is_public,
									e.type
							FROM	ef_events e 
							WHERE	e.organizer = " . $uid . " AND e.is_active = 1 AND e.is_public = 1
						) el
						ORDER BY el.days_left ASC";
		return $this->getQueryResultAssoc($GET_EVENTS);
	}
	
	public function getEventAttendingByUidProfile($uid) {
		$GET_EVENTS = "	SELECT	* 
						FROM (
								SELECT 	e.id, 
										DATEDIFF(e.event_datetime, CURDATE()) AS days_left,
										e.created, 
										e.title, 
										e.goal, 
										e.reach_goal,
										e.location_name,
										e.location_address, 
										e.event_datetime, 
										e.event_end_datetime,
										e.event_deadline, 
										e.description, 
										e.is_public,
										e.type 
								FROM 	ef_attendance a, 
										ef_events e 
								WHERE 	a.event_id = e.id 
								AND 	a.user_id = " . $uid . " 
								AND 	a.confidence <> " . CONFOPT6 . " AND e.is_active = 1 AND e.is_public = 1
						) el
						ORDER BY el.days_left ASC";
		return $this->getQueryResultAssoc($GET_EVENTS);
	}
	
	/* getEventInfo
	 * Grabs information about an event with the given
	 * event ID from the Database.
	 */
	public function getEventInfo($eid) {
		$GET_EVENT = "	SELECT	id, 
								DATEDIFF ( event_deadline, CURDATE() ) AS rsvp_days_left,
								DATEDIFF ( event_datetime, CURDATE() ) AS days_left,
								DATE_FORMAT(event_datetime, '%M %d, %Y') AS friendly_event_date,
								DATE_FORMAT(event_datetime, '%r') AS friendly_event_time,
								created,
								organizer, 
								title, 
								goal, 
								reach_goal,
								location_name,
								location_address, 
								event_datetime, 
								event_end_datetime,
								event_deadline, 
								description, 
								is_public, 
								type,
								location_lat,
								location_long,
								twitter
						FROM	ef_events
						WHERE	id = " . $eid;
		return $this->executeValidQuery( $GET_EVENT );
	}
	
	public function hasAttend($uid, $eid) {
		$HAS_ATTEND = "	SELECT	* 
						FROM	ef_attendance a 
						WHERE	a.event_id = " . $eid . " 
						AND		a.user_id = " . $uid . " AND a.confidence <> ".CONFELSE;
		if ($this->getRowNum($HAS_ATTEND) > 0) {
			return $this->executeValidQuery($HAS_ATTEND);
		}
		return NULL;
	}
	
	public function eventSignUp($uid, $event, $conf) {
		if ( ! $this->hasAttend($uid, $event->eid) ) {
			$SIGN_UP_EVENT = "	INSERT IGNORE INTO ef_attendance (event_id, user_id, confidence, rsvp_time) 
								VALUES(
									" . $event->eid . ", 
									" . $uid . ", 
									" . $conf . ",
									NOW()
								)";
			
			$this->executeUpdateQuery($SIGN_UP_EVENT);
			EFCommon::$mailer->sendAGuestHtmlEmailByEvent('thankyou_RSVP', $_SESSION['user'], $event, 'Thank you for RSVP to {Event name}');
		} else {
			$UPDATE_SIGN_UP = "	UPDATE 	ef_attendance 
								SET 	confidence = " . $conf . " 
								WHERE 	event_id = " . $event->eid . " 
								AND 	user_id = " . $uid;

			$this->executeUpdateQuery($UPDATE_SIGN_UP);
		}
	}
	
	public function eventWaitlist($uid, $event, $conf) {
		if ( ! $this->hasAttend($uid, $event->eid) ) {
			$SIGN_UP_WAITLIST_EVENT = "	INSERT INTO ef_waitinglist (event_id, user_id, confidence) 
										VALUES(
											" . $event->eid . ", 
											" . $uid . ", 
											" . $conf . ",
											NOW()
										)";
			
			$this->executeUpdateQuery($SIGN_UP_EVENT);
		} else {
			$UPDATE_WAITLIST = "	UPDATE 	ef_waitinglist 
									SET 	confidence = " . $conf . " 
									WHERE 	event_id = " . $event->eid . " 
									AND 	user_id = " . $uid;

			$this->executeUpdateQuery($UPDATE_SIGN_UP);
		}
	}
	
	public function preapprovePayment($uid, $eid, $pkey, $pemail) {
		$PREAPPROVE_PAYMENT = "INSERT INTO ef_event_preapprovals (uid, eid, pkey, pemail) 
		                          VALUES (".$uid.", '".$eid."', '".$pkey."', '".$pemail."')";
		$this->executeUpdateQuery($PREAPPROVE_PAYMENT);
	}
	
	public function getPaypalEmail($uid) {
		$GET_PAYPAL_EMAIL = "SELECT * FROM ef_paypal_accounts e WHERE e.uid = ".$uid;
		return $this->executeQuery($GET_PAYPAL_EMAIL);
	}
	
	public function updateuserStatus($status) {
		$uid = $_SESSION['user']->id;
		$UPDATE_USER_STATUS = "	UPDATE	ef_users e 
								SET 	e.about = '" . $status . "' 
								WHERE 	e.id = " . $uid;
		$this->executeUpdateQuery($UPDATE_USER_STATUS);
	}
	
	
	public function updatePaypalEmail($uid, $pemail) {
		if ($this->getPaypalEmail($uid) > 0) {
			$UPDATE_PAYPAL_EMAIL = "UPDATE ef_paypal_accounts e SET e.pemail = '".$pemail."' WHERE e.uid = ".$uid;
			$this->executeUpdateQuery($UPDATE_PAYPAL_EMAIL);
		} else {
			$INSERT_PAYPAL_EMAIL = "INSERT INTO ef_paypal_accounts (uid, pemail) VALUES (".$uid.", '".$pemail."')";
			$this->executeUpdateQuery($INSERT_PAYPAL_EMAIL);
		}
	}
	
	public function storeContacts($contactEmails, $uid) {
		for ($i = 0; $i < sizeof($contactEmails); ++$i) {
			if (!$this->isUserEmailExist($contactEmails[$i])) {
				$STORE_EMAIL_USERS = "INSERT INTO ef_users (email, referrer)
											VALUES ('" . $contactEmails[$i] . "', " . $uid . ")";
				$this->executeUpdateQuery($STORE_EMAIL_USERS);
			}
			$new_user = new User($contactEmails[$i]);
			$STORE_CONTACT = "INSERT IGNORE INTO ef_addressbook (user_id, contact_id, contact_email) 
							  VALUES (" . $uid . ", " . $new_user->id . ", '".$new_user->email."')";
			$this->executeUpdateQuery($STORE_CONTACT);
		}
	}
	
	/**
	 * $guestEmail Array    string of email addresses
	 * $eid        Integer  Event ID
	 * $referrer   Integer  User ID
	 */
	public function storeGuests($guestEmails, $eid, $referrer) {
		for ($i = 0; $i < sizeof($guestEmails); ++$i) {
			if (!$this->isUserEmailExist($guestEmails[$i])) {
				$STORE_GUEST_EMAIL_USERS = "INSERT INTO ef_users (email, referrer)
												VALUES ('" . $guestEmails[$i] . "', " . $referrer . ")";
				$this->executeUpdateQuery($STORE_GUEST_EMAIL_USERS);
			}
			$new_user = new User($guestEmails[$i]);
			$STORE_GUEST_EMAIL_ATTENDEES = "INSERT IGNORE INTO ef_attendance (event_id, user_id) VALUES (" . $eid . ", " . $new_user->id . ")";
			$this->executeUpdateQuery($STORE_GUEST_EMAIL_ATTENDEES);
		}
	}
	
	// DEPRECATED: Paypal
	
	public function getAttendeesByEvent($eid) {
		$GET_ATTENDEES = "	SELECT	* 
							FROM 	ef_attendance a, 
									ef_users u 
							WHERE 	a.user_id = u.id 
							AND 	a.event_id = " . $eid;
		return $this->getQueryResultAssoc($GET_ATTENDEES);
	}
	
	/**
	 * Get all of the guests who already RSVP'ed
	 * $eid   Integer   the ID of the event
	 */
	public function getConfirmedGuests($eid) {
		$GET_ATTENDEES = "	SELECT	* 
							FROM 	ef_attendance a, 
									ef_users u 
							WHERE 	a.user_id = u.id 
							AND 	a.event_id = " . $eid . " AND a.confidence <> ".CONFELSE;
		return $this->getQueryResultAssoc($GET_ATTENDEES);
	}
	
	/**
	 * Get all of the guests who already RSVP'ed, 
	 * with the specified confidence value
	 * $eid   Integer    the ID of the event
	 * $conf  Integer    the confidence value
	 */
	public function getConfirmedGuestsByConfidence($eid, $conf) {
		$GET_ATTENDEES = "	SELECT	u.* 
							FROM 	ef_attendance a, 
									ef_users u 
							WHERE 	a.user_id = u.id 
							AND 	a.event_id = " . $eid . " AND a.confidence = ".$conf;
		return $this->getQueryResultAssoc($GET_ATTENDEES);
	}
	
	public function getNumAttendeesByConfidence($eid, $conf) {
		$GET_ATTENDEES = "	SELECT 	COUNT(*) AS guest_num 
							FROM 	ef_attendance a, ef_users u 
							WHERE 	a.user_id = u.id 
							AND 	a.confidence = " . $conf . " 
							AND 	a.event_id = " . $eid;
		return $this->executeQuery($GET_ATTENDEES);
	}

	public function getNumAttendeesNoResponse($eid) {
		$GET_ATTENDEES = "SELECT COUNT(*) AS guest_num FROM ef_attendance a, ef_users u 
												WHERE a.user_id = u.id AND a.confidence IS NULL AND a.event_id = ".$eid;
		return $this->executeQuery($GET_ATTENDEES);
	}
	
	// Event Result: the total number of attendees that got checked
	// i.e. It is the number of guests who actually attended the event
	public function getEventResult($eid) {
		$GET_ATTENDEES = "SELECT COUNT(*) AS guest_num FROM ef_attendance a, ef_users u 
												WHERE a.user_id = u.id AND a.event_id = ".$eid." AND a.is_attending = 1";
		return $this->executeQuery($GET_ATTENDEES);
	}
	
	public function checkInGuest($isAttend, $uid, $eid) {
		$CHECKIN_GUEST = "UPDATE ef_attendance a SET a.is_attending = ".$isAttend." 
												WHERE a.user_id = ".$uid." AND a.event_id = ".$eid."";
		$this->executeUpdateQuery($CHECKIN_GUEST);
	}
	
	/**
	 * Save the message with the type specified
	 * $eid              Integer   event ID
	 * $msg              String    the content of the message
	 * $deliveryDateTime String    the datetime in string
	 * $subject          String    the subject of the reminder
	 * $autoReminder     Integer   whether this is message should be automatically sent at the later time
	 * $group            Integer   the recipient group
	 * $isFollowup       Integer   between 1 or 0, 1 if it is a followup email
	 */
	public function saveEmail($eid, $msg, $deliveryDateTime, $subject, $autoReminder, $group, $isFollowup) {
		$reminderType = ($isFollowup == 0) ? EMAIL_REMINDER_TYPE : EMAIL_FOLLOWUP_TYPE;
		if ($this->hasEventMessage($eid, $reminderType)) {
			$UPDATE_REMINDER = "
			UPDATE	ef_event_messages 
			SET
			  created = NOW(), 
			  subject = '" . mysql_real_escape_string($subject) . "', 
			  message = '" . mysql_real_escape_string($msg) . "', 
			  delivery_time = '" . mysql_real_escape_string($deliveryDateTime) . "', 
			  is_activated = ".$autoReminder.",
			  recipient_group = ".mysql_real_escape_string($group)."
			WHERE
			  event_id = ".$eid." AND
			  type = ".$reminderType;
			$this->executeUpdateQuery($UPDATE_REMINDER);

		} else {
			$SAVE_REMINDER = "
			INSERT INTO	ef_event_messages 
						(
							created, 
							subject, 
							message, 
							delivery_time, 
							event_id, 
							type, 
							is_activated,
							recipient_group
						) 
			VALUES		(
							NOW(), 
							'" . mysql_real_escape_string($subject) . "', 
							'" . mysql_real_escape_string($msg) . "', 
							'" . mysql_real_escape_string($deliveryDateTime) . "', 
							" . $eid.",
							" . $reminderType.",
							" . $autoReminder.",
							".mysql_real_escape_string($group)."
						)";
			$this->executeUpdateQuery($SAVE_REMINDER);
		}
	}
	
	public function saveText($eid, $msg, $deliveryDateTime, $autoReminder, $group) {
		if ($this->hasEventMessage($eid, SMS_REMINDER_TYPE)) {
			$UPDATE_REMINDER = "
			UPDATE	ef_event_messages 
			SET
			  created = NOW(), 
			  subject =  NULL, 
			  message = '" . mysql_real_escape_string($msg) . "', 
			  delivery_time = '" . mysql_real_escape_string($deliveryDateTime) . "', 
			  is_activated = ".$autoReminder.",
			  recipient_group = ".mysql_real_escape_string($group)."
			WHERE
			  event_id = ".$eid." AND
			  type = ".SMS_REMINDER_TYPE;
			$this->executeUpdateQuery($UPDATE_REMINDER);

		} else {
			$SAVE_REMINDER = "
			INSERT INTO	ef_event_messages 
						(
							created, 
							message, 
							delivery_time, 
							event_id, 
							type, 
							is_activated,
							recipient_group
						) 
			VALUES		(
							NOW(),
							'" . mysql_real_escape_string($msg) . "', 
							'" . mysql_real_escape_string($deliveryDateTime) . "', 
							" . $eid.",
							" . SMS_REMINDER_TYPE.",
							" . $autoReminder.",
							".mysql_real_escape_string($group)."
						)";
			$this->executeUpdateQuery($SAVE_REMINDER);
		}
	}
	
	/**
	 * Check whether there is already a message for this event
	 * $eid    Integer   the event ID
	 * $type   Integer   the message type:
	 *						define('EMAIL_REMINDER_TYPE', 1);
	 *						define('SMS_REMINDER_TYPE', 2);
	 * @return Boolean true when there is already a message. False otherwise.
	 */
	public function hasEventMessage($eid, $type) {
		$GET_EVENT_EMAIL = "SELECT	m.subject, 
									m.message, 
									DATE_FORMAT(m.delivery_time, '%m/%d/%Y %r') AS datetime, 
									m.event_id 
							FROM 	ef_event_messages m 
							WHERE 	m.event_id = " . $eid . " 
							AND 	m.type = " . $type;
		if ($this->getRowNum($GET_EVENT_EMAIL) == 0) {
			return false;
		}
		return true;
	}
	
	/**
	 * Get the message that was saved
	 * $eid   Integer the event ID
	 * $type  Integer the message type:
	 *						define('EMAIL_REMINDER_TYPE', 1);
	 *						define('SMS_REMINDER_TYPE', 2);
	 */
	public function getEventEmail($eid, $type) {
		$GET_EVENT_EMAIL = "SELECT	m.subject, 
									m.message, 
									DATE_FORMAT(m.delivery_time, '%m/%d/%Y %r') AS datetime, 
									m.event_id,
									m.recipient_group 
							FROM 	ef_event_messages m 
							WHERE 	m.event_id = " . $eid . " 
							AND 	m.type = " . $type . " 
							ORDER BY m.created DESC 
							LIMIT 1";
		return $this->executeQuery($GET_EVENT_EMAIL);
	}
	
	public function setAutosend($eid, $type, $isActivated) {
		$SET_AUTOSEND = "UPDATE ef_event_messages m SET m.is_activated = ".$isActivated.
											" WHERE m.event_id = ".$eid." AND m.type = ".$type;
		$this->executeUpdateQuery($SET_AUTOSEND);
	}
	
	public function getNumGuests($eid) {
		$GET_NUM_GUESTS = "SELECT COUNT(*) AS num_guests FROM ef_users u, ef_attendance a WHERE u.id = a.user_id AND a.event_id = ".$eid;
		return $this->executeQuery($GET_NUM_GUESTS);
	}
	
	public function resetPassword($oldPass, $newPass, $confPass) {
		$userPass = $this->getUserPass();
		if ($oldPass == $userPass['password'] && $newPass == $confPass) {
			$RESET_PASSWORD = "	UPDATE 	ef_users 
								SET 	password = '" . $newPass . "' 
								WHERE 	id = " . $_SESSION['user']->id;
			$this->executeUpdateQuery($RESET_PASSWORD);
			return true;
		}
		return false;
	}
	
	public function getUserPass() {
		$GET_USER_PASS = "SELECT password FROM ef_users WHERE id = " . $_SESSION['user']->id;
		return $this->executeQuery($GET_USER_PASS);
	}
	
	public function requestPasswordReset($hash_key, $email) {
		$CHECK_VALID_EMAIL = "SELECT * FROM ef_users u WHERE u.email = '".$email."'";
		if ($this->getRowNum($CHECK_VALID_EMAIL) == 0) {
			return NULL;
		}
		$REQUEST_PASS_RESET = "INSERT INTO ef_password_reset (hash_key, email) VALUES ('".$hash_key."', '".$email."')";
		$this->executeUpdateQuery($REQUEST_PASS_RESET);
		return new User($this->getUserInfoByEmail($email));;
	}
	
	public function isValidPassResetRequest($hash_key) {
		$IS_VALID_PASS_RESET_REQUEST = "SELECT * FROM ef_password_reset r WHERE r.hash_key = '".$hash_key."'";
		if ($this->getRowNum($IS_VALID_PASS_RESET_REQUEST) == 0) {
			return false;
		}
		return true;
	}
	
	public function resetPasswordByEmail($newPass, $hash_key) {
		$GET_RESET_EMAIL = "SELECT * FROM ef_password_reset r WHERE r.hash_key = '".$hash_key."'";
		$refInfo = $this->executeQuery($GET_RESET_EMAIL);
		
		$UPDATE_USER_PASS = "UPDATE ef_users u SET u.password = '".md5($newPass)."' WHERE u.email = '".$refInfo['email']."'";
		$this->executeUpdateQuery($UPDATE_USER_PASS);
		
		$UPDATE_RESET_TIME = "UPDATE ef_password_reset r SET r.treset = NOW() WHERE r.hash_key = '".$hash_key."'";
		$this->executeUpdateQuery($UPDATE_RESET_TIME);
	}
	
	public function isInvited($uid, $eid) {
		$IS_INVITED = "	SELECT	* 
						FROM		ef_attendance a 
						WHERE	a.user_id = " .	$uid . " 
						AND		a.event_id = " . $eid;
		if ($this->getRowNum($IS_INVITED) == 0) {
			return false;
		}
		return true;
	}

	public function getInviteReference($ref, $email) {
		$GET_REF_EMAIL = "SELECT * FROM ef_event_invites i WHERE i.hash_key = '".$ref."' AND i.email_to = '".$email."'";
		
		return $this->executeQuery($GET_REF_EMAIL);
	}
	
	/**
	 * $uid - User id of the logged in user
	 * $fid - User id of the viewed user profile
	 * Determine whether $uid should be following $fid
	 */
	public function followUser($uid, $fid) {
		$IS_FOLLOW = "SELECT * FROM ef_friendship WHERE uid = ".$uid." AND fid = ".$fid;
		if ($this->getRowNum($IS_FOLLOW) == 0) { 
			$FOLLOW_USER = "INSERT INTO ef_friendship (uid, fid) VALUES (".$uid.", ".$fid.")";
			$this->executeUpdateQuery($FOLLOW_USER);
			
			return 1;
		} else {
			$followInfo = $this->executeQuery($IS_FOLLOW);
			$isFollow = (intval($followInfo['is_follow']) == 1) ? 0 : 1;
			$UPDATE_FOLLOW = "UPDATE ef_friendship SET is_follow = ".$isFollow." WHERE uid = ".$uid." AND fid = ".$fid;
			$this->executeUpdateQuery($UPDATE_FOLLOW);
			
			return $isFollow;
		}
	}
	
	/**
	 * $uid - User id of the logged in user
	 * $fid - User id of the viewed user profile
	 * Get the information whether $uid is currently following $fid
	 */
	public function isFollowing($uid, $fid) {
		$IS_FOLLOW = "SELECT * FROM ef_friendship WHERE uid = ".$uid." AND fid = ".$fid. " AND is_follow = 1";
		if ($this->getRowNum($IS_FOLLOW) == 0) { 
			return 0;
		}
		return 1;
	}
	
	/**
	 * Store the email of NOT YET's (index page)
	 * @param $email   String    email address
	 */
	public function storeNotyet($email) {
		$STORE_NOTYET = "INSERT INTO ef_notyet (email) VALUES ('".mysql_real_escape_string($email)."')";
		$this->executeUpdateQuery($STORE_NOTYET);
	}
	
	public function getEmailSuggestion($keyword) {
		$GET_SUGGESTION = "SELECT * FROM ef_addressbook a WHERE a.email LIKE '%".$keyword."%' AND a.user_id = ".$_SESSION['user']->id;
	}
	
	/**
	 * Store the user's Facebook friends
	 * @param $fbFriends   Array  of facebook friends
	 * @param $userId      Integer the user ID
	 */
	public function saveFBFriends($fbFriends, $userId) {
		for ($i = 0; $i < sizeof($fbFriends); ++$i) {
			if (is_array($fbFriends[$i])) {
				$STORE_FB_FRIEND = "INSERT IGNORE INTO fb_friends (user_id, fb_name, fb_id) 
										VALUES (".$userId.", '".mysql_real_escape_string($fbFriends[$i]['name'])."', '".$fbFriends[$i]['id']."')";
				$this->executeUpdateQuery($STORE_FB_FRIEND);
			} else {
				$STORE_FB_FRIEND = "INSERT IGNORE INTO fb_friends (user_id, fb_name, fb_id) 
										VALUES (".$userId.", '".mysql_real_escape_string($fbFriends[$i]->name)."', '".$fbFriends[$i]->id."')";
				$this->executeUpdateQuery($STORE_FB_FRIEND);
			}
		}
	}
	
	/**
	 * Given the URI alias, get the event row in the DB
	 * @param $url_alias   String   the alias URI of an event
	 */
	public function getEventByURIAlias($url_alias) {
		$GET_EVENT_URI_ALIAS = "SELECT * FROM ef_events WHERE url_alias = '".mysql_real_escape_string($url_alias)."'";
		return $this->executeQuery($GET_EVENT_URI_ALIAS);
	}
	
	/**
	 * Given the URI alias, get the user row in the DB
	 * @param $url_alias   String   the alias URI of an user
	 */
	public function getUserByURIAlias($url_alias) {
		$GET_USER_URI_ALIAS = "SELECT * FROM ef_users WHERE url_alias = '".mysql_real_escape_string($url_alias)."'";
		return $this->executeQuery($GET_USER_URI_ALIAS);
	}
}