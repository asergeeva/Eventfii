<?php
class DBConfig {
	private $DB_HOST = "127.0.0.1:3306";
	private $DB_USER = "glaksmono";
	private $DB_PASS = "12345";
	
	private $DB_NAME = "eventfii";
	
	private $DEBUG = true;
	
	function __construct() {
		$this->openCon();
	}
	
	function __destruct() {
		
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
	
	public function executeInsertQuery($query) {
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
	  if (!$dbResult && $this->DEBUG) {
			print($query . "<br />");
		  die('Invalid query: ' . mysql_error());
	  }
		return $dbResult;
	}
	
	public function getMaxEventId() {
		$GET_MAX_EFID = "SELECT MAX(e.id) AS max_id FROM ef_events e";
		$maxId = $this->executeQuery($GET_MAX_EFID);
		if (is_null($maxId['max_id'])) {
			return 1;
		}
		return $maxId['max_id'] + 1;
	}
	
	public function checkValidUser($email, $pass) {
		$CHECK_VALID_USER = "SELECT * FROM ef_users e WHERE e.email = '".$email."' AND e.password = '".$pass."'";
		$userInfo = $this->executeQuery($CHECK_VALID_USER);
		if (isset($userInfo['id'])) {
			return $userInfo['id'];
		}
		return NULL;
	}
	
	public function getUserInfo($uid) {
		$GET_USER_INFO = "SELECT * FROM ef_users e WHERE e.id = ".$uid;
		$userInfo = $this->executeQuery($GET_USER_INFO);
		return $userInfo;
	}
	
	public function createNewUser($fname, $lname, $email, $pass) {
		$CREATE_NEW_USER = "INSERT INTO ef_users (fname, lname, email, password, about) 
													VALUES ('".$fname."', '".$lname."', '".$email."', '".$pass."', 'I am ".$fname."')";
		$this->executeInsertQuery($CREATE_NEW_USER);
	}
	
	public function getCurSignup($eid) {
		$GET_CUR_SIGNUP = "SELECT COUNT(*) AS cur_signups FROM ef_attendance a WHERE a.event_id = ".$eid;
		$curSignUp = $this->executeQuery($GET_CUR_SIGNUP);
		return $curSignUp['cur_signups'];
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
		$datetime = $this->dateToSql($newEvent["date"])." ".$newEvent["time"];
		$sqlDeadline = $this->dateToSql($newEvent["deadline"]);
		
		$CREATE_NEW_EVENT = "INSERT INTO ef_events (created, organizer, title, url, 
														 min_spot, max_spot, location_address, 
														 event_datetime, event_deadline, description, cost, is_public, gets) 
												 VALUES (NOW(), ".mysql_real_escape_string($newEvent["organizer"]).", 
												 							 '".mysql_real_escape_string($newEvent["title"])."', 
																			 '".mysql_real_escape_string($newEvent["url"])."', 
																			  ".mysql_real_escape_string($newEvent["min_spot"]).",
																				".mysql_real_escape_string($newEvent["max_spot"]).", 
																			 '".mysql_real_escape_string($newEvent["address"])."',
																			 '".mysql_real_escape_string($datetime)."',
																			 '".mysql_real_escape_string($sqlDeadline)."',
																			 '".mysql_real_escape_string($newEvent["description"])."',
																			 	".mysql_real_escape_string($newEvent["cost"]).", 
																			  ".mysql_real_escape_string($newEvent["is_public"]).",
																			 '".mysql_real_escape_string($newEvent["gets"])."')";
		$this->executeInsertQuery($CREATE_NEW_EVENT);
	}
	
	public function updateEvent($eventInfo) {
		
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
		$GET_EVENTS = "SELECT * FROM
										 (SELECT e.id, DATEDIFF(e.event_deadline, CURDATE()) AS days_left,
											 e.created, e.title, e.url, e.min_spot, e.max_spot, 
											 e.location_address, e.event_datetime, e.event_deadline, 
											 e.description, e.cost, e.is_public 
										 FROM ef_events e WHERE e.organizer = ".$uid.") el
									 WHERE el.days_left > 0 ORDER BY el.days_left ASC";
		return $this->getQueryResultAssoc($GET_EVENTS);
	}
	
	public function getEventAttendingBy($uid) {
		$GET_EVENTS = "SELECT * FROM 
											(SELECT e.id, DATEDIFF(e.event_deadline, CURDATE()) AS days_left,
											 	e.created, e.title, e.url, e.min_spot, e.max_spot, 
											 	e.location_address, e.event_datetime, e.event_deadline, 
											 	e.description, e.cost, e.is_public 
											FROM ef_attendance a, ef_events e WHERE a.event_id = e.id AND a.user_id = ".$uid.") el
									WHERE el.days_left > 0 ORDER BY el.days_left ASC";
		return $this->getQueryResultAssoc($GET_EVENTS);
	}
	
	public function getEvents($uid) {
		$GET_EVENTS = "SELECT * FROM
											((SELECT e.id, 
															 e.created, 
															 e.organizer, 
															 e.title, 
															 e.url, 
															 e.min_spot,
															 e.max_spot, 
															 e.location_address, 
															 e.location_lat, 
															 e.location_long, 
															 e.event_datetime, 
															 e.event_deadline,
															 e.description, 
															 e.cost 
											FROM ef_events e WHERE e.organizer = ".$uid.")
											UNION
											(SELECT e.id, 
															e.created, 
															e.organizer, 
															e.title, 
															e.url, 
															e.min_spot,
															e.max_spot, 
															e.location_address, 
															e.location_lat, 
															e.location_long, 
															e.event_datetime, 
															e.event_deadline,
															e.description, 
															e.cost
											FROM ef_attendance a, ef_events e WHERE a.user_id = ".$uid." AND a.event_id = e.id)) e
											WHERE e.event_datetime > NOW() ORDER BY e.event_datetime ASC";		
		return $this->getQueryResultAssoc($GET_EVENTS);
	}
	
	public function getEventInfo($eid) {
		$GET_EVENT = "SELECT DATEDIFF(e.event_deadline, CURDATE()) AS days_left,
										e.created, e.organizer, e.title, e.url, e.min_spot, e.max_spot, 
										e.location_address, e.event_datetime, e.event_deadline, 
										e.description, e.cost, e.is_public, e.gets 
									FROM ef_events e WHERE e.id = ".$eid;
		return $this->executeQuery($GET_EVENT);
	}
	
	public function hasAttend($uid, $eid) {
		$HAS_ATTEND = "SELECT * FROM ef_attendance a WHERE a.event_id = ".$eid." AND a.user_id = ".$uid;
		if ($this->getRowNum($HAS_ATTEND) > 0) {
			return true;
		}
		return false;
	}
	
	public function eventSignUp($uid, $eid) {
		if (!$this->hasAttend($uid, $eid)) {
			$SIGN_UP_EVENT = "INSERT INTO ef_attendance (event_id, user_id) VALUES (".$eid.", ".$uid.")";
			$this->executeInsertQuery($SIGN_UP_EVENT);
			return true;
		}
		return false;
	}
}