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
require_once(realpath(dirname(__FILE__)).'/../models/EFCore.class.php');

class EFMail {
	private $FROM = "'trueRSVP' <hello@truersvp.com>";
	private $efmailDict = array(
		"{Guest name}",
		"{Host name}",
		"{Event name}",
		"{Event time}"
	);
	private $templates = array(
		"welcome" => "welcome_userPOV.html",
		"confirm_email" => "confirmemail_userPOV.html",
		"contact_us" => "contactus_userPOV.html",
		"daily_summary" => "dailysummary_eventcreatorPOV.html", //- cron
		"attendance_check" => "didyoushowup_attendeePOV.html",  //- cron
		"hosts_following" => "eventplannersyouarefollowing.html",
		"follow_up" => "followupemail_dayafter_attendeePOV.html",
		"invite" => "inviteemail.html",
		"friends_attending" => "notificationwhenfriendsRSVP_attendeePOV.html",
		"forgot_pw" => "recoverpassword_userPOV.html",
		"reminder_4days" => "reminder_4daysbefore_eventcreator.html",
 		"reminder_after" => "reminder_after_eventcreator.html",
		"reminder_dayof" => "reminder_dayof_eventcreator.html",
		"reminder_attendee" => "reminderemail_24hrsaway_attendeePOV.html",
		"thankyou_RSVP" => "thankyouforRSVPing_attendeePOV.html"
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
	 * $htmlEmail    DOMDocument  email template
	 * $event        Event        the event
	 * $reference    String       the reference of the link (e.g. ?eid=CODE) -- Optional
	 */
	public function mapEventHtml(&$htmlEmail, &$event, $reference = '') {
		$replaceItems = $htmlEmail->getElementsByTagName("span");

		for ($j = 0; $j < $replaceItems->length; ++$j) {
			switch ($replaceItems->item($j)->getAttribute("id")) {
				case "event_name":
					$replaceItems->item($j)->nodeValue = $event->title;
					$replaceItems->item($j)->parentNode->setAttribute("href", EVENT_URL."/".$event->eid.$reference);
					break;
				case "event_date":
					$replaceItems->item($j)->nodeValue = $event->date;
					break;
				case "event_time":
					$replaceItems->item($j)->nodeValue = $event->time;
					break;
				case "event_location":
					$replaceItems->item($j)->nodeValue = $event->address;
					break;
				case "event_link":
					$replaceItems->item($j)->parentNode->setAttribute("href", EVENT_URL."/".$event->eid.$reference);
					break;
				case "host_name":
					$replaceItems->item($j)->nodeValue = $event->organizer->fname . " " . $event->organizer->lname;
					break;
				case "host_email":
					$replaceItems->item($j)->nodeValue = $event->organizer->email;
					break;
				case "event_twitter_hashtag":
					$replaceItems->item($j)->nodeValue = "#truersvp".$event->eid;
					break;
				case "event_truersvp":
					$efcore = new EFCore();
					$replaceItems->item($j)->nodeValue = $efcore->computeTrueRSVP($event->eid);
					break;
			}
		}
	}
	
	/**
	 * $htmlEmail     DOMDocument  email template
	 * $guest         User         The recipient
	 * $event         Event        the event
	 */
	public function mapEventGuestHtml(&$event, &$guest, &$htmlEmail) {
		$replaceItems = $htmlEmail->getElementsByTagName("span");

		for ($j = 0; $j < $replaceItems->length; ++$j) {
			switch ($replaceItems->item($j)->getAttribute("id")) {
				case "event_qr":
					$replaceItems->item($j)->firstChild->setAttribute("src", $event->generateQR($guest->id));
					break;
			}
		}
	}
	
	/**
	 * $htmlEmail     DOMDocument  email template
	 * $guest         User         The recipient
	 */
	public function mapGuestHtml(&$htmlEmail, &$guest) {
		$replaceItems = $htmlEmail->getElementsByTagName("span");

		for ($j = 0; $j < $replaceItems->length; ++$j) {
			switch ($replaceItems->item($j)->getAttribute("id")) {
				case "guest_name":
					$replaceItems->item($j)->nodeValue = $guest->fname;
					break;
			}
		}
	}
	
	/**
	 * $template  String   the template
	 * $guest     User     the guest in user object
	 * $subject   String   the subject of the email
	 * We don't need transactions
	 */
	public function sendHtmlEmail($template, $guest, $subject) {
		$htmlStr = file_get_contents(realpath(dirname(__FILE__))."/../templates/email/".$this->templates[$template]);
		$htmlStr = str_replace('images', CURHOST.'/images/templates', $htmlStr);
		
		$htmlEmail = new DOMDocument();	
		$htmlEmail->loadXML($htmlStr);
		
		$rawMime = 
		    "X-Priority: 1 (Highest)\n".
		    "X-Mailgun-Tag: truersvp\n".
		    "Content-Type: text/html;charset=UTF-8\n".    
		    "From: ".$this->FROM."\n".
		    "To: ".$guest->email."\n".
		    "Subject: ".$subject."\n".
		    "\n".$htmlEmail->saveXML();
		
		MailgunMessage::send_raw($this->FROM, $guest->email, $rawMime);
	}
	
	/**
	 * $template  String   the template
	 * $event     Event    the event
	 * $subject   String   the subject of the email
	 * We don't need transactions
	 */
	public function sendGuestsHtmlEmailByEvent($template, $event, $subject) {
		$htmlStr = file_get_contents(realpath(dirname(__FILE__))."/../templates/email/".$this->templates[$template]);
		$htmlStr = str_replace('images', CURHOST.'/images/templates', $htmlStr);
		
		$htmlEmail = new DOMDocument();	
		$htmlEmail->loadXML($htmlStr);
		
		for ($i = 0; $i < sizeof($event->guests); ++$i) {
			$this->mapEventHtml($htmlEmail, $event);
			$this->mapGuestHtml($htmlEmail, $event->guests[$i]);
			$this->mapEventGuestHtml($event, $event->guests[$i], $htmlEmail);
			
			$rawMime = 
			    "X-Priority: 1 (Highest)\n".
			    "X-Mailgun-Tag: truersvp\n".
			    "Content-Type: text/html;charset=UTF-8\n".    
			    "From: ".$this->FROM."\n".
			    "To: ".$event->guests[$i]->email."\n".
			    "Subject: ".$subject."\n".
			    "\n".$htmlEmail->saveXML();
			
			MailgunMessage::send_raw($this->FROM, $event->guests[$i]->email, $rawMime);
		}
	}
	
	/**
	 * $template  String        the template
	 * $guest     AbstractUser  the user
	 * $event     Event         the event
	 * $subject   String        the subject of the email
	 * We don't need transactions
	 */
	public function sendAGuestHtmlEmailByEvent($template, $guest, $event, $subject) {
		$htmlStr = file_get_contents(realpath(dirname(__FILE__))."/../templates/email/".$this->templates[$template]);
		$htmlStr = str_replace('images', CURHOST.'/images/templates', $htmlStr);
		
		$htmlEmail = new DOMDocument();	
		$htmlEmail->loadXML($htmlStr);
		
		$this->mapEventHtml($htmlEmail, $event);
		$this->mapGuestHtml($htmlEmail, $guest);
		
		$rawMime = 
		    "X-Priority: 1 (Highest)\n".
		    "X-Mailgun-Tag: truersvp\n".
		    "Content-Type: text/html;charset=UTF-8\n".    
		    "From: ".$this->FROM."\n".
		    "To: ".$guest->email."\n".
		    "Subject: ".$subject."\n".
		    "\n".$htmlEmail->saveXML();
		
		MailgunMessage::send_raw($this->FROM, $guest->email, $rawMime);
	}
	
	/**
	 * $event     Event  the event object
	 * We don't need transactions
	 */
	public function sendHtmlInvite($event) {
		$htmlStr = file_get_contents(realpath(dirname(__FILE__))."/../templates/email/".$this->templates['invite']);
		$htmlStr = str_replace('images', CURHOST.'/images/templates', $htmlStr);
		
		$htmlEmail = new DOMDocument();	
		$htmlEmail->loadXML($htmlStr);
		
		
		for ($i = 0; $i < sizeof($event->guests); ++$i) {
			if (trim($event->guests[$i]->email) !== "") {		
				$hash_key = md5($event->guests[$i]->email."-".$event->eid ."-". time());
			
				
				$insertedUser = EFCommon::$dbCon->createNewUser( NULL, NULL, $event->guests[$i]->email, NULL, NULL, NULL );
				
				$this->mapEventHtml($htmlEmail, $event, "?ref=".$hash_key);
								
				$RECORD_HASH_KEY = "INSERT INTO ef_event_invites (hash_key, email_to, event_id) 
									VALUES ('" . $hash_key . "', '" . $event->guests[$i]->email . "', " . $event->eid . ")";
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
				    "To: ".$event->guests[$i]->email."\n".
				    "Subject: You are invited to ".$event->title."\n".
				    "\n".$htmlEmail->saveXML();
				
				MailgunMessage::send_raw($this->FROM, $event->guests[$i]->email, $rawMime);
			}
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