<?php
/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */
class EFTwitter {
	private $dbCon;
	private $PREFIX = "#truersvp";
	
	public function __construct() {
		$this->dbCon = new DBConfig();
	}
	
	public function __destruct() {
		
	}
	
	/**
	 * Generating the Twitter hash tag given the event ID
	 * $eid Integer the event ID
	 * return String
	 */
	public function getTwitterHash($eid) {
		$eventInfo = EFCommon::$dbCon->getEventInfo($eid);
		return $this->PREFIX.$eventInfo['url_alias'];
	}
}