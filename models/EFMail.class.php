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
	private $dbConnection;
	const TPL_PATH = "/../templates/email/";

	private $FROM = "trueRSVP <hello@truersvp.com>";
	private $templates = array(
		"welcome" 			=> "welcome_userPOV.html",
		"confirm_email" 	=> "confirmemail_userPOV.html", //- not yet, we never confirm email, yet
		"contact_us" 		=> "contactus_userPOV.html", //-not yet
		"daily_summary" 	=> "dailysummary_eventcreatorPOV.html", //- cron - not yet
		"attendance_check" 	=> "didyoushowup_attendeePOV.html",  //- cron - not yet
		"hosts_following" 	=> "eventplannersyouarefollowing.html", //- not yet
		"follow_up" 		=> "followupemail_dayafter_attendeePOV.html",
		"invite" 			=> "inviteemail.html",
		"invite_image" 			=> "inviteemail_image.html",
		"friends_attending" => "notificationwhenfriendsRSVP_attendeePOV.html", //- not yet
		"forgot_pw" 		=> "recoverpassword_userPOV.html", //- we don't use this, yet
		"reminder_4days" 	=> "reminder_4daysbefore_eventcreator.html",
 		"reminder_after" 	=> "reminder_after_eventcreator.html",
		"reminder_dayof" 	=> "reminder_dayof_eventcreator.html",
		"reminder_attendee" => "reminderemail_24hrsaway_attendeePOV.html",
		"thankyou_RSVP" 	=> "thankyouforRSVPing_attendeePOV.html",
		"general" 			=> "general.html",
		"forgot_pass" 		=> "forgot_pass.html",
		"cancel" 			=> "canceledevent.html"
	);
	
	public function __construct() {
		mailgun_init('key-43033qlv70665f8rxn9l6zld10jkgrs1');
		$this->dbConnection = new DBConfig();
	}
	
	public function __destruct() {
		
	}
	
	public function mapText($text, $eid) {
		$GET_EVENT_INFO = "	SELECT 	u.fname, e.organizer, e.title, DATE_FORMAT(e.event_datetime, '%W %M %Y %r') AS datetime 
							FROM 	ef_attendance a, ef_users u, ef_events e 
							WHERE 	a.user_id = u.id AND a.event_id = e.id AND a.event_id = " . $eid;
		
		$mapEventInfo = EFCommon::$dbCon->executeQuery($GET_EVENT_INFO);
		$hostInfo = EFCommon::$dbCon->getUserInfo($mapEventInfo['organizer']);
		for ($i = 0; $i < sizeof(EFCommon::$efDict); ++$i) {
			switch (EFCommon::$efDict[$i]) {
				case "{Guest name}":
					$text = str_replace(EFCommon::$efDict[$i], $mapEventInfo['fname'], $text);
					break;
				case "{Host name}":
					$text = str_replace(EFCommon::$efDict[$i], $hostInfo['fname'], $text);
					break;
				case "{Event name}":
					$text = str_replace(EFCommon::$efDict[$i], $mapEventInfo['title'], $text);
					break;
				case "{Event time}":
					$text = str_replace(EFCommon::$efDict[$i], $mapEventInfo['datetime'], $text);
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
		$imageExist = false;
		for ($j = 0; $j < $replaceItems->length; ++$j) {
			switch ($replaceItems->item($j)->getAttribute("id")) {
				case "event_name":
					$replaceItems->item($j)->nodeValue = stripslashes($event->title);
					$replaceItems->item($j)->parentNode->setAttribute("href", EVENT_URL."/a/".$event->alias.$reference);
					break;
				case "event_date":
					if (isset($event->friendly_date) && trim($event->friendly_date) != "") {
						$replaceItems->item($j)->nodeValue = $event->friendly_date;
					} else {
						$replaceItems->item($j)->nodeValue = $event->date;
					}
					break;
				case "event_time":
					if (isset($event->friendly_time) && trim($event->friendly_time) != "") {
						$replaceItems->item($j)->nodeValue = $event->friendly_time;
					} else {
						$replaceItems->item($j)->nodeValue = $event->time;
					}
					break;
				case "invite_image":
					if($event->image != NULL)
					{
						$replaceItems->item($j)->nodeValue = '<img src="'.CURHOST.'/upload/events/'.$event->image.'" />';
					}
				break;
				case "event_location":
					$replaceItems->item($j)->nodeValue = $event->address;
					break;
				case "event_link":
					$replaceItems->item($j)->parentNode->setAttribute("href", EVENT_URL."/a/".$event->alias.$reference);
					break;
				case "event_link_actual":
					$replaceItems->item($j)->nodeValue = EVENT_URL."/a/".$event->alias.$reference;
					break;
				case "event_description":
					$replaceItems->item($j)->nodeValue = $event->description;
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
					$replaceItems->item($j)->nodeValue = $efcore->getTrueRSVP($event->eid);
					break;
				case "event_host":
					$replaceItems->item($j)->nodeValue = $event->organizer->fname;
					break;
				case "event_custom_invite":
					if(file_exists('./upload/events/'.$event->image))
					{
						$imageExist = true;
						list($awidth, $aheight) = getimagesize('./upload/events/'.$event->image);
						$replaceItems->item($j)->firstChild->setAttribute("src", CURHOST."/upload/events/".$event->image);
						if($awidth > 531)
						{
							$replaceItems->item($j)->firstChild->setAttribute("width", '531');	
						}
					}else
					{
						$imageExist = false;
					}
					break;
				case "event_rsvp_days":
					$date1 = date('Y-m-d');
					$date2 = $event->deadline;
					$diff = abs(strtotime($date2) - strtotime($date1));
					$years = floor($diff / (365*60*60*24));
					$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
					$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
					$replaceItems->item($j)->nodeValue = $days;
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
	 * $message       String       the message that will be embedded - Optional
	 */
	public function mapGuestHtml(&$htmlEmail, &$guest, $message = NULL, $event_id=0) {
		$replaceItems = $htmlEmail->getElementsByTagName("span");
		for ($j = 0; $j < $replaceItems->length; ++$j) {
			switch ($replaceItems->item($j)->getAttribute("id")) {
				case "guest_name":
					$replaceItems->item($j)->nodeValue = $guest->fname;
					break;
				case "evevt_subject":
						if(!(isset($_SESSION['user']->id) && $_SESSION['user']->id==$guest->id))
						{
							$sessionUser = isset($_SESSION['user']->fname)?$_SESSION['user']->fname:"";
							$replaceItems->item($j)->nodeValue = stripslashes($sessionUser." RSVP'd you to:");				
						}
					break;	
				case "guest_rate":
					$act_confidence = '';
					$confidence = EFCommon::$dbCon->getGuestConfidence($guest->id, $event_id);
					switch($confidence)
					{
						case "90":
							$act_confidence = 'Absolutely';
							break;
						case "65":
							$act_confidence = 'Pretty sure';
							break;
						case "35":
							$act_confidence = '50/50';
							break;
						case "15":
							$act_confidence = 'Not likely';
							break;
						case "4":
							$act_confidence = 'Raincheck';
							break;
					}
					$replaceItems->item($j)->nodeValue = $act_confidence;
					break;
				case "message":
					if (isset($message)) {
						$replaceItems->item($j)->nodeValue = stripslashes($message);
					}
					break;
			}
		}
	}
	
	/**
	 * $template  String   the template
	 * $guest     User     the guest in user object
	 * $subject   String   the subject of the email
	 * $event     Event    the event object - Optional
	 * $content   String   the content of the email - Optional
	 * We don't need transactions
	 */
	public function sendHtmlEmail($template, $guest, $subject, $event = NULL, $content = NULL) {
		$htmlStr = file_get_contents(realpath(dirname(__FILE__)).EFMail::TPL_PATH.$this->templates[$template]);
		$htmlStr = str_replace('images', CURHOST.'/images/templates', $htmlStr);
		
		$htmlEmail = new DOMDocument();	
		$htmlEmail->loadXML($htmlStr);
		
		$this->mapGuestHtml($htmlEmail, $guest, $content);
		$this->mapEventHtml($htmlEmail, $event);
		
		if (isset($event)) {
			$subject = EFCommon::mapText($subject, $event, $guest);
			$this->mapEventHtml($htmlEmail, $event);
		}
		
		$rawMime = 
		    "X-Priority: 1 (Highest)\n".
		    "X-Mailgun-Tag: truersvp\n".
		    "Content-Type: text/html;charset=UTF-8\n".    
		    "From: ".$this->FROM."\n".
		    "To: ".$guest->email."\n".
		    "Subject: ".stripslashes(EFCommon::mapText($subject, $event, $guest))."\n".
		    "\n".$htmlEmail->saveXML();
		
		if (ENABLE_EMAIL) {
			MailgunMessage::send_raw($this->FROM, $guest->email, $rawMime);
		}
	}
	
	/**
	 * $template  String   the template
	 * $event     Event    the event
	 * $subject   String   the subject of the email
	 * We don't need transactions
	 */
	public function sendGuestsHtmlEmailByEvent($template, $event, $subject) {
		$htmlStr = file_get_contents(realpath(dirname(__FILE__)).EFMail::TPL_PATH.$this->templates[$template]);
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
			    "Subject: ".stripslashes(EFCommon::mapText($subject, $event, $event->guests[$i]))."\n".
			    "\n".$htmlEmail->saveXML();
			
			if (ENABLE_EMAIL) {
				MailgunMessage::send_raw($this->FROM, $event->guests[$i]->email, $rawMime);
			}
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
		$htmlStr = file_get_contents(realpath(dirname(__FILE__)).EFMail::TPL_PATH.$this->templates[$template]);
		$htmlStr = str_replace('images', CURHOST.'/images/templates', $htmlStr);
		
		$htmlEmail = new DOMDocument();	
		$htmlEmail->loadXML($htmlStr);
		
		$this->mapEventHtml($htmlEmail, $event);
		$this->mapGuestHtml($htmlEmail, $guest, NULL, $event->eid);
		$this->mapEventGuestHtml($event, $guest, $htmlEmail);
	
		$rawMime = 
		    "X-Priority: 1 (Highest)\n".
		    "X-Mailgun-Tag: truersvp\n".
		    "Content-Type: text/html;charset=UTF-8\n".    
		    "From: ".$this->FROM."\n".
		    "To: ".$guest->email."\n".
		    "Subject: ".stripslashes(EFCommon::mapText($subject, $event, $guest))."\n".
		    "\n".$htmlEmail->saveXML();
		
		if (ENABLE_EMAIL) {
			MailgunMessage::send_raw($this->FROM, $guest->email, $rawMime);
		}
	}
	
	/**
	 * $event     Event  the event object
	 * We don't need transactions
	 */
	public function sendHtmlInvite($event, $newGuests) {
		$event->image = $this->dbConnection->getEventImage($event->eid);
		if($event->image != NULL && $event->image != '')
		{
			$htmlStr = file_get_contents(realpath(dirname(__FILE__)).EFMail::TPL_PATH.$this->templates['invite_image']);
		}else
		{
			$htmlStr = file_get_contents(realpath(dirname(__FILE__)).EFMail::TPL_PATH.$this->templates['invite']);
		}
		$htmlStr = str_replace('images', CURHOST.'/images/templates', $htmlStr);
		
		$htmlEmail = new DOMDocument();	
		$htmlEmail->loadXML($htmlStr);
		
		for ($i = 0; $i < sizeof($newGuests); ++$i) {
			if ($newGuests[$i] != $_SESSION['user']->email) {
				if (trim($newGuests[$i]) !== "") {		
					$hash_key = md5($newGuests[$i]."-".$event->eid ."-". time());
							
					$insertedUser = EFCommon::$dbCon->getUserInfoByEmail($newGuests[$i]);
					
					$this->mapEventHtml($htmlEmail, $event, "?gref=".$event->global_ref."&eref=".$hash_key);	
					$htmlEmail->saveXML();
					$RECORD_HASH_KEY = "INSERT IGNORE INTO ef_event_invites (hash_key, email_to, event_id) 
										VALUES ('" . mysql_real_escape_string($hash_key) . "', 
												'" . mysql_real_escape_string($newGuests[$i]) . "', 
												 " . $event->eid . ")";
					EFCommon::$dbCon->executeUpdateQuery($RECORD_HASH_KEY);
					EFCommon::$dbCon->recordUnconfirmedAttendance($event, $insertedUser['id']);
					
					$RECORD_CONTACT = "	INSERT IGNORE INTO ef_addressbook (user_id, contact_id, contact_email) 
										VALUES (" . $_SESSION['user']->id . ", 
												" . $insertedUser['id'] . ", 
											   '".mysql_real_escape_string($insertedUser['email'])."')";
					EFCommon::$dbCon->executeUpdateQuery($RECORD_CONTACT);
					
					$rawMime = 
					    "X-Priority: 1 (Highest)\n".
					    "X-Mailgun-Tag: truersvp\n".
					    "Content-Type: text/html;charset=UTF-8\n".    
					    "From: ".$event->organizer->fname." ".$event->organizer->lname." <".$event->organizer->email.">\n".
					    "To: ".$newGuests[$i]."\n".
					    "Subject: ".stripslashes($event->organizer->fname)." invited you to ".stripslashes($event->title)."\n".
					    "\n".$htmlEmail->saveXML();
					
					if (ENABLE_EMAIL) {
						MailgunMessage::send_raw($this->FROM, $newGuests[$i], $rawMime);
					}
				}
			}
		}
	}
	
	/**
	 * Sending the reset password link to the user
	 */
	public function sendResetPassLink($uriPath, $hash_key, $user_email) {
		$this->sendHtmlEmail('general', 
							  $user_email, 
							  "Reset Password", 
							  NULL, 
							  "This is the link to reset your password: ".CURHOST.$uriPath."?ref=".$hash_key);
	}
	
	public function sendFeedback() {
		//declare our assets 
		$name = stripcslashes($_POST['name']);
		$emailAddr = trim(stripcslashes($_POST['email'])) ? '' : $this->FROM;
		$comment = stripcslashes($_POST['message']);
		$contactMessage =  
			"$comment 
	
			Name: $name
			E-mail: $emailAddr
	
			Sending IP:$_SERVER[REMOTE_ADDR]
			Sending Script: $_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]";
		
		//send the email 
		$rawMime = "X-Mailgun-Tag: truersvp\n" . 
				   "Content-Type: plaintext;charset=UTF-8\n" . 
				   "From: " . $name . "<" . $emailAddr . ">\n" . 
				   "To: support@truersvp.com\n" . 
				   "Subject: [trueRSVP Feedback] \n\n". $contactMessage;
		MailgunMessage::send_raw($emailAddr, 'support@truersvp.com', $rawMime);
		echo('Success'); //return success callback
	}
	
	/**
	 * Check for the valid RFC Email if it is valid
	 * @return String of the email if it's valid, false otherwise
	 */
	public static function getRFCEmail($email) {
		if (self::validateEmail($email)) {
			$address_array  = imap_rfc822_parse_adrlist($email, $_SERVER['HTTP_HOST']);
			
			foreach ($address_array as $id => $val) {
				return $val->mailbox . "@" . $val->host;
			}
		}
		return false;
	}
	
	/**
	 * Validate one or multiple separated by comma of email addresses that follows the RFC 2822 specifications
	 * imap_rfc822_parse_adrlist
	 * 		Input: foo@bar.com
	 * 		Output: Array ( [0] => stdClass Object ( [mailbox] => foo [host] => bar.com ) )
	 */
	public static function validateEmail($email) {
		$address_array  = imap_rfc822_parse_adrlist($email, $_SERVER['HTTP_HOST']);
		if (!is_array($address_array) || count($address_array) < 1) {
		    return false;
		}
		
		foreach ($address_array as $id => $val) {
			if (!isset($val->mailbox) || trim($val->mailbox) == "") {
				return false;
			}
			
			if (!isset($val->host) || trim($val->host) == "") {
				return false;
			}
			
			$emailVal = $val->mailbox . "@" . $val->host;
			if (!filter_var($emailVal, FILTER_VALIDATE_EMAIL)) {
				return false;
			}
		}
		return true;
	}
}