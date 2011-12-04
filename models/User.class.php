<?php
/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 
 */
require_once(realpath(dirname(__FILE__)).'/AbstractUser.class.php');

class User extends AbstractUser {
	public $verified;
	public $referrer;
	public $phone;
	public $zip;
	public $twitter;
	public $facebook;	
	public $notif_opt1;
	public $notif_opt2;
	public $notif_opt3;
	public $contacts = array();
	
	public $fb_access_token;
	public $fb_session_key;
	
	public function __construct($info) {
		// Settings page
		if ( $info === NULL ) {
			parent::__construct(NULL);
			$this->set_phone();
			$this->set_zip();
			$this->notif_opt1 = ( isset($_POST['notif_opt1']) && $_POST['notif_opt1'] == 1) ? 1 : 0;
			$this->notif_opt2 = ( isset($_POST['notif_opt2']) && $_POST['notif_opt2'] == 1 ) ? 1 : 0;		
			$this->notif_opt3 = ( isset($_POST['notif_opt3']) && $_POST['notif_opt3'] == 1 ) ? 1 : 0;
		} else {
			if ( ! is_array($info) ) {
				$info = EFCommon::$dbCon->getUserInfo($info);
			}
			$this->makeUserFromArray($info);
		}
		if ( $this->numErrors == 0 ) {
			if ( $info === NULL ) {
				$this->updateUser();
			}
			
			if (isset($_SESION['user'])) {
				$_SESSION['user'] == $this;
			}
		}
	}
	
	public function __destruct() {
		
	}
	
	protected function makeUserFromArray($userInfo) {
		parent::makeUserFromArray($userInfo);
		
		$this->set_phone($userInfo['phone']);
		$this->set_zip($userInfo['zip']);
		$this->notif_opt1 = $userInfo['notif_opt1'];
		$this->notif_opt2 = $userInfo['notif_opt2'];
		$this->notif_opt3 = $userInfo['notif_opt3'];
		
		$this->fb_access_token = $userInfo['fb_access_token'];
		$this->fb_session_key = $userInfo['fb_session_key'];	
	}
	
	public function updateUser() {
		if ( $this->numErrors == 0 ) {
			EFCommon::$dbCon->updateUserInfo( $this->fname, $this->lname, $this->phone, $this->zip, $this->notif_opt1, $this->notif_opt2, notif_opt3 );
			$_SESSION['user'] = $this;
		}
	}
	
	public function setContacts($contact_email) {
		$contacts = explode(",", $contact_email);
		
		// Reset the errors
		unset($this->error);
		$this->error["add_contact"] = "";
		$this->numErrors = 0;
		
		if ( sizeof($contacts) > 1 ) {
			foreach( $contacts as $contact ) {
				$contact = trim($contact);
				if ( filter_var($contact, FILTER_VALIDATE_EMAIL) ) {
					$addContacts[] = $contact;
				} else {
					$this->error["add_contact"] .= "<br />" . $contact;
					$this->numErrors++;
				}
			}
		} else {
			$addContacts[] = $contact_email;
		}
		
		if ( isset($addContacts) ) {
			EFCommon::$dbCon->storeContacts($addContacts, $_SESSION['user']->id);
			return sizeof($addContacts);
		} else {
			return 0;
		}
	}
	
	public function setContactsFromCSV($csvFile) {
		$csv_contacts = array();
		if (($handle = fopen($csvFile, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
				for ($i = 0; $i < sizeof($data); ++$i) {
					if (filter_var($data[$i], FILTER_VALIDATE_EMAIL)) {
						array_push($csv_contacts, $data[$i]);
						array_push($this->contacts, new AbstractUser($data[$i]));
					}
				}
			}
			fclose($handle);
		}
		EFCommon::$dbCon->storeContacts($csv_contacts, $_SESSION['user']->id);
	}
	
	public function addContacts() {
		$csvFile = USER_CSV_UPLOAD_PATH . '/' . $this->id . '.csv';
		
		$numContacts = 0;
		
		// text area check
		if (trim($_POST['emails']) != "") {
			$numContacts = $this->setContacts($_POST['emails']);		
		// CSV file check
		} else if (file_exists($csvFile)) {
			$this->setContactsFromCSV($csvFile);
		}
		
		if ( $numContacts == 0 ) {
			return "No guests added.";
		} else {
			$plural_contact = ($numContacts == 1) ? "contact" : "contacts";
			return $numContacts . " " . $plural_contact . " added successfully";
		}
	}
	
	public function set_phone($phone = NULL) {
		if ( $phone == NULL ) {
			if ( isset($_POST['phone']) ) {
				$phone = $_POST['phone']; 
			}
		}
		
		$this->phone = $phone;
		
		// Optional field
		if ( strlen($this->phone) == 0 )
			return;
		
		$valid_phone = filter_var(
	 		$this->phone, 
	 		FILTER_VALIDATE_REGEXP, 
	 		array(
	 			"options" => array(
	 				"regexp" => "/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/"
	 			)
	 		)
	 	);
	 	
		if( ! $valid_phone ) {
			$this->addError("phone", "Phone number is not in valid format.");
		}	
	}
	
	public function set_zip($zip = NULL) {
		if ( $zip == NULL ) {
			if ( isset($_POST['zip']) ) {
				$zip = $_POST['zip']; 
			}
		}
		
		$this->zip = $zip;
		
 		$valid_zip = filter_var(
	 		$this->zip, 
	 		FILTER_VALIDATE_REGEXP, 
	 		array(
	 			"options" => array(
	 				"regexp" => "/^\d{5}(-\d{4})?$/"
	 			)
	 		)
	 	);
	 	
		if( ! $valid_zip ) {
			$this->addError("zip", "Please enter a valid zip code.");
		}
	}
}