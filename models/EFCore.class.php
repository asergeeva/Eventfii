<?php
/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
require_once('db/DBConfig.class.php');
require_once('models/EFCommon.class.php');
 
class EFCore {
	private $dbCon;
	private $efCom;
	
	public function __construct() {
		$this->dbCon = new DBConfig();	
		$this->efCom = new EFCommon();
	}
	
	public function __destruct() {
		
	}
	
	public function computeGuestimate($eid) {
		$numGuests = $this->dbCon->getNumAttendeesByConfidence($eid, CONFELSE);;
		$numGuests = $numGuests['guest_num'];
		
		$baseNum = 0;
		if ($numGuests >= 0 && $numGuests <= 20) {
			$baseNum = mt_rand(GUESTRANGE1MIN, GUESTRANGE1MAX);
		} else if ($numGuests >= 21 && $numGuests <= 50) {
			$baseNum = mt_rand(GUESTRANGE2MIN, GUESTRANGE2MAX);
		} else {
			$baseNum = mt_rand(GUESTRANGE3MIN, GUESTRANGE3MAX);
		}
		
		$guestimate = ($numGuests * $this->efCom->toPercent($baseNum)) + $this->computeGuestimateTRSVP($eid);
		
		return round($guestimate, 0);
	}
	
	// Guestimate TrueRSVP does not include the probability of the guests
	// Those are not responding to the invitation
	public function computeGuestimateTRSVP($eid) {
		$numGuestConf1 = $this->dbCon->getNumAttendeesByConfidence($eid, CONFOPT1);
		$numGuestConf2 = $this->dbCon->getNumAttendeesByConfidence($eid, CONFOPT2);
		$numGuestConf3 = $this->dbCon->getNumAttendeesByConfidence($eid, CONFOPT3);
		$numGuestConf4 = $this->dbCon->getNumAttendeesByConfidence($eid, CONFOPT4);
		$numGuestConf5 = $this->dbCon->getNumAttendeesByConfidence($eid, CONFOPT5);
		$numGuestConf6 = $this->dbCon->getNumAttendeesByConfidence($eid, CONFOPT6);
		
		$trsvpConf1Val = $this->efCom->toPercent(CONFOPT1) * $numGuestConf1['guest_num'];
		$trsvpConf2Val = $this->efCom->toPercent(CONFOPT2) * $numGuestConf2['guest_num'];
		$trsvpConf3Val = $this->efCom->toPercent(CONFOPT3) * $numGuestConf3['guest_num'];
		$trsvpConf4Val = $this->efCom->toPercent(CONFOPT4) * $numGuestConf4['guest_num'];
		$trsvpConf5Val = $this->efCom->toPercent(CONFOPT5) * $numGuestConf5['guest_num'];
		$trsvpConf6Val = $this->efCom->toPercent(CONFOPT6) * $numGuestConf6['guest_num'];
		
		$trsvpVal = $trsvpConf1Val + $trsvpConf2Val + $trsvpConf3Val + 
								$trsvpConf4Val + $trsvpConf5Val + $trsvpConf6Val;
		return round($trsvpVal, 0);
	}
	
	public function computeTrueRSVP($eid) {
		$numGuestConf1 = $this->dbCon->getNumAttendeesByConfidence($eid, CONFOPT1);
		$numGuestConf2 = $this->dbCon->getNumAttendeesByConfidence($eid, CONFOPT2);
		$numGuestConf3 = $this->dbCon->getNumAttendeesByConfidence($eid, CONFOPT3);
		$numGuestConf4 = $this->dbCon->getNumAttendeesByConfidence($eid, CONFOPT4);
		$numGuestConf5 = $this->dbCon->getNumAttendeesByConfidence($eid, CONFOPT5);
		$numGuestConf6 = $this->dbCon->getNumAttendeesByConfidence($eid, CONFOPT6);
		
		$numGuestNoResp = $this->dbCon->getNumAttendeesByConfidence($eid, CONFELSE);
		
		$trsvpConf1Val = $this->efCom->toPercent(CONFOPT1) * $numGuestConf1['guest_num'];
		$trsvpConf2Val = $this->efCom->toPercent(CONFOPT2) * $numGuestConf2['guest_num'];
		$trsvpConf3Val = $this->efCom->toPercent(CONFOPT3) * $numGuestConf3['guest_num'];
		$trsvpConf4Val = $this->efCom->toPercent(CONFOPT4) * $numGuestConf4['guest_num'];
		$trsvpConf5Val = $this->efCom->toPercent(CONFOPT5) * $numGuestConf5['guest_num'];
		$trsvpConf6Val = $this->efCom->toPercent(CONFOPT6) * $numGuestConf6['guest_num'];
		
		$trsvpGuestNoRespVal = $this->efCom->toPercent(CONFELSE) * $numGuestNoResp['guest_num'];
		
		$trsvpVal = $trsvpConf1Val + $trsvpConf2Val + $trsvpConf3Val + 
								$trsvpConf4Val + $trsvpConf5Val + $trsvpConf6Val + 
								$trsvpGuestNoRespVal;
		return round($trsvpVal, 0);
	}
}