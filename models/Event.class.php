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
	public $min_spot;
	public $max_spot;
	public $address;
	public $date;
	public $deadline;
	public $time;
	public $description;
	public $cost;
	public $is_public;
	public $gets;
	
	public $eid;
	
	function __construct($organizer,
							 $title,
						   $url,
						   $min_spot,
							 $max_spot,
						   $address,
						   $date,
						   $time,
							 $deadline,
						   $description,
						   $cost,
							 $is_public,
							 $gets) {
		$this->organizer = $organizer;
		$this->title = $title;
		$this->url = $url;
		$this->min_spot = $min_spot;
		$this->max_spot = $max_spot;
		$this->address = $address;
		$this->date = $date;
		$this->time = $time;
		$this->deadline = $deadline;
		$this->description = $description;
		$this->cost = $cost;
		$this->is_public = $is_public;
		$this->gets = $gets;
	}
	
	function __destruct() {
		
	}
}