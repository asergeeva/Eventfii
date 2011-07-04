<?php
/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
class Event {
	public $organizer;
	public $title;
	public $url;
	public $goal;
	public $address;
	public $date;
	public $deadline;
	public $time;
	public $description;
	public $cost;
	public $is_public;
	public $type;
	public $lat;
	public $lng;
	
	public $eid;
	
	public $guests = array();
	
	function __construct($organizer,
											 $title,
											 $url,
											 $goal,
											 $address,
											 $date,
											 $time,
											 $deadline,
											 $description,
											 $cost,
											 $is_public,
											 $type,
											 $lat=0,
											 $lng=0) {
		$this->organizer = $organizer;
		$this->title = $title;
		$this->url = $url;
		$this->goal = $goal;
		$this->address = $address;
		$this->date = $date;
		$this->time = $time.":00";
		$this->deadline = $deadline;
		$this->description = $description;
		$this->cost = $cost;
		$this->is_public = $is_public;
		$this->gets = $gets;
		$this->type = $type;
		$this->lat = $lat;
		$this->lng = $lng;
	}
	
	function __destruct() {
		
	}
	
	public function setGuests($guest_email) {
		$this->guests = array_map('trim', explode(",", $guest_email));
	}
	
	public function setGuestsFromCSV($csvFile) {
		if (($handle = fopen($csvFile, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
				for ($i = 0; $i < sizeof($data); ++$i) {
					if (filter_var($data[$i], FILTER_VALIDATE_EMAIL)) {
						array_push($this->guests, $data[$i]);
					}
				}
			}
			fclose($handle);
		}	
	}
}