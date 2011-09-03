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
										e.created, 
										e.organizer,
										e.title, 
										e.goal, 
										e.location_address, 
										e.location_lat,
										e.location_long,
										e.event_datetime, 
										e.event_deadline, 
										e.twitter,
										e.description, e.is_public 
								FROM 	ef_attendance a, 
										ef_events e 
								WHERE 	a.event_id = e.id AND a.user_id = ".$uid."
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
		$GET_ATTENDEES = "	SELECT	* 
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
	
	public function m_checkInGuestWithDate($isAttend, $uid, $eid, $date) 
	{
		$CHECKIN_GUEST = "UPDATE ef_attendance a SET a.is_attending = ".$isAttend." , a.rsvp_time = ".$date."
												WHERE a.user_id = ".$uid." AND a.event_id = ".$eid."";
		$this->executeUpdateQuery($CHECKIN_GUEST);
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
	public function m_eventSignUp($uid, $event, $conf)
	{
		$UPDATE_SIGN_UP = "	UPDATE 	ef_attendance 
							SET 	confidence = " . $conf . " 
							WHERE 	event_id = " . $event->eid . " 
							AND 	user_id = " . $uid;
		return $this->executeUpdateQuery($UPDATE_SIGN_UP);
	}
}