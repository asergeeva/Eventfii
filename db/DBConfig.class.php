<?php
/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
 
class DBConfig {
	private $DB_HOST = "127.0.0.1:3306";
	private $DB_USER = "glaksmono";
	private $DB_PASS = "12345";
	
	private $DB_NAME = "eventfii";
	
	private $DEBUG = true;
	
	public function __construct() {
		$this->openCon();
	}
	
	public function __destruct() {
		
	}
	
	public function openCon() {
		$dbLink = mysql_connect($this->DB_HOST, $this->DB_USER, $this->DB_PASS);
		mysql_select_db($this->DB_NAME);
		if (!$dbLink && $this->DEBUG) {
			die('Could not connect: '.mysql_error());	
		}
		
		return $dbLink;
	}
	
	public function closeCon($dbLink) {
		mysql_close($dbLink);
	}
	
	public function executeQuery($query) {
		$dbLink = $this->openCon();
		$dbResult = mysql_query($query);
		if (!$dbResult && $this->DEBUG) {
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
		if ( ! $dbResult && $this->DEBUG ) {
			$result = false;
		} else {
			$result = mysql_fetch_array($dbResult, MYSQL_ASSOC);
			mysql_free_result($dbResult);
		}
		
		return $result;
	}
	
	public function executeUpdateQuery($query) {
		$dbLink = $this->openCon();
		$dbResult = mysql_query($query);
		if (!$dbResult && $this->DEBUG) {
			print($query . "<br />");
			die('Invalid query: ' . mysql_error());
		}
	}
	
	public function getQueryResult($query) {
		$dbLink = $this->openCon();
		$dbResult = mysql_query($query);
		if ( ! $dbResult && $this->DEBUG ) {
			return 0;
		}
		return $dbResult;
	}
	
	public function getMaxEventId() {
		$GET_MAX_EFID = "	SELECT	MAX(e.id) AS max_id 
							FROM 	ef_events e
							WHERE	e.organizer = " . $_SESSION['uid'];
		
		$maxId = $this->executeQuery($GET_MAX_EFID);
		
		if ( is_null($maxId['max_id']) ) {
			return 1;
		}
		return $maxId['max_id'];
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
		$GET_USER_INFO = "SELECT * FROM ef_users e WHERE e.id = " . $uid;
		$userInfo = $this->executeValidQuery($GET_USER_INFO);
		return $userInfo;
	}
	
	public function getUserInfoByEmail( $email ) {
		$GET_USER_INFO = "	SELECT	* 
							FROM 	ef_users 
							WHERE 	email = '".$email."'";
		$userInfo = $this->executeQuery($GET_USER_INFO);
		return $userInfo;
	}
	
	public function getReferenceEmail($hashKey) {
		$GET_REF_EMAIL = "SELECT * FROM ef_event_invites i WHERE i.hash_key = '".$hashKey."'";
		$invitedEmail = $this->executeQuery($GET_REF_EMAIL);
		return $invitedEmail['email_to'];
	}
	
	public function isUserEmailExist($email) {
		if (isset($_SESSION['ref'])) {
			$email = $this->getReferenceEmail($_SESSION['ref']);
		}
		$GET_USER_EMAIL = "SELECT * FROM ef_users e WHERE e.email = '".$email."'";
		if ($this->getRowNum($GET_USER_EMAIL) == 0) {
			return false;
		}
		return true;
	}
	
	public function saveUserPic()
	{
		$uid=$_SESSION['uid'];
		$SAVE_USER_PIC="update ef_users set pic='$uid.jpg' where id=$uid";
		$this->executeUpdateQuery($SAVE_USER_PIC);
	}
	
	public function updateUserProfileDtls($email,$zip,$cell)
	{
		$uid=$_SESSION['uid'];
		$UPDATE_USER_PROFILE="update ef_users set zip='$zip',email='$email',phone='$cell' where id=$uid";
		$this->executeUpdateQuery($UPDATE_USER_PROFILE);
	}
	
	public function getUserPic($uid) {
		//$uid=$_SESSION['uid'];
		$GET_USER_PIC="select pic from ef_users where id=$uid";
		$usrPic=$this->executeQuery($GET_USER_PIC);
		return $usrPic['pic'];
	}
	
	public function updateUserInfo($fname, $lname, $email, $phone, $zip, $twitter, 
																 $about, $notif_opt1 = 1, $notif_opt2 = 1, $notif_opt3 = 1) {
		$UPDATE_USER = "UPDATE	ef_users SET 
								fname = '" . mysql_real_escape_string($fname) . "', 
								lname = '" . mysql_real_escape_string($lname) . "',
								email = '".mysql_real_escape_string($email)."', 
								phone = '" . mysql_real_escape_string($phone) . "',
								zip = '" . mysql_real_escape_string($zip) . "',
								twitter = '" . mysql_real_escape_string($twitter) . "',
								about = 'I am " . mysql_real_escape_string($fname) . "',
								notif_opt1 = ".$notif_opt1.",
								notif_opt2 = ".$notif_opt2.",
								notif_opt3 = ".$notif_opt3."
						WHERE	id = '" . $_SESSION['uid'] . "'";
		$this->executeUpdateQuery($UPDATE_USER);
	}
	
	public function createNewUser($fname, $lname, $email, $phone, $pass, $zip) {
		if ( ! $this->isUserEmailExist($email) ) {
			if ( isset( $pass ) ) {
				$pass = "'".mysql_real_escape_string($pass)."'";
			} else {
				// Facebook maintained the password of the user we store them as a NULL
				$pass = "NULL";
			}
			if (!isset($zip) || strlen($zip)<=0)
			{
				$zip="NULL";
			}
			$CREATE_NEW_USER = "INSERT INTO ef_users(fname, lname, email, phone, password, about, zip) 
								VALUES(		'" . mysql_real_escape_string($fname) . "', 
											'" . mysql_real_escape_string($lname) . "', 
											'" . mysql_real_escape_string($email) . "', 
											'" . mysql_real_escape_string($phone) . "', 
											" . $pass . ", 
											'', 
											" . mysql_real_escape_string($zip) . ")";
			$this->executeUpdateQuery($CREATE_NEW_USER);
		} else if ( isset($_SESSION['ref']) ) {
			$refEmail = $this->getReferenceEmail($_SESSION['ref']);
			$userInfo = $this->getUserInfoByEmail($refEmail);
			
			$emailAttr = 'email1';
			// check if the email is the same as the previous one
			if ($userInfo['email1'] == $email) {
				$emailAttr = 'email1';
			} else if ($userInfo['email2'] == $email) {
				$emailAttr = 'email2';
			} else if ($userInfo['email3'] == $email) {
				$emailAttr = 'email3';
			} else if ($userInfo['email4'] == $email) {
				$emailAttr = 'email4';
			} else if ($userInfo['email5'] == $email) {
				$emailAttr = 'email5';
			// Check which attribute in the DB that is NULL
			} else if ($userInfo['email2'] == '') {
				$emailAttr = 'email2';
			} else if ($userInfo['email3'] == '') {
				$emailAttr = 'email3';
			} else if ($userInfo['email4'] == '') {
				$emailAttr = 'email4';
			} else if ($userInfo['email5'] == '') {
				$emailAttr = 'email5';
			}
			
			$UPDATE_USER = "UPDATE	ef_users SET
									fname = '" . mysql_real_escape_string($fname) . "', 
									lname = '" . mysql_real_escape_string($lname) . "',
									" . $emailAttr . " = '" . mysql_real_escape_string($email) . "',
									about = ''
							WHERE	email = '" . $refEmail . "'";
											
			$email = $refEmail;
			$this->executeUpdateQuery($UPDATE_USER);
		} 
		
		return $this->getUserInfoByEmail($email);
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
	public function facebookConnect( $fname, $lname, $email ) {
		if ( ! $this->isUserEmailExist($email) ) {
			// If the user is new, create their account
			$this->createNewUser( $fname, $lname, $email, '', NULL, '' );
		} else {
			// Check to see that current users info is up to date
			$UPDATE_USER = "	UPDATE	ef_users 
								SET 	fname = '" . mysql_real_escape_string($fname) . "',
										lname = '" . mysql_real_escape_string($lname) . "' 
								WHERE	email = '" . mysql_real_escape_string($email) . "'";
			$this->executeUpdateQuery($UPDATE_USER);
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
		return $dateElem[2]."-".$dateElem["0"]."-".$dateElem[1];
	}
	
	public function dateToRegular($date) {
		$dateElem = explode("-", $date);
		return $dateElem[1]."/".$dateElem["2"]."/".$dateElem[0];
	}
	
	public function createNewEvent($newEvent) {
		$datetime = $this->dateToSql($newEvent->date) . " " . $newEvent->time;
		$sqlDeadline = $this->dateToSql($newEvent->deadline);
		
		$CREATE_NEW_EVENT = "
			INSERT INTO ef_events (	created, 
									organizer, 
									title, 
									url, 
									goal, 
									location_address, 
									event_datetime, 
									event_deadline, 
									description, 
									is_public, 
									type,
									location_lat,
									location_long) 
			VALUES (		NOW(), 
						'" . mysql_real_escape_string($newEvent->organizer) . "',
						'" . mysql_real_escape_string($newEvent->title) . "', 
						'" . mysql_real_escape_string($newEvent->url) . "', 
						" . mysql_real_escape_string($newEvent->goal) . ",
						'" . mysql_real_escape_string($newEvent->address) . "',
						'" . mysql_real_escape_string($datetime) . "',
						'" . mysql_real_escape_string($sqlDeadline) . "',
						'" . mysql_real_escape_string($newEvent->description) . "',	
						" . $newEvent->is_public . ",
						" . $newEvent->type . ",
						" . mysql_real_escape_string($newEvent->location_lat) . ",
						" . mysql_real_escape_string($newEvent->location_long) . ")
		";
		
		$this->executeUpdateQuery($CREATE_NEW_EVENT);
	}
	
	public function updateEvent($eventInfo) {
		$datetime = $this->dateToSql($eventInfo->date)." ".$eventInfo->time;
		$sqlDeadline = $this->dateToSql($eventInfo->deadline);
		
		$UPDATE_EVENT = "	UPDATE	ef_events e 
							SET		e.title = '".mysql_real_escape_string($eventInfo->title)."', 
									e.goal = ".mysql_real_escape_string($eventInfo->goal).",
									e.location_address = '".mysql_real_escape_string($eventInfo->address)."', 
									e.event_datetime = '".mysql_real_escape_string($datetime)."', 
									e.event_deadline = '".mysql_real_escape_string($sqlDeadline)."', 
									e.description = '".mysql_real_escape_string($eventInfo->description)."',
									e.is_public = ".mysql_real_escape_string($eventInfo->is_public).", 
									e.location_lat=".mysql_real_escape_string($eventInfo->location_lat).",
									e.location_long=".mysql_real_escape_string($eventInfo->location_long).",
									e.type = ".$eventInfo->type." 
							WHERE	e.id = ".mysql_real_escape_string($eventInfo->eid);
		$this->executeUpdateQuery($UPDATE_EVENT);
	}
	
	public function getQueryResultAssoc($sqlQuery) {
		$sqlResult = $this->getQueryResult($sqlQuery);
		$sqlRows = array();
		while ($row = mysql_fetch_array($sqlResult, MYSQL_ASSOC)) {
			array_push($sqlRows, $row);
		}
		mysql_free_result($sqlResult);
		return $sqlRows;
	}
	
	public function getRowNum($sqlQuery) {
		$sqlResult = $this->getQueryResult($sqlQuery);
		return mysql_num_rows($sqlResult);
	}
	
	public function getEventByEO($uid) {
		$GET_EVENTS = "	SELECT	* 
						FROM	
						(
							SELECT		e.id, 
							TIMEDIFF
							(
										e.event_datetime, 
										NOW()
							) 
							AS			time_left,
										e.created, 
										e.title, 
										e.url, 
										e.goal, 
										e.location_address, 
										e.event_datetime, 
										e.event_deadline, 
										e.description, 
										e.is_public 
							FROM		ef_events e 
							WHERE		e.organizer = " . $uid . "
						) el
						WHERE			el.time_left > 0 
						ORDER BY		el.time_left ASC";
		return $this->getQueryResultAssoc($GET_EVENTS);
	}
	
	public function getEventAttendingBy($uid) {
		$GET_EVENTS = "SELECT * FROM 
											(SELECT e.id, DATEDIFF(e.event_deadline, CURDATE()) AS days_left,
											 	e.created, e.title, e.url, e.goal, 
											 	e.location_address, e.event_datetime, e.event_deadline, 
											 	e.description, e.is_public 
											FROM ef_attendance a, ef_events e WHERE a.event_id = e.id AND a.user_id = ".$uid.") el
									WHERE el.days_left > 0 ORDER BY el.days_left ASC";
		return $this->getQueryResultAssoc($GET_EVENTS);
	}
	
	/* getEventInfo
	 * Grabs information about an event with the given
	 * event ID from the Database.
	 */
	public function getEventInfo($eid) {
		$GET_EVENT = "	SELECT	id, 
								DATEDIFF (
									event_deadline, 
									CURDATE()
								) AS days_left,
								created,
								organizer, 
								title, 
								goal, 
								location_address, 
								event_datetime, 
								event_deadline, 
								description, 
								is_public, 
								type,
								location_lat,
								location_long
						FROM	ef_events
						WHERE	id = " . $eid;
		return $this->executeValidQuery( $GET_EVENT );
	}
	
	public function hasAttend($uid, $eid) {
		$HAS_ATTEND = "	SELECT	* 
						FROM	ef_attendance a 
						WHERE	a.event_id = " . $eid . " 
						AND		a.user_id = " . $uid;
		if ($this->getRowNum($HAS_ATTEND) > 0) {
			return $this->executeValidQuery($HAS_ATTEND);
		}
		return NULL;
	}
	
	public function eventSignUp($uid, $eid, $conf) {
		if ( ! $this->hasAttend($uid, $eid) ) {
			$SIGN_UP_EVENT = "	INSERT INTO ef_attendance (event_id, user_id, confidence) VALUES (".$eid.", ".$uid.", ".$conf.")";
			$this->executeUpdateQuery($SIGN_UP_EVENT);
		} else {
						$UPDATE_SIGN_UP = "UPDATE ef_attendance SET confidence = ".$conf.", is_attending = 1 WHERE event_id = ".$eid." AND user_id = ".$uid;

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
		$uid=$_SESSION['uid'];
		$UPDATE_USER_STATUS = "UPDATE ef_users e SET e.about = '".$status."' WHERE e.id = ".$uid;
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
	
	// DEPRECATED: Paypal
	public function getAttendees($eid) {
		$GET_ATTENDEES = "SELECT p.uid, p.eid, p.pkey, p.pemail, 
													FROM ef_event_preapprovals p, ef_events e 
											WHERE p.eid = e.id AND p.eid = ".$eid;
		return $this->getQueryResultAssoc($GET_ATTENDEES);
	}
	
	/* Depreciated
	public function getNewEvents() {
		$GET_NEW_EVENTS = "SELECT * FROM ef_events WHERE is_public = 1 ORDER BY event_datetime DESC LIMIT 3";
		return $this->getQueryResultAssoc($GET_NEW_EVENTS);
	} */
	
	public function storeGuests($guestEmails, $eid, $referrer) {
		for ($i = 0; $i < sizeof($guestEmails); ++$i) {
			if (!$this->isUserEmailExist($guestEmails[$i])) {
				$STORE_GUEST_EMAIL_USERS = "INSERT IGNORE INTO ef_users (email, referrer) VALUES ('".$guestEmails[$i]."', ".$referrer.")";
				$this->executeUpdateQuery($STORE_GUEST_EMAIL_USERS);
			}
			$userInfo = $this->getUserInfoByEmail($guestEmails[$i]);
			$STORE_GUEST_EMAIL_ATTENDEES = "INSERT IGNORE INTO ef_attendance (event_id, user_id) VALUES (".$eid.", ".$userInfo['id'].")";
			$this->executeUpdateQuery($STORE_GUEST_EMAIL_ATTENDEES);
		}
	}
	
	
	// Should confidence be greater than 4
	// or whatever is bigger than the Not Attending confidence?
	public function getAttendeesByEvent($eid) {
		$GET_ATTENDEES = "	SELECT	* 
							FROM 	ef_attendance a, 
									ef_users u 
							WHERE 	a.user_id = u.id 
							AND 	a.confidence 
							IS 		NOT NULL 
							AND 	a.event_id = " . $eid;
		return $this->getQueryResultAssoc($GET_ATTENDEES);
	}
	
	public function getNumAttendeesByConfidence($eid, $conf) {
		$GET_ATTENDEES = "SELECT COUNT(*) AS guest_num FROM ef_attendance a, ef_users u 
												WHERE a.user_id = u.id AND a.confidence = ".$conf." AND a.event_id = ".$eid;
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
	
	// If the recipient_group IS NULL, the recipient is all attendees
	public function saveEmail($eid, $msg, $deliveryDateTime, $subject, $type, $autoReminder) {
		$deliveryTime = $deliveryTime.":00";
		$SAVE_REMINDER = "
		INSERT INTO	ef_event_messages 
					(
						created, 
						subject, 
						message, 
						delivery_time, 
						event_id, 
						type, 
						is_activated
					) 
		VALUES		(
						NOW(), 
						'" . mysql_real_escape_string($subject) . "', 
						'" . mysql_real_escape_string($msg) . "', 
						'" . mysql_real_escape_string($deliveryDateTime) . "', 
						" . $eid.",
						" . $type.",
						" . $autoReminder."
					)";
		$this->executeUpdateQuery($SAVE_REMINDER);
	}
	
	public function saveText($eid, $msg, $deliveryDateTime, $type, $autoReminder) {
		$deliveryTime = $deliveryTime.":00";
		$SAVE_REMINDER = "
		INSERT INTO	ef_event_messages 
					(
						created, 
						message, 
						delivery_time, 
						event_id, 
						type, 
						is_activated
					) 
		VALUES		(
						NOW(),
						'" . mysql_real_escape_string($msg) . "', 
						'" . mysql_real_escape_string($deliveryDateTime) . "', 
						" . $eid.",
						" . $type.",
						" . $autoReminder."
					)";
		$this->executeUpdateQuery($SAVE_REMINDER);
	}
	
	public function getEventEmail($eid, $type) {
		$GET_EVENT_EMAIL = "SELECT * FROM ef_event_messages m WHERE m.event_id = ".$eid." 
													AND m.type = ".$type." ORDER BY m.created DESC LIMIT 1";
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
			$RESET_PASSWORD = "UPDATE ef_users SET password = '".$newPass."' WHERE id = ".$_SESSION['uid'];
			$this->executeUpdateQuery($RESET_PASSWORD);
			return true;
		}
		return false;
	}
	
	public function getUserPass() {
		$GET_USER_PASS = "SELECT password FROM ef_users WHERE id = ".$_SESSION['uid'];
		return $this->executeQuery($GET_USER_PASS);
	}
	
	public function requestPasswordReset($hash_key, $email) {
		$CHECK_VALID_EMAIL = "SELECT * FROM ef_users u WHERE u.email = '".$email."'";
		if ($this->getRowNum($CHECK_VALID_EMAIL) == 0) {
			return false;
		}
		$REQUEST_PASS_RESET = "INSERT INTO ef_password_reset (hash_key, email) VALUES ('".$hash_key."', '".$email."')";
		$this->executeUpdateQuery($REQUEST_PASS_RESET);
		return true;
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

}
