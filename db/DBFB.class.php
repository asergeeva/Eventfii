<?php
/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
require_once(realpath(dirname(__FILE__)).'/DBConfig.class.php');

class DBFB extends DBConfig {
	public function __construct() {
		
	}
	
	public function __destruct() {
		
	}
	
	public function getEventByRequestId($requestId) {
		$GET_EVENT = "SELECT
						DATEDIFF ( e.event_deadline, CURDATE() ) AS rsvp_days_left,
						DATEDIFF ( e.event_datetime, CURDATE() ) AS days_left,
						UNIX_TIMESTAMP(e.event_datetime) - UNIX_TIMESTAMP(NOW()) AS time_left,
						DATE_FORMAT(e.event_datetime, '%M %d, %Y') AS friendly_event_date,
						DATE_FORMAT(e.event_datetime, '%r') AS friendly_event_time, 
						e.* FROM fb_invited i, ef_events e 
						WHERE i.event_id = e.id AND i.request_id = '".mysql_real_escape_string($requestId)."'";
		return $this->executeQuery($GET_EVENT);
	}
}