<?php
/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
require_once(realpath(dirname(__FILE__)).'/DBConfig.class.php');

class DBAPI extends DBConfig {
	public function __construct() {
		
	}
	
	public function __destruct() {
		
	}
	
	public function m_updateUserInfo($email,$about,$zip,$cell,$twitter)
	{
		$uid = unserialize($_SESSION['user'])->id;
		$UPDATE_USER_PROFILE="	UPDATE 	ef_users 
								SET 	email 	= '$email',
										about 	= '$about',
										zip 	= '$zip',
										phone	= '$cell',
										twitter	= '$twitter'
								WHERE	id = '$uid'";
		$this->executeUpdateQuery($UPDATE_USER_PROFILE);
		return 'status_updateCompleted';
	}
	
	public function m_checkValidUser($email, $pass) 
	{
		$CHECK_VALID_USER = "SELECT * FROM ef_users e WHERE e.email = '".$email."' AND e.password = '".$pass."'";
		$userInfo = $this->executeQuery($CHECK_VALID_USER);
		if (isset($userInfo['id'])) {
			return $userInfo['id'];
		}
		return NULL;
	}
	
	public function m_checkFBUser($email)
	{
		$CHECK_VALID_USER = "SELECT * FROM ef_users e WHERE e.email = '".$email."'";
		$userInfo = $this->executeQuery($CHECK_VALID_USER);
		if (isset($userInfo['id'])) 
		{
			return true;
		}
		return false;		
	}
	
	public function m_getEventAttendingBy($uid) 
	{
		$GET_EVENTS = "	SELECT	* 
						FROM (
								SELECT 	e.id, 
										DATEDIFF(e.event_datetime, CURDATE()) AS days_left,
										e.organizer,
										e.title, 
										e.location_address, 
										e.location_lat,
										e.location_long,
										e.event_datetime, 
										e.event_deadline,
										e.twitter,
										e.description, 
										a.is_attending,
										a.confidence,
										u.email
								FROM 	ef_attendance a, 
										ef_events e,
										ef_users u
								WHERE 	a.event_id = e.id AND e.organizer = u.id AND a.user_id = ".$uid."
						) el
						ORDER BY el.days_left ASC";
		return $this->getQueryResultAssoc($GET_EVENTS);
	}
	
	public function m_getAttendees($eid) 
	{
		$GET_ATTENDEES = "SELECT DISTINCT e.user_id FROM ef_attendance e WHERE e.event_id = '$eid'";
		return $this->executeQuery($GET_ATTENDEES);
	}
	
	public function m_getGuestListByEvent($eid) 
	{
		$GET_ATTENDEES = "	SELECT	a.confidence,
									u.id,
									u.fname,
									u.lname,
									u.email,
									a.is_attending
							FROM 	ef_attendance a, 
									ef_users u 
							WHERE 	a.user_id = u.id 
							AND 	a.event_id = " . $eid;
		return $this->getQueryResultAssoc($GET_ATTENDEES);
	}
	
	public function m_getUsername($uid)
	{
		$GET_USER_INFO = "SELECT e.fname, e.lname FROM ef_users e WHERE e.id = " . $uid;
		$userInfo = $this->executeValidQuery($GET_USER_INFO);
		return $userInfo;
	}
	//Deprecate
	public function m_checkInGuestWithDate($isAttend, $uid, $eid, $date) 
	{
		$getRSVPDate = $this->m_getCheckInDate($eid, $uid);
		$rsvpDate = new DateTime($getRSVPDate['rsvp_time']);
		$checkInDate = new DateTime($date);
		if($checkInDate >= $rsvpDate)
		{
			$CHECKIN_GUEST = "UPDATE ef_attendance a SET a.is_attending = ".$isAttend.", a.rsvp_time = \"".$date."\"
													WHERE a.user_id = ".$uid." AND a.event_id = ".$eid."";
			$this->executeUpdateQuery($CHECKIN_GUEST);
		}
	}
	public function m_getCheckInDate($eid, $uid)
	{
		$GET_DATE = "SELECT e.rsvp_time FROM ef_attendance e WHERE e.event_id = ".$eid." AND e.user_id = ".$uid."";
		$dateInfo = $this->executeValidQuery($GET_DATE);
		return $dateInfo;
	}
	public function m_isAttending($eid)
	{
		$uid = unserialize($_SESSION['user'])->id;
		$IS_ATTENDING="SELECT * FROM ef_attendance e WHERE e.event_id = ".$eid." AND e.user_id = ".$uid;
		$isAttending = $this->executeValidQuery($IS_ATTENDING);
		return $isAttending;
	}
	public function m_getGuestContactInfo($eid, $uid)
	{
		$GET_ATTENDEES = "	SELECT	* 
					FROM 	ef_attendance a, 
							ef_users u 
					WHERE 	u.id = " . $uid . "
					AND		a.user_id = u.id
					AND 	a.event_id = " . $eid;
		return $this->getQueryResultAssoc($GET_ATTENDEES);
	}
	//Deprecate
	public function m_eventSignUp($uid, $event, $conf)
	{
		$UPDATE_SIGN_UP = "	UPDATE 	ef_attendance 
							SET 	confidence = " . $conf . " 
							WHERE 	event_id = " . $event->eid . " 
							AND 	user_id = " . $uid;
		return $this->executeUpdateQuery($UPDATE_SIGN_UP);
	}
	public function m_eventSignUpWithDate($uid, $event, $conf, $date)
	{
		$getRSVPDate = $this->m_getCheckInDate($event-eid, $uid);
		$rsvpDate = new DateTime($getRSVPDate['rsvp_time']);
		$incomingRSVPDate = new DateTime($date);
		if($incomingRSVPDate >= $rsvpDate)
		{
			$UPDATE_SIGN_UP = "	UPDATE 	ef_attendance 
								SET 	confidence = " . $conf . " ,
								rsvp_time = \"".$date."\"
								WHERE 	event_id = " . $event->eid . " 
								AND 	user_id = " . $uid;
			return $this->executeUpdateQuery($UPDATE_SIGN_UP);
		}
	}
	public function m_hasAttend($uid, $eid) {
		$HAS_ATTEND = "	SELECT	* 
						FROM	ef_attendance a 
						WHERE	a.event_id = " . $eid . " 
						AND		a.user_id = " . $uid . " AND a.confidence <> ".CONFELSE;
		if ($this->getRowNum($HAS_ATTEND) > 0) {
			return $this->executeValidQuery($HAS_ATTEND);
		}
		return NULL;
	}
	public function m_getEventByEO($uid)
	{
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
									e.location_lat,
									e.location_long,
									e.event_datetime, 
									e.event_end_datetime,
									e.event_deadline, 
									e.description, 
									e.is_public,
									e.type,
									e.twitter
							FROM	ef_events e 
							WHERE	e.organizer = " . $uid . " AND e.is_active = 1 AND e.is_public = 1
						) el
						ORDER BY el.days_left ASC";
		return $this->getQueryResultAssoc($GET_EVENTS);
	}
}