<?php
/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */

class Contact {
	/* Contact name is first name and last name (i.e. fname." ".lname) */
	public $name;
	
	/* Contact ID can be email or FB ID */
	public $cid;
	public $friendly_cid;
	public $is_email = false;
	
	public $pic;
		
	public function __construct($name, $cid) {
		$this->name = $name;
		$this->cid = $cid;
		
		if ( filter_var($this->cid, FILTER_VALIDATE_EMAIL) ) {
			$this->pic = $this->getUserPic();
			$this->friendly_cid = $this->cid;
			$this->is_email = true;
		} else {
			$this->pic = "http://graph.facebook.com/" . $this->cid . "/picture";
			$this->friendly_cid = "Facebook contact";
		}
	}
	
	public function __destruct() {
	
	}
	
	private function getUserPic() {
		$userInfo = EFCommon::$dbCon->getUserInfoByEmail($this->cid);
		if ( file_exists(realpath(dirname(__FILE__))."/../upload/user/" . $userInfo['id'] . ".png") ) {
			return CURHOST . "/upload/user/" . $userInfo['id'] . ".png";
		} else if ( file_exists(realpath(dirname(__FILE__))."/../upload/user/" . $userInfo['id'] . ".jpg") ) {
			return CURHOST . "/upload/user/" . $userInfo['id'] . ".jpg";
		} else {
			return CURHOST . "/images/default_thumb.jpg";
		}
	}
}