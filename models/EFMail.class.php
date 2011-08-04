<?php
/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
 
require_once(realpath(dirname(__FILE__)).'/../libs/Mailgun/Mailgun.php');
require_once(realpath(dirname(__FILE__)).'/../db/DBConfig.class.php');

class EFMail {
	private $FROM = "hello@truersvp.com";
	private $dbCon;
	private $efmailDict = array(
		"{Guest name}",
		"{Host name}",
		"{Event name}",
		"{Event time}"
	);
	
	public function __construct() {
		mailgun_init('key-afy6amxoo2fnj$u@mc');
		$this->dbCon = new DBConfig();
	}
	
	public function __destruct() {
		
	}
	
	public function mapText($text, $eid) {
		$GET_EVENT_INFO = "SELECT u.fname, e.title, DATE_FORMAT(e.event_datetime, '%W %M %Y %r') AS datetime 
													FROM ef_attendance a, ef_users u, ef_events e 
													WHERE a.user_id = u.id AND a.event_id = e.id AND a.event_id = ".$eid;
		$mapEventInfo = $this->dbCon->executeQuery($GET_EVENT_INFO);
		$hostInfo = $this->dbCon->getUserInfo($mapInfo['organizer']);
		for ($i = 0; $i < sizeof($this->efmailDict); ++$i) {
			switch ($this->efmailDict[$i]) {
				case "{Guest name}":
					$text = str_replace($this->efmailDict[$i], $mapEventInfo['fname'], $text);
					break;
				case "{Host name}":
					$text = str_replace($this->efmailDict[$i], $hostInfo['fname'], $text);
					break;
				case "{Event name}":
					$text = str_replace($this->efmailDict[$i], $mapEventInfo['title'], $text);
					break;
				case "{Event time}":
					$text = str_replace($this->efmailDict[$i], $mapEventInfo['datetime'], $text);
					break;
			}
		}
		print($text."<br/>");
		return $text;
	}
	
	/**
	 * $to        Array  list of email addresses
	 * $eventName String title of the event
	 * $eventUrl  String url of the event
	 */
	public function sendEmail($to, $eventId, $eventName, $eventUrl) {
		$subject = "You are invited to ".$eventName;
		
		for ($i = 0; $i < sizeof($to); ++$i) {
			$hash_key = md5($to[$i].$eventId);
			$message = "Link: ".EVENT_URL."/".$eventInfo->eid."?ref=".$hash_key;
			$RECORD_HASH_KEY = "INSERT IGNORE INTO ef_event_invites (hash_key, email_to)
														VALUES ('".$hash_key."', '".$to[$i]."')";
			$this->dbCon->executeUpdateQuery($RECORD_HASH_KEY);
			MailgunMessage::send_text($this->FROM, $to[$i], $subject, $message);
		}
	}
	
	// We don't need transactions
	public function sendInvite($to, $eventId, $eventName, $eventUrl) {
		$subject = "You are invited to ".$eventName;
		
		for ($i = 0; $i < sizeof($to); ++$i) {
			$hash_key = md5($to[$i].$eventId);
			$message = "Link: ".$eventUrl."?ref=".$hash_key;
			$insertedUser = $this->dbCon->createNewUser(NULL, NULL, $to[$i], NULL, NULL, NULL);
			$userId = mysql_insert_id();
			
			if ($userId != 0) {
				$RECORD_HASH_KEY = "INSERT IGNORE INTO ef_event_invites (hash_key, email_to, event_id) 
															VALUES ('".$hash_key."', '".$to[$i]."', ".$eventId.")";
				$this->dbCon->executeUpdateQuery($RECORD_HASH_KEY);
				$RECORD_ATTEND_UNCONFO = "INSERT IGNORE INTO ef_attendance (event_id, user_id) 
																		VALUES (".$eventId.", ".$insertedUser['id'].")";
				$this->dbCon->executeUpdateQuery($RECORD_ATTEND_UNCONFO);
				$RECORD_CONTACT = "INSERT IGNORE INTO ef_addressbook (user_id, contact_id) 
															VALUES (".$_SESSION['uid'].", ".$insertedUser['id'].")";
				$this->dbCon->executeUpdateQuery($RECORD_CONTACT);
				MailgunMessage::send_text($this->FROM, $to[$i], $subject, $message);
			}
		}
	}
	
	/**
	 * $attendees Array  list of all attendees
	 * $eventInfo Object the Event object from the DBMS
	 */
	public function sendAutomatedEmail($eventInfo, $content, $subject, $attendees) {
		$message =  $content."\r\n".
								"Link: ".EVENT_URL."/".$eventInfo->eid;
		for ($i = 0; $i < sizeof($attendees); ++$i) {
			MailgunMessage::send_text($this->FROM, 
															  $attendees[$i]['email'], 
																$subject, 
																$this->mapText($message, $eventInfo->eid));
		}
	}
	
	public function sendResetPassLink($uriPath, $hash_key, $email) {
		$subject = "Reset Password";
		
		$message = "To reset password: ".CURHOST.$uriPath."?ref=".$hash_key;
		MailgunMessage::send_text($this->FROM, $email, $subject, $message);
	}
}