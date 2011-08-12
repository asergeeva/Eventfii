<?php
/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
require_once(realpath(dirname(__FILE__)).'/../db/DBConfig.class.php');
require_once(realpath(dirname(__FILE__)).'/../models/EFMail.class.php');

class EFCommon {
	public $currDate;
	public $startDate;
	public $endDate;
	public $monthAlpha = array(
		'01' => 'January',
		'02' => 'February',
		'03' => 'March',
		'04' => 'April',
		'05' => 'May',
		'06' => 'June',
		'07' => 'July',
		'08' => 'August',
		'09' => 'September',
		'10' => 'October',
		'11' => 'November',
		'12' => 'December'
	);
	
	public static $smarty;
	
	public static $dbCon;
	
	public static $mailer;
	
	public function __construct($smarty) {
		$this->currDate = getdate();
		$this->startDate = $this->currDate['year'].'-'.$this->currDate['mon'].'-'.$this->currDate['mday'];
		$this->startDate = strtotime($this->startDate);
		
		$this->startDate = date('Y-m-d', mktime(0,0,0,date('m',$this->startDate),date('d',$this->startDate),date('Y',$this->startDate)));
		$this->endDate = $this->add_date($this->startDate, 1);
		
		self::$smarty = $smarty;
		
		self::$dbCon = new DBConfig();
		
		self::$mailer = new EFMail();
	}
	
	public function __destruct() {
		
	}
	
	public function add_date($orgDate,$yr){
	  $cd = strtotime($orgDate);
	  $retDAY = date('Y-m-d', mktime(0, 0, 0, date('m',$cd), date('d',$cd), date('Y',$cd) + $yr));
	  return $retDAY;
	}
	
	public function toPercent($intVal) {
		return floatval($intVal) * 0.01;
	}
}