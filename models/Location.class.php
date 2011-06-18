<?php
/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
class Location {
	public $address;
	public $city;
	public $state;
	public $lat = 0;
	public $long = 0;
	
	function __construct($address, $city, $state) {
		$this->address = $address;
		$this->city = $city;
		$this->state = $state;
	}
	
	function __destruct() {
		
	}
}