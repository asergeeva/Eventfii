<?php
/*
 * Author : Grady Laksmono
 * Email : grady@eventfii.com
 * All code (c) 2011 Eventfii Inc. 
 * All rights reserved
 */
require_once(realpath(dirname(__FILE__)).'/../models/EFMail.class.php');
require_once(realpath(dirname(__FILE__)).'/../models/EFSMS.class.php');
require_once(realpath(dirname(__FILE__)).'/../models/EFCore.class.php');
require_once(realpath(dirname(__FILE__)).'/../models/Experiment.class.php');
require_once(realpath(dirname(__FILE__)).'/../models/MetricsTracker.class.php');
require_once(realpath(dirname(__FILE__)).'/../models/ImageResizer.class.php');
require_once(realpath(dirname(__FILE__)).'/../db/DBConfig.class.php');
require_once(realpath(dirname(__FILE__)).'/../libs/Facebook/facebook.php');
require_once(realpath(dirname(__FILE__)).'/../libs/GoogleMapAPI.class.php');

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
	
	public static $efDict = array(
		"{Guest name}",
		"{Host name}",
		"{Event name}",
		"{Event time}"
	);
	
	public static $confidenceMap = array(
		CONFOPT1 => "Absolutely",
		CONFOPT2 => "Pretty sure",
		CONFOPT3 => "50/50",
		CONFOPT4 => "Not likely",
		CONFOPT5 => "Raincheck",
		CONFOPT6 => "Spam",
		CONFELSE => "No Response"
	);
	
	public static $smarty;
	
	public static $dbCon;
	
	public static $mailer;
	
	public static $sms;
	
	public static $facebook;
	
	/* http://www.phpinsider.com/php/code/GoogleMapAPI/ */
	public static $google;
	
	public static $core;
	
	public static $imageResizer;
	
	public static $metricsTracker;
	
	public static $experimentManager;
		
	public function __construct($smarty = NULL) {
		date_default_timezone_set('America/Los_Angeles');
	
		$this->currDate = getdate();
		$this->startDate = $this->currDate['year'].'-'.$this->currDate['mon'].'-'.$this->currDate['mday'];
		$this->startDate = strtotime($this->startDate);
		
		$this->startDate = date('Y-m-d', mktime(0,0,0,date('m',$this->startDate),date('d',$this->startDate),date('Y',$this->startDate)));
		$this->endDate = $this->add_date($this->startDate, 1);
		
		self::$smarty = $smarty;
		
		self::$dbCon = new DBConfig();
		
		self::$mailer = new EFMail();
		
		self::$sms = new EFSMS();
		
		self::$facebook = new Facebook(array(
		  'appId'  => FB_APP_ID,
		  'secret' => FB_APP_SECRET,
		  'cookie' => true
		));
		
		self::$google = new GoogleMapAPI('map');
		self::$google->setAPIKey(GOOGLE_MAPS_API);
		
		self::$core = new EFCore();
		
		self::$imageResizer = new ImageResizer();
		
		self::$metricsTracker = new MetricsTracker(MIXPANEL_TOKEN);
		
		self::$experimentManager = new Experiment();
	}
	
	public function __destruct() {
		
	}
	
	public static function add_date($orgDate,$yr){
	  $cd = strtotime($orgDate);
	  $retDAY = date('Y-m-d', mktime(0, 0, 0, date('m',$cd), date('d',$cd), date('Y',$cd) + $yr));
	  return $retDAY;
	}
	
	public static function toPercent($intVal) {
		return floatval($intVal) * 0.01;
	}
	
	public static function mapText($text, &$event, &$guest) {
		for ($i = 0; $i < sizeof(self::$efDict); ++$i) {
			if (isset($guest)) {
				switch (EFCommon::$efDict[$i]) {
					case "{Guest name}":
						$text = str_replace(EFCommon::$efDict[$i], $guest->fname, $text);
						break;
				}
			}
			
			if (isset($event)) {
				switch (EFCommon::$efDict[$i]) {
					case "{Host name}":
						$text = str_replace(EFCommon::$efDict[$i], $event->organizer->fname, $text);
						break;
					case "{Event name}":
						$text = str_replace(EFCommon::$efDict[$i], $event->title, $text);
						break;
					case "{Event time}":
						if (isset($event->friendly_time) && trim($event->friendly_time) != "") {
							$text = str_replace(EFCommon::$efDict[$i], $event->friendly_time, $text);
						} else {
							$text = str_replace(EFCommon::$efDict[$i], $event->time, $text);
						}
						break;
				}
			}
		}
		return $text;
	}
	
	public static function gcd($n,$m){
		if($m == 0) {
			return $n;
		}
		return self::gcd($m, $n%$m);
	}
	
	public static function resizeImage($filename) {
		
		self::$imageResizer->load($filename);
		self::$imageResizer->resizeToHeight(96);
		self::$imageResizer->resizeToWidth(96);
		self::$imageResizer->save($filename);
	}
	
	/**
	 * Get all of the emails given the CSV file
	 */
	public static function getContactsFromCSV($csvFile) {
		$csv_contacts = array();
		if (($handle = fopen($csvFile, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
				for ($i = 0; $i < sizeof($data); ++$i) {
					if (filter_var($data[$i], FILTER_VALIDATE_EMAIL)) {
						array_push($csv_contacts, $data[$i]);
					}
				}
			}
			fclose($handle);
		}
		return $csv_contacts;
	}
}