<?php
/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
 
require_once(realpath(dirname(__FILE__)).'/../libs/Mailgun/Mailgun.php');
require_once(realpath(dirname(__FILE__)).'/../db/DBConfig.class.php');
require_once(realpath(dirname(__FILE__)).'/../models/User.class.php');

class EFMail {
	private $FROM = "'TrueRSVP' <hello@truersvp.com>";
	private $efmailDict = array(
		"{Guest name}",
		"{Host name}",
		"{Event name}",
		"{Event time}"
	);
	private $templates = array(
		"welcome_email" => "welcome.html",
		"send_invite" => "inviteemail.html"
		// ...
	);
	
	public function __construct() {
		mailgun_init('key-afy6amxoo2fnj$u@mc');
	}
	
	public function __destruct() {
		
	}
	
	public function mapText($text, $eid) {
		$GET_EVENT_INFO = "	SELECT 	u.fname, e.title, DATE_FORMAT(e.event_datetime, '%W %M %Y %r') AS datetime 
							FROM 	ef_attendance a, ef_users u, ef_events e 
							WHERE 	a.user_id = u.id AND a.event_id = e.id AND a.event_id = " . $eid;
		$mapEventInfo = EFCommon::$dbCon->executeQuery($GET_EVENT_INFO);
		$hostInfo = EFCommon::$dbCon->getUserInfo($mapInfo['organizer']);
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
		return $text;
	}
	
	/**
	 * $event     Event  the event object
	 * We don't need transactions
	 */
	public function sendHtmlInvite($event) {
		$htmlStr = file_get_contents(realpath(dirname(__FILE__))."/../templates/email/".$this->templates['send_invite']);
		$htmlStr = str_replace('images', CURHOST.'/images/templates', $htmlStr);
		
		$htmlEmail = new DOMDocument();	
		$htmlEmail->loadXML($htmlStr);
		
		$replaceItems = $htmlEmail->getElementsByTagName("span");
	
		for ($i = 0; $i < sizeof($event->guests); ++$i) {
			$hash_key = md5($event->guests[$i].$event->eid.time());
		
			$insertedUser = EFCommon::$dbCon->createNewUser( NULL, NULL, $event->guests[$i], NULL, NULL, NULL );
			
			for ($j = 0; $j < $replaceItems->length; ++$j) {
				switch ($replaceItems->item($j)->getAttribute("id")) {
					case "event_name":
						$replaceItems->item($j)->nodeValue = $event->title;
						$replaceItems->item($j)->parentNode->setAttribute("href", EVENT_URL."/".$event->eid."?ref=" . $hash_key);
						break;
					case "event_date":
						$replaceItems->item($j)->nodeValue = $event->date;
						break;
					case "event_location":
						$replaceItems->item($j)->nodeValue = $event->address;
						break;
					case "host_name":
						$replaceItems->item($j)->nodeValue = $_SESSION['user']->fname . " " . $_SESSION['user']->lname;
						break;
					case "host_email":
						$replaceItems->item($j)->nodeValue = $_SESSION['user']->email;
						break;
				}
			}
			
			$RECORD_HASH_KEY = "INSERT INTO ef_event_invites (hash_key, email_to, event_id) 
								VALUES ('" . $hash_key . "', '" . $event->guests[$i] . "', " . $event->eid . ")";
			EFCommon::$dbCon->executeUpdateQuery($RECORD_HASH_KEY);
			$RECORD_ATTEND_UNCONFO = "	INSERT IGNORE INTO ef_attendance (event_id, user_id) 
										VALUES (" . $event->eid . ", " . $insertedUser['id'] . ")";
			EFCommon::$dbCon->executeUpdateQuery($RECORD_ATTEND_UNCONFO);
			$RECORD_CONTACT = "	INSERT IGNORE INTO ef_addressbook (user_id, contact_id) 
								VALUES (" . $_SESSION['user']->id . ", " . $insertedUser['id'] . ")";
			EFCommon::$dbCon->executeUpdateQuery($RECORD_CONTACT);
		
			$rawMime = 
			    "X-Priority: 1 (Highest)\n".
			    "X-Mailgun-Tag: truersvp\n".
			    "Content-Type: text/html;charset=UTF-8\n".    
			    "From: ".$this->FROM."\n".
			    "To: ".$event->guests[$i]."\n".
			    "Subject: You are invited to ".$event->title."\n".
			    "\n".$htmlEmail->saveXML();
			    
			MailgunMessage::send_raw($this->FROM, $event->guests[$i], $rawMime);
		}
	}
	
	/**
	 * $attendees Array  list of all attendees
	 * $eventInfo Object the Event object from the DBMS
	 */
	public function sendAutomatedEmail($eventInfo, $content, $subject, $attendees) {
		$message = $content . "\r\nLink: " . EVENT_URL . "/" . $eventInfo->eid;
		for ($i = 0; $i < sizeof($attendees); ++$i) {
			MailgunMessage::send_text($this->FROM, $attendees[$i]['email'], $subject, $this->mapText($message, $eventInfo->eid));
		}
	}
	
	public function sendResetPassLink($uriPath, $hash_key, $email) {
		$subject = "Reset Password";
		
		$message = "To reset password: " . CURHOST . $uriPath . "?ref=" . $hash_key;
		MailgunMessage::send_text($this->FROM, $email, $subject, $message);
	}
}