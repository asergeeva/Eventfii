<?php
/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
class EFCommon {
	public $currDate;
	public $startDate;
	public $endDate;
	
	function __construct() {
		$this->currDate = getdate();
		$this->startDate = $this->currDate['year'].'-'.$this->currDate['mon'].'-'.$this->currDate['mday'];
		$this->startDate = strtotime($this->startDate);
		
		$this->startDate = date('Y-m-d', mktime(0,0,0,date('m',$this->startDate),date('d',$this->startDate),date('Y',$this->startDate)));
		$this->endDate = $this->add_date($this->startDate, 1);
	}
	
	function __destruct() {
		
	}
	
	public function add_date($orgDate,$yr){
	  $cd = strtotime($orgDate);
	  $retDAY = date('Y-m-d', mktime(0, 0, 0, date('m',$cd), date('d',$cd), date('Y',$cd) + $yr));
	  return $retDAY;
	}
}