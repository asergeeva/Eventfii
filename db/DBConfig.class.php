<?php
/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
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
	public function executeInsert($query) {
		$dbLink = $this->openCon();
		$dbResult = mysql_query( $query );
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
		return true;
	}
	
	/* function executeUpdateQuery
	 * Executes an updates query. There is not result set.
	 *
	 * @param $query | The query to be run on the db
	 */
	public function getLastInsertedId() {
		$dbLink = $this->openCon();
		return mysql_insert_id($dbLink);
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
	 * Checks to see if user login credentials are valid
	 * @return	$userInfo if valid, NULL if not
	 */
	public function checkValidUser($email, $pass) {
		$CHECK_VALID_USER = "	SELECT	* 
								FROM 	ef_users
								WHERE 	email = '" . $email . "' 
								AND 	password = '" . md5($pass) . "'";
		$userInfo = $this->executeQuery($CHECK_VALID_USER);
		if ( isset($userInfo) ) {
			return $userInfo;
		}
		return NULL;
	}
	
	/**
	 * Pulls the entire user object from the database,
	 * which is only useful for when you need info about
	 * the logged in user.
	 *
	 * Will be depreciated soon, since checkValidUser
	 * already does this now.
	 *
	 * @param	$info | Either the user's ID or email
	 * @return	$userInfo if valid, NULL if not
	 */
	public function getUserInfo($info) {
		if ( is_numeric($info) ) {
			$where = "id = " . $info;
		} else {
			$where = "email = '" . $info . "'";
		}
		$GET_USER_INFO = "	SELECT	* 
							FROM 	ef_users
							WHERE 	" . $where;
		$userInfo = $this->executeValidQuery($GET_USER_INFO);
		return $userInfo;
	}
	
	/**
	 * Pulls only the most basic user information from the database.
	 * @param	$info | Either the user's ID or email
	 * @return	$userInfo if valid, NULL if not
	 */
	public function getBasicUserInfo($info) {
		if ( is_numeric($info) ) {
			$where = "id = " . $info;
		} else {
			$where = "email = '" . $info . "'";
		}
		$GET_USER_INFO = "	SELECT	id,
									fname,
									lname,
									email,
									about,
									verified,
									pic,
									twitter,
									facebook,
									url_alias
							FROM 	ef_users
							WHERE 	" . $where;
		$userInfo = $this->executeValidQuery($GET_USER_INFO);
		return $userInfo;
	}
	
	/*Get Image name*/
	public function getEventName($eid)
	{
		$GET_TITLE = "SELECT title FROM ef_events WHERE id=".$eid;
		$image = $this->executeQuery($GET_TITLE);
		return $image['title'];	
	}
	/*Gett image for event*/
	public function getEventImage($eid)
	{
		$GET_IMAGE = "SELECT image FROM ef_events WHERE id=".$eid;
		$image = $this->executeQuery($GET_IMAGE);
		return $image['image'];
	}
	
	/*Inserting images for event*/
	public function insertEventImages($uid, $eid, $imageName)
	{
		$date = date("Y-m-d H:i:s");
		$IMAGES = "INSERT INTO ef_event_images SET event_id='".mysql_real_escape_string($eid)."', owner_id='".mysql_real_escape_string($uid)."', image='".mysql_real_escape_string($imageName)."', created_at='".mysql_real_escape_string($date)."'";	
		$this->executeInsert($IMAGES);
	}
	/*Select all event images*/
	public function getAllEventImages($eid, $uid=0)
	{
		$where = '';
		$order_by = '';
		if($uid != 0)
		{
			$where = " AND owner_id='".mysql_real_escape_string($uid)."'";
		}else if($uid == 'taken' || $uid == 'added')
		{
			$order_by = " ORDER BY created_at DESC";
		}
		$GET_IMAGES = "SELECT id, image, owner_id FROM ef_event_images WHERE event_id='".mysql_real_escape_string($eid)."'".$where.' '.$order_by;
		return $this->getQueryResultAssoc($GET_IMAGES);
	}
	public function delImage($delId)
	{
		//$SQL = "SELECT image FROM ef_event_images WHERE id='".mysql_real_escape_string($delId)."'";
		//$row = $this->executeValidQuery($SQL);
		//unlink();
		$SQL = "DELETE FROM ef_event_images WHERE id='".mysql_real_escape_string($delId)."'";
		$this->executeUpdateQuery($SQL);
	}
	public function getUsersByImages($eid)
	{
		$GET_USERS = "SELECT DISTINCT owner_id FROM ef_event_images WHERE event_id='".mysql_real_escape_string($eid)."'";
		return $this->getQueryResultAssoc($GET_USERS);
	}
	public function getUserNameById($uid)
	{
		$GET_USER_NAME = "SELECT CONCAT(fname, ' ', lname) as name FROM ef_users WHERE id='".mysql_real_escape_string($uid)."'";	
		$row = $this->executeValidQuery($GET_USER_NAME);
		return $row['name'];
	}
	
	/**
	 * Get the a specific user based on the email
	 * @param $email | String | A valid email address
	 * @return Array the user row in the database
	 */
	public function getUserInfoByEmail( $email ) {
		$GET_USER_INFO = "	SELECT	* 
							FROM 	ef_users 
							WHERE 	
							email = '" . mysql_real_escape_string($email) . "'";
		$userInfo = $this->executeQuery($GET_USER_INFO);
		return $userInfo;
	}
	
	/**
	 * Given the URI alias, get the user row in the DB
	 * @param $url_alias   String   the alias URI of an user
	 */
	public function getUserByURIAlias($url_alias) {
		$GET_USER_URI_ALIAS = "	SELECT	* 
								FROM 	ef_users 
								WHERE 	url_alias = '" . mysql_real_escape_string($url_alias) . "'";
		return $this->executeQuery($GET_USER_URI_ALIAS);
	}
	
	public function updateUserInfo( $fname, $lname, $phone, $zip, $notif_opt1, $notif_opt2, $notif_opt3 ) {
		$UPDATE_USER = "UPDATE	ef_users 
						SET 	fname 		= '" . mysql_real_escape_string($fname) . "', 
								lname 		= '" . mysql_real_escape_string($lname) . "',
								phone 		= '" . mysql_real_escape_string($phone) . "',
								zip 		= '" . mysql_real_escape_string($zip) . "',
								notif_opt1 	= " . $notif_opt1 . ",
								notif_opt2 	= " . $notif_opt2 . ",
								notif_opt3 	= " . $notif_opt3 . "
						WHERE	id = " . $_SESSION['user']->id;
		$this->executeUpdateQuery($UPDATE_USER);
	}
	public function updateUserInfoAttend($fname, $lname, $uid)
	{
		$SQL = "UPDATE ef_users SET fname='".$fname."', lname='".$lname."' WHERE id=".$uid;
		$this->executeUpdateQuery($SQL);	
	}
	
	public function getReferenceEmail($hashKey) {
		$GET_REF_EMAIL = "	SELECT	* 
							FROM 	ef_event_invites i 
							WHERE 	i.hash_key = '" . mysql_real_escape_string($hashKey) . "'";
		$invitedEmail = $this->executeQuery($GET_REF_EMAIL);
		return $invitedEmail;
	}
	
	public function isUserEmailExist($email) {
		$GET_USER_EMAIL = "	SELECT	* 
							FROM 	ef_users e 
							WHERE 	
							e.email = '" . mysql_real_escape_string($email) . "'";
							/*OR e.email2 = '" . mysql_real_escape_string($email) . "' 
							OR e.email3 = '" . mysql_real_escape_string($email) . "' 
							OR e.email4 = '" . mysql_real_escape_string($email) . "' 
							OR e.email5 = '" . mysql_real_escape_string($email) . "'";*/
		if ($this->getRowNum($GET_USER_EMAIL) == 0) {
			return false;
		}
		return true;
	}
	
	private function isUserRegistered($email) {
		$GET_USER_EMAIL = "	SELECT	* 
							FROM 	ef_users e 
							WHERE 	
							e.email = '" . mysql_real_escape_string($email) . "' AND password IS NULL";
							/*OR e.email2 = '" . mysql_real_escape_string($email) . "' 
							OR e.email3 = '" . mysql_real_escape_string($email) . "' 
							OR e.email4 = '" . mysql_real_escape_string($email) . "' 
							OR e.email5 = '" . mysql_real_escape_string($email) . "' 
							AND password IS NULL";*/
		if ($this->getRowNum($GET_USER_EMAIL) == 0) {
			return false;
		}
		return true;
	}
	
	public function getUserContacts($uid) {
		$GET_CONTACT = "SELECT u.* FROM ef_addressbook a, ef_users u WHERE a.contact_id = u.id 
							AND a.user_id <> u.id 
							AND a.user_id = ".$uid." 
							ORDER BY u.fname";
		return $this->getQueryResultAssoc($GET_CONTACT);
	}
	
	public function getFBFriends($uid) {
		$GET_FB_FRIENDS = "SELECT * FROM fb_friends f WHERE f.user_id = ".$uid. " ORDER BY fb_name";
		return $this->getQueryResultAssoc($GET_FB_FRIENDS);
	}
	
	/**
	 * Get the combined email and FB contacts sorted by its name
	 */
	public function getContactsUnion($uid) {
		$GET_ALL_CONTACTS = "SELECT c.name, c.email AS id FROM (
							  (SELECT CONCAT_WS(' ', u.fname, u.lname) AS name, u.email FROM ef_addressbook a, ef_users u WHERE a.contact_id = u.id 
							                AND a.user_id <> u.id 
							                AND a.user_id = ".mysql_real_escape_string($uid).")
							  UNION
							  (SELECT f.fb_name, f.fb_id FROM fb_friends f WHERE f.user_id = ".mysql_real_escape_string($uid)." ORDER BY fb_name)
							) c ORDER BY c.name";
		return $this->getQueryResultAssoc($GET_ALL_CONTACTS);
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
	
	/**
	 * Check whether the value should use NULL
	 */
	private function checkNullOrValSql($val) {
		if (isset($val) && trim($val) != "") {
			return "'".mysql_real_escape_string($val)."'";
		}
		return "NULL";
	}
	
	/**
	 * Update user
	 */
	private function updateUser($fname, $lname, $phone, $pass, $zip, $fbid, $access_token, $session_key, $email) {
		$emailPredicate = "";
		// Check for secondary emails
		if (isset($_SESSION['eref'])) {
			$userInfo = $this->getUserInfoByEmail($email);
			if (!isset($userInfo['email2'])) {
				$emailPredicate = ", email2 = '".mysql_real_escape_string($_SESSION['eref']['email_secondary'])."'";
			} else if (!isset($userInfo['email3'])) {
				$emailPredicate = ", email3 = '".mysql_real_escape_string($_SESSION['eref']['email_secondary'])."'";
			} else if (!isset($userInfo['email4'])) {
				$emailPredicate = ", email4 = '".mysql_real_escape_string($_SESSION['eref']['email_secondary'])."'";
			} else {
				$emailPredicate = ", email5 = '".mysql_real_escape_string($_SESSION['eref']['email_secondary'])."'";
			}
		}
	
		$UPDATE_USER = "UPDATE	ef_users SET
								fname = ".$this->checkNullOrValSql($fname).", 
								lname = ".$this->checkNullOrValSql($lname).",
								phone = ".$this->checkNullOrValSql($phone).",
								password = ".$this->checkNullOrValSql($pass).",
								zip = ".$this->checkNullOrValSql($zip).",
								facebook = ".$this->checkNullOrValSql($fbid).",
								fb_access_token = ".$this->checkNullOrValSql($access_token).",
								fb_session_key = ".$this->checkNullOrValSql($session_key).",
								url_alias = HEX(".USER_ALIAS_OFFSET." + id)
								".$emailPredicate." 
						WHERE email = '" . mysql_real_escape_string($email) . "'"; 
						/*OR email2 = '" . mysql_real_escape_string($email) . "' 
						OR email3 = '" . mysql_real_escape_string($email) . "' 
						OR email4 = '" . mysql_real_escape_string($email) . "' 
						OR email5 = '" . mysql_real_escape_string($email) . "'";*/
	
		$this->executeUpdateQuery($UPDATE_USER);
	}
	
	public function recordUnconfirmedAttendance($event, $userId) {
		$RECORD_ATTEND_UNCONFO = "	INSERT IGNORE INTO ef_attendance (event_id, user_id) 
									VALUES (" . mysql_real_escape_string($event->eid) . ", " . mysql_real_escape_string($userId) . ")";
		EFCommon::$dbCon->executeUpdateQuery($RECORD_ATTEND_UNCONFO);
	}
	
	public function getNextUserId() {
		$GET_MAX_UID = "  	SELECT	MAX(u.id) AS max_id 
							FROM	ef_users u";
		$maxId = $this->executeQuery($GET_MAX_UID);
		if ( is_null($maxId['max_id']) ) {
			return 1;
		}
		
		return intval($maxId['max_id']) + 1;
	}
	
	/**
	 * If the user is inviting friends through Facebook, record the invites
	 */
	public function inviteUserFB($uid, $from_fbid, $to_fbid, $request_id, $data, $eid) {
		echo $INVITE_FB = "INSERT IGNORE INTO fb_invited (user_id, from_fbid, to_fbid, request_id, invite_data, event_id) 
						VALUES (".mysql_real_escape_string($uid).", 
								'".mysql_real_escape_string($from_fbid)."', 
								'".mysql_real_escape_string($to_fbid)."', 
								'".mysql_real_escape_string($request_id)."', 
								".$this->checkNullOrValSql($data).", 
								".mysql_real_escape_string($eid).")";
		$this->executeUpdateQuery($INVITE_FB);
	}
	
	/**
	 * Create a new user
	 */
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
		
		$newUser = NULL;
		
		// If the email hasn't yet been found in the system
		if ( ! $this->isUserEmailExist($email) && !isset($_SESSION['eref'])) {
			$nextUserId = $this->getNextUserId();
			$CREATE_NEW_USER = "INSERT IGNORE INTO ef_users(fname, lname, email, phone, password, 
															zip, facebook, fb_access_token, fb_session_key, url_alias, user_cookie) 
								VALUES(".$this->checkNullOrValSql($fname).", 
									   ".$this->checkNullOrValSql($lname).", 
									   '".mysql_real_escape_string($email)."', 
									   ".$this->checkNullOrValSql($phone).", 
									   ".$this->checkNullOrValSql($pass).", 
									   ".$this->checkNullOrValSql($zip).",
									   ".$this->checkNullOrValSql($fbid).",
									   ".$this->checkNullOrValSql($access_token).",
									   ".$this->checkNullOrValSql($session_key).",
									   '".dechex(USER_ALIAS_OFFSET + $nextUserId)."',
									   '".md5(USER_COOKIE_PREFIX.$nextUserId)."')";
			$this->executeUpdateQuery($CREATE_NEW_USER);
			$newUser = $this->getUserInfoByEmail($email);
			
		// Update the reference
		// The implementation is the same as without reference
		// Future: may be we want to give credits to the inviter
		} else if ( isset($_SESSION['eref']) && $this->isUserRegistered($_SESSION['eref']['email_to'])) {
			$this->updateUser($fname, $lname, $phone, $pass, $zip, $fbid, $access_token, $session_key, $_SESSION['eref']['email_to']);
			$newUser = $this->getUserInfoByEmail($email);
		} else if (isset($_SESSION['fb']) && $this->isUserRegistered($_SESSION['fb']->email)) {
			$this->updateUser($fname, $lname, $phone, $pass, $zip, $fbid, $access_token, $session_key, $_SESSION['fb']->email);
			$newUser = $this->getUserInfoByEmail($email);
		} else if ($this->isUserRegistered($email)) {
			$this->updateUser($fname, $lname, $phone, $pass, $zip, $fbid, $access_token, $session_key, $email);
			$newUser = $this->getUserInfoByEmail($email); 
		}
		
		if (isset($newUser) && isset($_SESSION['gref'])) {
			$this->recordUnconfirmedAttendance($_SESSION['gref'], $newUser['id']);
		}
		
		$_SESSION['isNewUser'] = true;
		
		return $newUser;
	}
	
	public function facebookAdd($fbid) {
		$UPDATE_USER_FB = "UPDATE ef_users SET facebook = '".$fbid."' WHERE id = ".$_SESSION['user']->id;
		$this->executeUpdateQuery($UPDATE_USER_FB);
		
		$_SESSION['user']->facebook = $fbid;
		$_SESSION['user']->setUserPic($fbid);
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
	public function facebookConnect( $fname, $lname, $email, $fbid, $access_token = NULL, $session_key = NULL ) {
		if ( ! $this->isUserEmailExist($email) && !isset($_SESSION['eref'])) {
			// If the user is new, create their account
			$this->createNewUser( $fname, $lname, $email, NULL, NULL, NULL, $fbid, $access_token, $session_key );
		} else {
			if (isset($_SESSION['eref'])) {
				$_SESSION['eref']['email_secondary'] = $email;
				$email = $_SESSION['eref']['email_to'];
			}
			// If Facebook session exists, and we send request from manage guest page.
			if(isset($_SESSION['user']))
			{
				$email =  $_SESSION['user']->email;
			  // If Facebook session exists, use its data for the FB related variables
				$fbFriends = EFCommon::$facebook->api('/me/friends', array('access_token' => $access_token));
				$this->saveFBFriends($fbFriends['data'], $_SESSION['user']->id);
			}
			// Check to see that current users info is up to date
			$UPDATE_USER = "	UPDATE	ef_users 
								SET 	fname = '" . mysql_real_escape_string($fname) . "',
										lname = '" . mysql_real_escape_string($lname) . "',
										pic = '". mysql_real_escape_string(FB_GRAPH_URL. "/" .$fbid. "/picture"). "',
										facebook = '".mysql_real_escape_string($fbid)."',
										fb_access_token = ".$this->checkNullOrValSql($access_token).",
										fb_session_key = ".$this->checkNullOrValSql($session_key).",
										url_alias = HEX(".USER_ALIAS_OFFSET." + id) 
								WHERE	
								email = '" . mysql_real_escape_string($email) . "'";
								/*OR email2 = '" . mysql_real_escape_string($email) . "'
								OR email3 = '" . mysql_real_escape_string($email) . "'
								OR email4 = '" . mysql_real_escape_string($email) . "'
								OR email5 = '" . mysql_real_escape_string($email) . "'";*/
								
			// setcookie(USER_COOKIE, $_SESSION['user']->cookie);
			// The user must have already registered
			$this->executeUpdateQuery($UPDATE_USER);
				$fbUser = $this->getUserInfoByEmail($email);
				if (isset($fbUser['password'])) {
					$_SESSION['user'] = new User($fbUser);
				}
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
	
	/**
	 * Get the user from the DB given the cookie value of that user
	 *
	 * @param $cookieVal  String the cookie value for that user
	 *
	 * @return Array of the user row from the DB
	 */
	public function getUserByCookie($cookieVal) {
		$GET_USER_COOKIE = "SELECT * FROM ef_users WHERE user_cookie = '".$cookieVal."'";
		if ($this->getRowNum($GET_USER_COOKIE) == 0) {
			return NULL;
		}
		return $this->executeQuery($GET_USER_COOKIE);
	}
	
	
	public function dateToSql($date) {
		$dateElem = explode("/", $date);
		if ($dateElem[1] == "" || $dateElem[2] == "" || $dateElem[0] == "") {
			return "";
		}
		return $dateElem[2]."-".$dateElem[0]."-".$dateElem[1];
	}
	
	public function dateToRegular($date) {
		$dateElem = explode("-", $date);
		if ($dateElem[1] == "" || $dateElem[2] == "" || $dateElem[0] == "") {
			return "";
		}
		return $dateElem[1]."/".$dateElem[2]."/".$dateElem[0];
	}
	
	public function addEventImage($event_id, $imageURL)
	{
		$UPDATE_EVENT = "UPDATE ef_events SET image='".mysql_real_escape_string($imageURL)."' WHERE id='".mysql_real_escape_string($event_id)."'";
		$this->executeUpdateQuery($UPDATE_EVENT);
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
		
		$nextEventId = $this->getNextEventId();
		if(isset($newEvent->organizer->id) && $newEvent->organizer->id != '')
		{
			$organizer = $newEvent->organizer->id;
		}else
		{
			$organizer = $_SESSION['user']->id;	
		}
		
		$CREATE_NEW_EVENT = "
			INSERT INTO ef_events (	
							created, 
							organizer, 
							title, 
							goal, 
							reach_goal,
							location_name,
							total_rsvps,
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
							url_alias,
							global_ref
						) 
			VALUES (	NOW(), 
						" . mysql_real_escape_string($organizer) . ",
						'" . mysql_real_escape_string($newEvent->title) . "', 
						" . mysql_real_escape_string($newEvent->goal) . ",
						" . mysql_real_escape_string($newEvent->reach_goal). ",
						" . $this->checkNullOrValSql($newEvent->location) . ",
						'".mysql_real_escape_string($newEvent->total_rsvps)."',
						'" . mysql_real_escape_string($newEvent->address) . "',
						'" . mysql_real_escape_string($datetime) . "',
						 " . $this->checkNullOrValSql($end_datetime) . ",
						'" . mysql_real_escape_string($sqlDeadline) . "',
						'" . mysql_real_escape_string($newEvent->description) . "',	
						" . mysql_real_escape_string($newEvent->is_public) . ",
						" . mysql_real_escape_string($newEvent->type) . ",
						" . mysql_real_escape_string($newEvent->location_lat) . ",
						" . mysql_real_escape_string($newEvent->location_long) . ",
						" . $this->checkNullOrValSql($twitter) . ",
						'" .dechex(EVENT_ALIAS_OFFSET + $nextEventId). "',
						'" .md5(EVENT_REF_PREFIX.$nextEventId). "'
			)";
		$this->executeUpdateQuery($CREATE_NEW_EVENT);
	}
	
	public function getLastEventCreatedBy($uid) {
		$GET_LAST_EVENT = "SELECT 
								id, 
								DATEDIFF ( e.event_deadline, CURDATE() ) AS rsvp_days_left,
								DATEDIFF ( e.event_datetime, CURDATE() ) AS days_left,
								UNIX_TIMESTAMP(e.event_datetime) - UNIX_TIMESTAMP(NOW()) AS time_left,
								DATE_FORMAT(e.event_datetime, '%M %d, %Y') AS friendly_event_date,
								DATE_FORMAT(e.event_datetime, '%r') AS friendly_event_time,
								e.*
		 				   FROM ef_events e WHERE organizer = ".$uid." ORDER BY created DESC LIMIT 1";
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
	
	public function getRowNum($sqlQuery) {
		$sqlResult = $this->getQueryResult($sqlQuery);

		if ($sqlResult == NULL )
			return 0;
			
		return mysql_num_rows($sqlResult);
	}
	
	/* CONTROL PANEL ASSIGN EVENTS */
	
	/**
	 * Get all of the events that is hosted by $uid
	 * @param $uid        | Integer | The user ID
	 * @param $publicOnly | Boolean | Whether we want the public event only or not
	 * @return Array the rows for all of the events that is hosted by the user
	 */
	public function getEventByEO($uid, $publicOnly = false) {
		$privateFilter = ($publicOnly) ? "AND is_public = 1" : "";
			
		$GET_EVENTS = "	SELECT	* 
						FROM (
							SELECT	TIMEDIFF( e.event_datetime, NOW() ) AS days_left,
									UNIX_TIMESTAMP(e.event_datetime) - UNIX_TIMESTAMP(NOW()) AS time_left,
									e.*
							FROM	ef_events e 
							WHERE	e.organizer = " . $uid . " AND e.is_active = 1 ".$privateFilter."
						) el
						WHERE el.time_left > 0 ORDER BY el.days_left ASC";
		return $this->getQueryResultAssoc($GET_EVENTS);
	}
	
	/**
	 * Get all of the past events that is hosted by $uid
	 * @param $uid | Integer | The user ID
	 * @return Array the rows for all of the past events that is hosted by the user
	 */
	public function getPastEventByEO($uid) {			
		$GET_EVENTS = "	SELECT	* 
						FROM (
							SELECT	TIMEDIFF( e.event_datetime, NOW() ) AS days_left,
									UNIX_TIMESTAMP(e.event_datetime) - UNIX_TIMESTAMP(NOW()) AS time_left,
									e.*
							FROM	ef_events e 
							WHERE	e.organizer = " . $uid . " AND e.is_active = 1
						) el
						WHERE el.time_left < 0 ORDER BY el.days_left ASC";
		return $this->getQueryResultAssoc($GET_EVENTS);
	}
	
	/**
		* Check whether the user belongs to a specific event or not?
		* Params $uid, and $eid.
		* return true if user is owner else return false.
	**/
	
	public function checkEventOwner($uid, $eid)
	{
		$SQL = "SELECT * FROM ef_events WHERE organizer='".mysql_real_escape_string($uid)."' AND id='".mysql_real_escape_string($eid)."'";
		if ($this->getRowNum($SQL) > 0) {
			return true;
		}else
		{
			return false;
		}
	}
	
	/**
	 * Get all of the events that is attended by the $uid
	 * @param $uid        | Integer | The user ID
	 * @param $publicOnly | Boolean | Whether we want the public event only or not
	 * @return Array the rows for all of the events that is attended by the user
	 */
	public function getEventAttendingByUid($uid, $publicOnly = false) {
		$privateFilter = ($publicOnly) ? "AND is_public = 1" : "";
			
		$GET_EVENTS = "	SELECT	* 
						FROM (
								SELECT 	TIMEDIFF( e.event_datetime, NOW() ) AS days_left,
										UNIX_TIMESTAMP(e.event_datetime) - UNIX_TIMESTAMP(NOW()) AS time_left,
										e.*
								FROM 	ef_attendance a, 
										ef_events e 
								WHERE 	a.event_id = e.id 
								AND 	a.user_id = " . $uid . " 
								AND 	a.confidence <> " . CONFOPT6 . "
								AND     a.confidence <> " . CONFELSE . " AND e.is_active = 1 ".$privateFilter."
								AND     e.organizer <> ".$uid."
						) el
						WHERE el.time_left > 0 ORDER BY el.days_left ASC";
		return $this->getQueryResultAssoc($GET_EVENTS);
	}
	
	/**
	 * Get all of the events that the $uid is invited to
	 * @param $uid        | Integer | The user ID
	 * @return Array the rows for all of the events that the user is invited to
	 */
	public function getEventInvited($uid) {
		$GET_INVITED = "SELECT	* FROM 
						  (SELECT TIMEDIFF( e.event_datetime, NOW() ) AS days_left,
						          UNIX_TIMESTAMP(e.event_datetime) - UNIX_TIMESTAMP(NOW()) AS time_left,
						          e.*,
						          a.*
						   FROM ef_attendance a, ef_events e 
						   WHERE a.event_id = e.id 
						   AND a.user_id = ".$uid." 
						   AND a.confidence = ".CONFELSE." 
						   AND e.is_active = 1
						) el
						WHERE el.time_left > 0 ORDER BY el.days_left ASC";
		return $this->getQueryResultAssoc($GET_INVITED);
	}
	
	/**
	 * Get all of the events that was attended by $uid
	 */
	public function getEventAttended($uid) {
		$GET_EVENTS = "	SELECT	* 
						FROM (
								SELECT 	TIMEDIFF( e.event_datetime, NOW() ) AS days_left,
										UNIX_TIMESTAMP(e.event_datetime) - UNIX_TIMESTAMP(NOW()) AS time_left,
										e.*
								FROM 	ef_attendance a, 
										ef_events e 
								WHERE 	a.event_id = e.id 
								AND 	a.user_id = " . $uid . " 
								AND 	a.confidence <> " . CONFOPT6 . "
								AND     a.confidence <> " . CONFELSE . " AND e.is_active = 1
						) el
						WHERE el.time_left < 0 ORDER BY el.days_left ASC";
		return $this->getQueryResultAssoc($GET_EVENTS);
	}
	
	/**
	 * Check whether this is a valid global reference to an event
	 * @param $gref  String   reference string to an event (unique for each event)
	 *
	 * @return Array of event DB if it's valid global reference
	 */
	public function isValidGlobalRef($gref) {
		$GREF_CHECK = "SELECT * FROM ef_events WHERE global_ref = '".$gref."'";
		if ($this->getRowNum($GREF_CHECK) == 0) {
			return false;
		}
		return $this->executeQuery($GREF_CHECK);
	}
	
	/* getEventInfo
	 * Grabs information about an event with the given
	 * event ID from the Database.
	 */
	public function getEventInfo($eid) {
		$GET_EVENT = "	SELECT	id, 
								DATEDIFF ( e.event_deadline, CURDATE() ) AS rsvp_days_left,
								DATEDIFF ( e.event_datetime, CURDATE() ) AS days_left,
								UNIX_TIMESTAMP(e.event_datetime) - UNIX_TIMESTAMP(NOW()) AS time_left,
								DATE_FORMAT(e.event_datetime, '%M %d, %Y') AS friendly_event_date,
								DATE_FORMAT(e.event_datetime, '%r') AS friendly_event_time,
								e.*
						FROM	ef_events e
						WHERE	id = " . $eid;
		return $this->executeValidQuery( $GET_EVENT );
	}
	
	/**
	 * Get the friendly date given a MySQL timestamp (e.g. 2011-09-30 00:30:00)
	 *
	 * @param $date String MySQL timestamp
	 *
	 * @return String friendly date (e.g. September 30, 2011, 12:30:00 AM) 
	 */
	public function getFriendlyDate($date) {
		$FRIENDLY_DATE = "SELECT DATE_FORMAT('".mysql_escape_string($date)."', '%M %d, %Y') AS friendly_event_date";
		
		$friendlyDate = $this->executeQuery($FRIENDLY_DATE);
		return $friendlyDate['friendly_event_date'];
	}
	
	/**
	 * Get the friendly time given a MySQL timestamp (e.g. 2011-09-30 00:30:00)
	 *
	 * @param $time String MySQL timestamp
	 *
	 * @return String friendly time (e.g. 12:30:00 AM) 
	 */
	public function getFriendlyTime($time) {
		$FRIENDLY_TIME = "SELECT DATE_FORMAT('".mysql_escape_string($time)."', '%r') AS friendly_event_time";
		
		$friendlyTime = $this->executeQuery($FRIENDLY_TIME);
		return $friendlyTime['friendly_event_time'];
	}
	
	/**
	 * Check whether the user has signed up to an event
	 *
	 * @param $uid  Integer  the user ID
	 * @param $eid  Integer  the event ID
	 *
	 * @return Boolean true if the user has signed up, false otherwise
	 */
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
	
	/**
	 * Check whether the user is alreay been invited 
	 * 		to an event, but has not yet responded
	 *
	 * @param $uid  Integer  the user ID
	 * @param $eid  Integer  the event ID
	 *
	 * @return Boolean true if the user had been invited, 
	 *		and has not yet responded false otherwise
	 */
	public function isInvitedNoResp($uid, $eid) {
		$IS_INVITED = "	SELECT	* 
						FROM	ef_attendance a 
						WHERE	a.event_id = " . $eid . " 
						AND		a.user_id = " . $uid . " AND a.confidence = ".CONFELSE;
		if ($this->getRowNum($IS_INVITED) > 0) {
			return $this->executeValidQuery($IS_INVITED);
		}
		return NULL;
	}
	
	
	/*Function for deleting the RSVP*/
	public function deleteRSVP($attend_id)
	{
		$exp = explode("_", $attend_id);
		$event_id = $exp[0];
		$user_id = $exp[1];
		$SQL_DELETE = "DELETE FROM ef_attendance WHERE event_id='". mysql_real_escape_string($event_id) ."' AND user_id='" . mysql_real_escape_string($user_id) . "'";
		$this->executeUpdateQuery($SQL_DELETE);
	}
	
	
	/**
	 * Sign up the user for an event with certain confidence level.
	 * Send the thank you email to the user that is attending the event.
	 * @param $uid   | Integer | The user ID that is signing up to the event
	 * @param $event | Event   | The event
	 * @update without sending email to users
	 */
	public function eventSignUpWithOutEmail($uid, $event, $conf, $user_id_posted, $is_attending=0) {
		$signedUp = $this->hasAttend($uid, $event->eid);
		$invitedNoResp = $this->isInvitedNoResp($uid, $event->eid);
		if ( ! isset($signedUp) && ! isset($invitedNoResp) ) {
			$SIGN_UP_EVENT = "	INSERT IGNORE INTO ef_attendance (event_id, user_id, confidence, rsvp_time, referenced_by, is_attending) 
								VALUES(
									" . mysql_real_escape_string($event->eid) . ", 
									" . mysql_real_escape_string($uid) . ", 
									" . mysql_real_escape_string($conf) . ",
									NOW(), 
									'" . mysql_real_escape_string($user_id_posted) . "',
									'" . mysql_real_escape_string($is_attending) . "'
								)";
			$this->executeUpdateQuery($SIGN_UP_EVENT);
		} else {
			$UPDATE_SIGN_UP = "	UPDATE 	ef_attendance 
								SET 	confidence = " . mysql_real_escape_string($conf) . " 
								WHERE 	event_id = " . mysql_real_escape_string($event->eid) . " 
								AND 	user_id = " . mysql_real_escape_string($uid);
			
			$this->executeUpdateQuery($UPDATE_SIGN_UP);
		}
	}
	
	
	/*Function getUserConfidence*/
	public function getGuestConfidence($uid, $eid)
	{
			$SQL = "SELECT confidence FROM ef_attendance WHERE user_id='".mysql_real_escape_string($uid)."' AND event_id='".mysql_real_escape_string($eid)."'";
			$row = $this->executeQuery($SQL);
			return $row['confidence'];
	}
	
	/**
	 * Sign up the user for an event with certain confidence level.
	 * Send the thank you email to the user that is attending the event.
	 * @param $uid   | Integer | The user ID that is signing up to the event
	 * @param $event | Event   | The event
	 */
	public function eventSignUp($uid, $event, $conf) {
		$signedUp = $this->hasAttend($uid, $event->eid);
		$invitedNoResp = $this->isInvitedNoResp($uid, $event->eid);
		
		if ( ! isset($signedUp) && ! isset($invitedNoResp) ) {
			$SIGN_UP_EVENT = "	INSERT IGNORE INTO ef_attendance (event_id, user_id, confidence, referenced_by, rsvp_time) 
								VALUES(
									" . mysql_real_escape_string($event->eid) . ", 
									" . mysql_real_escape_string($uid) . ", 
									" . mysql_real_escape_string($conf) . ",
									" . mysql_real_escape_string($uid) . ",
									NOW()
								)";
			$this->executeUpdateQuery($SIGN_UP_EVENT);
			EFCommon::$mailer->sendAGuestHtmlEmailByEvent('thankyou_RSVP', $_SESSION['user'], $event, 'Thank you for RSVPing to {Event name}');
		} else {
			$UPDATE_SIGN_UP = "	UPDATE 	ef_attendance 
								SET 	confidence = " . mysql_real_escape_string($conf) . " 
								WHERE 	event_id = " . mysql_real_escape_string($event->eid) . " 
								AND 	user_id = " . mysql_real_escape_string($uid);
			
			$this->executeUpdateQuery($UPDATE_SIGN_UP);
			if (!isset($invitedNoResp)) {
				EFCommon::$mailer->sendAGuestHtmlEmailByEvent('thankyou_RSVP', $_SESSION['user'], $event, 'Thank you for RSVPing to {Event name}');
			}
		}
	}
	
	public function eventWaitlist($uid, $event, $conf) {
		$signedUp = $this->hasAttend($uid, $event->eid);
		if ( ! isset($signedUp) ) {
			$SIGN_UP_WAITLIST_EVENT = "	INSERT INTO ef_waitinglist (event_id, user_id, confidence) 
										VALUES(
											" . mysql_real_escape_string($event->eid) . ", 
											" . mysql_real_escape_string($uid) . ", 
											" . mysql_real_escape_string($conf) . ",
											NOW()
										)";
			
			$this->executeUpdateQuery($SIGN_UP_EVENT);
		} else {
			$UPDATE_WAITLIST = "	UPDATE 	ef_waitinglist 
									SET 	confidence = " . mysql_real_escape_string($conf) . " 
									WHERE 	event_id = " . mysql_real_escape_string($event->eid) . " 
									AND 	user_id = " . mysql_real_escape_string($uid);

			$this->executeUpdateQuery($UPDATE_SIGN_UP);
		}
	}
	
	public function updateuserStatus($status) {
		$uid = $_SESSION['user']->id;
		$UPDATE_USER_STATUS = "	UPDATE	ef_users e 
								SET 	e.about = '" . $status . "' 
								WHERE 	e.id = " . $uid;
		$this->executeUpdateQuery($UPDATE_USER_STATUS);
	}
	
	public function storeContacts($contactEmails, $uid) {
		for ($i = 0; $i < sizeof($contactEmails); ++$i) {
			if (!$this->isUserEmailExist($contactEmails[$i])) {
				$STORE_EMAIL_USERS = "INSERT INTO ef_users (email, referrer)
											VALUES ('" . mysql_real_escape_string($contactEmails[$i]) . "', " . mysql_real_escape_string($uid) . ")";
				$this->executeUpdateQuery($STORE_EMAIL_USERS);
			}
			$new_user = new User($contactEmails[$i]);
			$STORE_CONTACT = "INSERT IGNORE INTO ef_addressbook (user_id, contact_id, contact_email) 
							  VALUES (" . mysql_real_escape_string($uid) . ", " . mysql_real_escape_string($new_user->id) . ", '".mysql_real_escape_string($new_user->email)."')";
			$this->executeUpdateQuery($STORE_CONTACT);
		}
	}
	
	/**
	 * $guestEmail Array    string of email addresses
	 * $eid        Integer  Event ID
	 */
	public function storeGuests($guestEmails, $eid) {
		for ($i = 0; $i < sizeof($guestEmails); $i++) {
			if (!$this->isUserEmailExist($guestEmails[$i])) {
				$STORE_GUEST_EMAIL_USERS = "INSERT INTO ef_users (email, referrer)
												VALUES (
													'" . mysql_real_escape_string($guestEmails[$i]) . "', 
													 " . mysql_real_escape_string($_SESSION['user']->id) . ")";
				$this->executeUpdateQuery($STORE_GUEST_EMAIL_USERS);
			}
			$STORE_GUEST_EMAIL_ATTENDEES = "INSERT IGNORE INTO ef_attendance (event_id, user_id) VALUES (" . mysql_real_escape_string($eid) . ", " . mysql_real_escape_string($_SESSION['user']->id) . ")";
			$this->executeUpdateQuery($STORE_GUEST_EMAIL_ATTENDEES);
		}
	}
	
	/* getAttendeesByEvent
	 * Grabs all the invited users to a specific event.
	 *
	 * @param $eid | The ID of the event
	 */
	public function getAttendeesByEvent($eid) {
		$GET_ATTENDEES = "	SELECT	* 
							FROM 	ef_attendance a, 
									ef_users u 
							WHERE 	a.user_id = u.id 
							AND 	a.event_id = " . $eid;
		return $this->getQueryResultAssoc($GET_ATTENDEES);
	}
	
	/* markChecked
		Mark the user as checked in
		@param eid | Event id, uid | user id
	*/
	public function markChecked($eid, $uid)
	{
		$SQL = "UPDATE ef_attendance SET is_attending='1' WHERE user_id='".mysql_real_escape_string($uid)."' AND event_id='".mysql_real_escape_string($eid)."'";
		$this->executeUpdateQuery($SQL);
	}
	
	/* unmarkChecked
		unMark the user as checked in
		@param eid | Event id, uid | user id
	*/
	public function unmarkChecked($eid, $uid)
	{
		$SQL = "UPDATE ef_attendance SET is_attending='0' WHERE user_id='".mysql_real_escape_string($uid)."' AND event_id='".mysql_real_escape_string($eid)."'";
		$this->executeUpdateQuery($SQL);
	}
	
	/* getAttendeesByEventAndUser
	 * Grabs all the invited users to a specific event.
	 *
	 * @param $eid | The ID of the event
	 */
	public function getAttendeesByEventAndUser($eid, $uid) {
		$GET_ATTENDEES = "SELECT count(*) as count FROM ef_attendance WHERE event_id = " . $eid . " AND referenced_by=" . $uid;
		$count = $this->executeQuery($GET_ATTENDEES);
		return $count['count'];
	}
	
	/* getAttendeesByEvent
	 * Grabs all the invited FB users to a specific event.
	 *
	 * @param $eid | The ID of the event
	 */
	public function getFBInvitedByEvent($eid) {
		$GET_ATTENDEES = "	SELECT	f.* 
							FROM 	fb_invited i, 
									fb_friends f 
							WHERE 	i.to_fbid = f.fb_id 
							AND i.event_id = " . $eid . " AND f.user_id = ". $_SESSION['user']->id;
		return $this->getQueryResultAssoc($GET_ATTENDEES);
	}
	
	/**
	 * Get the combined email and FB invited guests sorted by its name
	 */
	public function getUnionInvitedByEvent($eid) {
		$GET_ALL_INVITED = "SELECT c.name, c.email AS id FROM (
							  (SELECT	CONCAT_WS(' ', u.fname, u.lname) AS name, u.email 
							                FROM 	ef_attendance a, 
							                    ef_users u 
							                WHERE 	a.user_id = u.id 
							                AND 	a.event_id = ".$eid.")
							  UNION
							  (SELECT	f.fb_name, f.fb_id 
							                FROM 	fb_invited i, 
							                    fb_friends f 
							                WHERE 	i.to_fbid = f.fb_id 
							                AND i.event_id = ".$eid." AND f.user_id = ".$_SESSION['user']->id.")
							) c WHERE c.email <> '".$_SESSION['user']->email."' 
								AND c.email <> '".$_SESSION['user']->facebook."' ORDER BY c.name";
		return $this->getQueryResultAssoc($GET_ALL_INVITED);
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
							AND 	a.event_id = " . $eid . " AND a.confidence <> ".CONFELSE.
							" AND a.confidence <> ".CONFOPT6.
							" AND a.confidence <> ".CONFOPT5;
		return $this->getQueryResultAssoc($GET_ATTENDEES);
	}
	
	/**
	 * Get all of the guests who already RSVP'ed
	 * $uid, $eid   Integer   the ID of the user and event
	 */
	public function getConfirmedGuestsByUser($uid, $eid) {
		$GET_ATTENDEES = "	SELECT	* 
							FROM 	ef_attendance a, 
									ef_users u 
							WHERE 	a.user_id = u.id 
							AND 	a.event_id = " . $eid . " AND a.referenced_by=" . $uid . " AND user_id != " . $uid ." AND a.confidence <> ".CONFELSE.
							" AND a.confidence <> ".CONFOPT6.
							" AND a.confidence <> ".CONFOPT5;
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
							AND 	a.confidence = " . $conf . " 
							AND     a.event_id = " . $eid;
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
			  is_activated = ".mysql_real_escape_string($autoReminder).",
			  recipient_group = ".mysql_real_escape_string($group)."
			WHERE
			  event_id = ".mysql_real_escape_string($eid)." AND
			  type = ".mysql_real_escape_string($reminderType);
			$this->executeUpdateQuery($UPDATE_REMINDER);

		} else {
			$SAVE_REMINDER = "
			INSERT INTO	ef_event_messages 
						(
							sender_id,
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
							".$_SESSION['user']->id.",
							NOW(), 
							'" . mysql_real_escape_string($subject) . "', 
							'" . mysql_real_escape_string($msg) . "', 
							'" . mysql_real_escape_string($deliveryDateTime) . "', 
							" .mysql_real_escape_string($eid).",
							" . mysql_real_escape_string($reminderType).",
							" . mysql_real_escape_string($autoReminder).",
							".mysql_real_escape_string($group)."
						)";
			$this->executeUpdateQuery($SAVE_REMINDER);
		}
	}
	
	/**
	 * Save the secondary email (Max of secondary email is with index 5 [range: 1-5])
	 */
	public function saveSecondaryEmail($email, $index) {
		$colName = 'email'.$index;
		$UPDATE_SECOND_EMAIL = "UPDATE ef_users SET ".$colName." = ".$this->checkNullOrValSql($email)." WHERE id = ".$_SESSION['user']->id;
		$_SESSION['user']->$colName = $email;
		return $this->executeUpdateQuery($UPDATE_SECOND_EMAIL);
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
			  is_activated = ".mysql_real_escape_string($autoReminder).",
			  recipient_group = ".mysql_real_escape_string($group)."
			WHERE
			  event_id = ".mysql_real_escape_string($eid)." AND
			  type = ".SMS_REMINDER_TYPE;
			$this->executeUpdateQuery($UPDATE_REMINDER);

		} else {
			$SAVE_REMINDER = "
			INSERT INTO	ef_event_messages 
						(
							sender_id,
							created, 
							message, 
							delivery_time, 
							event_id, 
							type, 
							is_activated,
							recipient_group
						) 
			VALUES		(
							".$_SESSION['user']->id.",
							NOW(),
							'" . mysql_real_escape_string($msg) . "', 
							'" . mysql_real_escape_string($deliveryDateTime) . "', 
							" . mysql_real_escape_string($eid).",
							" . SMS_REMINDER_TYPE.",
							" . mysql_real_escape_string($autoReminder).",
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
		$CHECK_VALID_EMAIL = "SELECT * FROM ef_users u WHERE u.email = '".mysql_real_escape_string($email)."'";
		if ($this->getRowNum($CHECK_VALID_EMAIL) == 0) {
			return NULL;
		}
		$REQUEST_PASS_RESET = "INSERT INTO ef_password_reset (hash_key, email) VALUES ('".mysql_real_escape_string($hash_key)."', '".mysql_real_escape_string($email)."')";
		$this->executeUpdateQuery($REQUEST_PASS_RESET);
		return new User($this->getUserInfoByEmail($email));;
	}
	
	public function isValidPassResetRequest($hash_key) {
		$IS_VALID_PASS_RESET_REQUEST = "SELECT * FROM ef_password_reset r WHERE r.hash_key = '".mysql_real_escape_string($hash_key)."'";
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
		$IS_FOLLOW = "SELECT * FROM ef_friendship WHERE uid = ".mysql_real_escape_string($uid)." AND fid = ".mysql_real_escape_string($fid);
		if ($this->getRowNum($IS_FOLLOW) == 0) { 
			$FOLLOW_USER = "INSERT INTO ef_friendship (uid, fid) VALUES (".mysql_real_escape_string($uid).", ".mysql_real_escape_string($fid).")";
			$this->executeUpdateQuery($FOLLOW_USER);
			
			return 1;
		} else {
			$followInfo = $this->executeQuery($IS_FOLLOW);
			$isFollow = (intval($followInfo['is_follow']) == 1) ? 0 : 1;
			$UPDATE_FOLLOW = "UPDATE ef_friendship SET is_follow = ".$isFollow." WHERE uid = ".mysql_real_escape_string($uid)." AND fid = ".mysql_real_escape_string($fid);
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
										VALUES (".$userId.", 
												'".mysql_real_escape_string($fbFriends[$i]['name'])."', 
												'".$fbFriends[$i]['id']."')";
				$this->executeUpdateQuery($STORE_FB_FRIEND);
			} else {
				$STORE_FB_FRIEND = "INSERT IGNORE INTO fb_friends (user_id, fb_name, fb_id) 
										VALUES (".$userId.", 
												'".mysql_real_escape_string($fbFriends[$i]->name)."', 
												'".$fbFriends[$i]->id."')";
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
}