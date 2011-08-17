<?php
/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */

class AbstractUser {
	public $email;
	
	public function __construct($email) {
		$this->email = $email;
	}
	
	protected function check_email() {
 		$valid_email = filter_var(
	 		$this->email, 
	 		FILTER_VALIDATE_EMAIL
	 	);
	 	
		if( ! $valid_email ) {
			$this->error['email'] = "Invalid email";
			$this->numErrors++;
		}	
	}
}