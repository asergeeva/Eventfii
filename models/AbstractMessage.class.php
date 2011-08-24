<?php
/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */
class AbstractMessage {
	public $message;
	public $time;
	public $date;
	public $recipient;
	
	public $error;
	public $numErrors;
	
	public function __construct($message, $time, $date, $recipient) {
		$this->message = $message;
		$this->time = $time;
		$this->date = $date;
		$this->recipient = $recipient;
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
		$this->check_message();
		
		if ($auto_reminder == 1) {
			$this->check_time();
			$this->check_date();
		}
		
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
	private function check_message() {
		$this->message = stripslashes($this->message);
		if( strlen($this->message) < 5 ) {
			$this->error['message'] = "Message must be at least 5 characters";
			$this->numErrors++;
		}
	}
	
	/* check_time
	 * Checks the event's time
	 *
	 * Requirements:
	 *  - 12 hour time format
	 */
	private function check_time() {	
		$valid_time = filter_var(
			$this->time, 
			FILTER_VALIDATE_REGEXP, 
			array(
				"options" => array(
					"regexp" => "/^((0?[1-9]|1[012])(:[0-5]\d){0,2}(\ [AP]M))$|^([01]\d|2[0-3])(:[0-5]\d){0,2}$/"
				)
			)
		);
		
		if( ! $valid_time ) {
			$this->error['time'] = "Please enter a time in 12 hour clock (12:30 PM) format.";
			$this->numErrors++;
		}
	}
	
	/* check_date
	 * Checks the event's date
	 *
	 * Requirements:
	 *  - Valid date
	 *  - Date in the future
	 */
	private function check_date() {
		if ( strlen($this->date) == 0) {
			$this->date = NULL;
			$this->error['date'] = "Please enter a date for your event";
			$this->numErrors++;
			return;
		}
		
		$event_date = explode('/', $this->date);
		$month = $event_date[0];
		$day = $event_date[1];
		$year = $event_date[2]; 

		// Make sure date is valid
		if( !@checkdate($month, $day, $year) ) {
			$this->error['date'] = "Please enter a valid date in mm/dd/yyyy format";
			$this->numErrors++;
			return;
		}

		// Make sure date is in the future
		$check = @mktime(0, 0, 0, $month, $day, $year,-1);
		$today = @mktime(0, 0, 0, date("m"), date("d"), date("y"), -1);
		if( $check < $today ) {
			$this->error['date'] = "Event date should be a date in the future.";
			$this->numErrors++;
		}
	}
	
	/**
	 * Printing all of the errors
	 */
	public function print_errors() {
		foreach ($this->error as $key => $val) {
			print($val."<br />");
		}
	}
}