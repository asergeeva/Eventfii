<?php
/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */
require_once(realpath(dirname(__FILE__)).'/AbstractMessage.class.php');

class EFEmailMessage extends AbstractMessage {
	public $subject;
	
	public function __construct($subject, $message, $time, $date, $recipient) {
		$this->subject = $subject;
		parent::__construct($message, $time, $date, $recipient);
	}
	
	public function __destruct() {
	
	}
	
	/* get_errors
	 * Makes akes sure that all event fields
	 * are filled and valid.
	 *
	 * @param $auto_reminder  Integer whether the auto reminder is checked or not (1 or 0)
	 *
	 * return $errors | If there are errors
	 * return false | If there are no errors
	 */
	public function get_errors($auto_reminder) {
		// Reset
		$this->numErrors = 0;
		unset($this->error);
		
		// Check for errors
		parent::get_errors($auto_reminder);
		$this->check_subject();
		
		// Return if there are any errors
		if ( $this->numErrors == 0 )
			return false;
		else
			return $this->error;
	}
	
	/* check_message
	 * Checks the message
	 *
	 * Requirements:
	 *  - Only alphanumeric characters
	 *  - 10-500 characters
	 */
	private function check_subject() {
		$this->subject = stripslashes($this->subject);
		if( strlen($this->subject) < 5 ) {
			$this->error['subject'] = "Message must be at least 5 characters";
			$this->numErrors++;
		}
	}
}