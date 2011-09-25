<?php
/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */
class EFCore {
	
	public function __construct() {

	}
	
	public function __destruct() {
		
	}
	
	public function computeGuestimate($eid) {
		$numGuests = EFCommon::$dbCon->getNumAttendeesByConfidence($eid, CONFELSE);;
		$numGuests = $numGuests['guest_num'];
		
		$baseNum = 0;
		if ($numGuests >= 0 && $numGuests <= 20) {
			$baseNum = mt_rand(GUESTRANGE1MIN, GUESTRANGE1MAX);
		} else if ($numGuests >= 21 && $numGuests <= 50) {
			$baseNum = mt_rand(GUESTRANGE2MIN, GUESTRANGE2MAX);
		} else {
			$baseNum = mt_rand(GUESTRANGE3MIN, GUESTRANGE3MAX);
		}
		
		$guestimate = ($numGuests * EFCommon::toPercent($baseNum)) + $this->computeGuestimateTRSVP($eid);
		
		return round($guestimate, 0);
	}
	
	// Guestimate TrueRSVP does not include the probability of the guests
	// Those are not responding to the invitation
	public function computeGuestimateTRSVP($eid) {
		$numGuestConf1 = EFCommon::$dbCon->getNumAttendeesByConfidence($eid, CONFOPT1);
		$numGuestConf2 = EFCommon::$dbCon->getNumAttendeesByConfidence($eid, CONFOPT2);
		$numGuestConf3 = EFCommon::$dbCon->getNumAttendeesByConfidence($eid, CONFOPT3);
		$numGuestConf4 = EFCommon::$dbCon->getNumAttendeesByConfidence($eid, CONFOPT4);
		$numGuestConf5 = EFCommon::$dbCon->getNumAttendeesByConfidence($eid, CONFOPT5);
		$numGuestConf6 = EFCommon::$dbCon->getNumAttendeesByConfidence($eid, CONFOPT6);
		
		$trsvpConf1Val = EFCommon::toPercent(CONFOPT1) * $numGuestConf1['guest_num'];
		$trsvpConf2Val = EFCommon::toPercent(CONFOPT2) * $numGuestConf2['guest_num'];
		$trsvpConf3Val = EFCommon::toPercent(CONFOPT3) * $numGuestConf3['guest_num'];
		$trsvpConf4Val = EFCommon::toPercent(CONFOPT4) * $numGuestConf4['guest_num'];
		$trsvpConf5Val = EFCommon::toPercent(CONFOPT5) * $numGuestConf5['guest_num'];
		$trsvpConf6Val = EFCommon::toPercent(CONFOPT6) * $numGuestConf6['guest_num'];
		
		$trsvpVal = $trsvpConf1Val + $trsvpConf2Val + $trsvpConf3Val + 
								$trsvpConf4Val + $trsvpConf5Val + $trsvpConf6Val;
		return round($trsvpVal, 0);
	}
	
	/**
	 * Get the trueRSVP number V1
	 * @param  $event   Event    the event object
	 *
	 * @return Integer
	 * DO NOT REMOVE, THIS IS JUST FOR DOCUMENTATION PURPOSE
	 */
	public function computeTrueRSVP($eid) {
		$numGuestConf1 = EFCommon::$dbCon->getNumAttendeesByConfidence($eid, CONFOPT1);
		$numGuestConf2 = EFCommon::$dbCon->getNumAttendeesByConfidence($eid, CONFOPT2);
		$numGuestConf3 = EFCommon::$dbCon->getNumAttendeesByConfidence($eid, CONFOPT3);
		$numGuestConf4 = EFCommon::$dbCon->getNumAttendeesByConfidence($eid, CONFOPT4);
		$numGuestConf5 = EFCommon::$dbCon->getNumAttendeesByConfidence($eid, CONFOPT5);
		$numGuestConf6 = EFCommon::$dbCon->getNumAttendeesByConfidence($eid, CONFOPT6);
		
		$numGuestNoResp = EFCommon::$dbCon->getNumAttendeesByConfidence($eid, CONFELSE);
		
		$trsvpConf1Val = EFCommon::toPercent(CONFOPT1) * $numGuestConf1['guest_num'];
		$trsvpConf2Val = EFCommon::toPercent(CONFOPT2) * $numGuestConf2['guest_num'];
		$trsvpConf3Val = EFCommon::toPercent(CONFOPT3) * $numGuestConf3['guest_num'];
		$trsvpConf4Val = EFCommon::toPercent(CONFOPT4) * $numGuestConf4['guest_num'];
		$trsvpConf5Val = EFCommon::toPercent(CONFOPT5) * $numGuestConf5['guest_num'];
		$trsvpConf6Val = EFCommon::toPercent(CONFOPT6) * $numGuestConf6['guest_num'];
		
		$trsvpGuestNoRespVal = EFCommon::toPercent(CONFELSE) * $numGuestNoResp['guest_num'];
		
		$trsvpVal = $trsvpConf1Val + $trsvpConf2Val + $trsvpConf3Val + $trsvpConf4Val + $trsvpConf5Val + $trsvpConf6Val + $trsvpGuestNoRespVal;
		return round($trsvpVal, 0);
	}
	
	/**
	 * Get the trueRSVP number V2
	 * @param  $event   Event    the event object
	 *
	 * @return Integer
	 */
	public function getTrueRSVP($event) {
		$attendees = EFCommon::$dbCon->getAttendeesByEvent($event->eid);
		$goalProb = $this->getProbByGoal($event);
		$typeProb = $this->getProbByEventType($event);
		
		$result = 0;
		for ($i = 0; $i < sizeof($attendees); ++$i) {
			$attendee = new User($attendees[$i]);
			
			$attendeeProb = 0;
			
			if (!$attendee->get_errors()) {
				$attendeeProb += ($this->getProbByDistance($attendee, $event) * 0.1);
				$attendeeProb += ($this->getProbByFriends($attendee, $event) * 0.1);
				$attendeeProb += ($this->getProbByUserReputation($attendee) * 0.1);
				$attendeeProb += ($this->getUserProb($attendee, $event) * 0.5);
			}
			$attendeeProb += ($goalProb * 0.1);
			$attendeeProb += ($typeProb * 0.1);
			
			$result += $attendeeProb;
		}
		
		return round($result);
	}
	
	/**
	 * Get the user confidence in decimal
	 *
	 * @param $user  User  user that we want to find confidence on
	 * @param $event Event the event that the user confidence on
	 *
	 * @return Double the probability in decimal
	 */
	private function getUserProb(&$user, &$event) {
		$GET_USER_PROB = "SELECT * FROM ef_attendance a WHERE a.user_id = ".$user->id." AND a.event_id = ".$event->eid;
		$user_prob = EFCommon::$dbCon->executeQuery($GET_USER_PROB);
		
		return floatval($user_prob['confidence']) / 100.0;
	}
	
	/**
	 * Get the probability based on distance
	 * 
	 * @param $user    User   the user who's going to attend the event
	 * @param $event   Event  the event that the user is going to attend
	 *
	 * @return Double the probability in decimal
	 */
	private function getProbByDistance(&$user, &$event) {
		$from = EFCommon::$google->geoGetCoords($user->zip);
		$to = EFCommon::$google->geoGetCoords($event->address);
		
		// Distance default by miles
		$distance = EFCommon::$google->geoGetDistance($from['lat'], $from['lon'], $to['lat'], $to['lon']);
		
		if ($distance > 0 && $distance <= 5) {
			return 1.0;
		}
		
		if ($distance > 5 && $distance <= 10) {
			return 0.9;
		}
		
		if ($distance > 10 && $distance <= 20) {
			return 0.8;
		}
		
		if ($distance > 20 && $distance <= 50) {
			return 0.7;
		}
		
		if ($distance > 50 && $distance <= 200) {
			return 0.6;
		}
		
		if ($distance > 200 && $distance <= 3000) {
			return 0.5;
		}
		
		return 0.4;
	}
	
	/**
	 * Get the number of friends who are going to the event
	 *
	 * @param &$user   User   the probbility of this user
	 * @param &$event  Event  the event that the user attend
	 *
	 * @return Double the probability in decimal  
	 */
	private function getProbByFriends(&$user, &$event) {
		$GET_NUM_FRIENDS = "SELECT COUNT(*) AS num_friends FROM ef_users u, ef_attendance a 
							  WHERE u.id = a.user_id AND a.event_id = ".$event->eid." AND u.facebook IN
							    (SELECT f.fb_id FROM fb_friends f WHERE f.user_id = ".$user->id.");";
		$num_friends = EFCommon::$dbCon->executeQuery($GET_NUM_FRIENDS);
		$num_friends = intval($num_friends['num_friends']);
		
		if ($num_friends >= 10) {
			return 1.0;
		}
		
		return ($num_friends * 0.1);
	}
	
	/**
	 * Get the probability based on the goal
	 *
	 * @param &$event  Event the reference to the event object
	 *
	 * @return Double the probability in decimal 
	 */
	private function getProbByGoal(&$event) {
		if ($event->goal > 0 && $event->goal <= 6) {
			return 1.0;
		}
		
		if ($event->goal > 6 && $event->goal <= 20) {
			return 0.8;
		}
		
		if ($event->goal > 20 && $event->goal <= 50) {
			return 0.6;
		}
		
		if ($event->goal > 50 && $event->goal <= 200) {
			return 0.4;
		}
		
		return 0.3;
	}
	
	private function getProbByEventType(&$event) {
		// Personal
		if ($event->type >= 1 && $event->type <= 6) {
			return 0.8;
		}
		
		// Educational
		if ($event->type >= 7 && $event->type <= 11) {
			return 0.7;
		}
		
		// Professional
		if ($event->type >= 12 && $event->type <= 16) {
			return 0.6;
		}
	}
	
	/**
	 * Reputation = # of events RSVP'ed / # of events attended
	 */
	public function getProbByUserReputation(&$user) {
		$COUNT_YES = "SELECT COUNT(*) AS cnt FROM ef_attendance a 
							WHERE a.user_id = ".$user->id." AND a.confidence BETWEEN ".CONFOPT2." AND ".CONFOPT1;
		$NUM_ATTEND = "SELECT COUNT(*) as cnt FROM ef_attendance a 
							WHERE a.is_attending = 1 AND a.user_id = ".$user->id;
		
		$YES = EFCommon::$dbCon->executeQuery($COUNT_YES);		
		$ATTENDED = EFCommon::$dbCon->executeQuery($NUM_ATTEND);
		
		$YES = floatval($YES['cnt']);
		$ATTENDED = floatval($ATTENDED['cnt']);
		
		return ($YES == 0) ? 0 : ($ATTENDED / $YES);
	}
	
	/**
	 * How fast the invited responded
	 */
	public function computeResponseTime($uid) {
		$RESPONSE_TIME = "SELECT AVG(resp.response_time) AS avg_resp FROM
							  (SELECT a.rsvp_time, i.invited, TIMESTAMPDIFF(SECOND, a.rsvp_time, i.invited) AS response_time 
							    FROM ef_attendance a, ef_users u, ef_event_invites i 
							    WHERE i.email_to = u.email AND i.event_id = a.event_id AND u.id = ".$uid.") resp";
		$TRESPONSE = EFCommon::$dbCon->executeQuery($RESPONSE_TIME);
		
		return floatval($TRESPONSE['avg_resp']);
	}
}