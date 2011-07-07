<?php
class CalGen{
var $frm_year;
var $frm_mth;
var $frm_date;
var $frm_hr;
var $frm_min;

var $to_year;
var $to_mth;
var $to_date;
var $to_hr;
var $to_min;

var $text;
var $details;
var $location;
var $link;
var $link_desc;

var $frm_dt;
var $to_dt;
var $dates;

function __construct($from_year,$from_month,$from_date,$from_hr,$from_minute,$to_year,$to_month,$to_date,$to_hr,$to_min,$description,$details,$location,$link,$link_description){
$this->frm_year=$from_year;
$this->frm_mth=$from_month;
$this->frm_date=$from_date;
$this->frm_hr=$from_hr;
$this->frm_min=$from_minute;
$this->to_year=$to_year;
$this->to_mth=$to_month;
$this->to_date=$to_date;
$this->to_hr=$to_hr;
$this->to_min=$to_min;
$this->text=rawurlencode($description);
$this->details=rawurlencode($details);
$this->location=rawurlencode($location);
$this->link=rawurlencode($link);
$this->link_desc=rawurlencode($link_description);
$this->frm_dt=$this->frm_year.$this->frm_mth.$this->frm_date.'T'.$this->frm_hr.$this->frm_min.'00';
$this->to_dt=$this->to_year.$this->to_mth.$this->to_date.'T'.$this->to_hr.$this->to_min.'00';
$this->dates = $this->frm_dt.'/'.$this->to_dt;
}

function getGCAL()
{
	$gCalButton='';
	$gCalButton = '<a href="http://www.google.com/calendar/event?action=TEMPLATE&text='.$this->text.'&dates='.$this->dates.'&details='.$this->details.'&location='.$this->location.'&trp=false&sprop='.$this->link.'&sprop='.$this->link_desc.'" target="_blank">';
	$gCalButton .= '<img src="http://www.google.com/calendar/images/ext/gc_button6.gif" border=0>';
	$gCalButton .= '</a>';
	echo $gCalButton;
}

function getVCS()
{
header("Content-Type: text/x-vCalendar");
header("Content-Disposition: inline; filename = trueRSVP.vcs");
/*The code for generating a .vcs file for outlook users*/	
$vCalOutput="";
$vCalOutput = $vCalOutput."BEGIN:VCALENDAR\n";
$vCalDescription = str_replace("\r", "\\n", $this->details);
$vCalLocation = str_replace("\r", "\\n", $this->location);
$text = str_replace("\r", "\\n", $this->text);
/* output the event */
$vCalOutput = $vCalOutput."BEGIN:VEVENT\n";
$vCalOutput = $vCalOutput."SUMMARY:".rawurldecode($text)."\n";
$vCalOutput = $vCalOutput."DESCRIPTION:".rawurldecode($vCalDescription)."\n";
$vCalOutput = $vCalOutput."DTSTART:".$frm_dt."\n";
$vCalOutput = $vCalOutput."LOCATION:".rawurldecode($vCalLocation)."\n";
$vCalOutput = $vCalOutput."URL;VALUE=URI:".rawurldecode($link)."\n";
$vCalOutput = $vCalOutput."DTEND:".$to_dt."\n";
$vCalOutput = $vCalOutput."END:VEVENT\n";
$vCalOutput = $vCalOutput."END:VCALENDAR\n";
echo $vCalOutput;

}

function getICS()
{
header('Content-Type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename="trueRSVP.ics"; ');



$vCalOutput="";
$vCalOutput = $vCalOutput."BEGIN:VCALENDAR\n";
$vCalOutput = $vCalOutput."CALSCALE:GREGORIAN\n";
$vCalOutput = $vCalOutput."X-WR-TIMEZONE;VALUE=TEXT:US/Pacific\n";
$vCalOutput = $vCalOutput."METHOD:PUBLISH\n";
$vCalOutput = $vCalOutput."PRODID:-//Apple Computer\, Inc//iCal 1.0//EN\n";
$vCalOutput = $vCalOutput."X-WR-CALNAME;VALUE=TEXT:TrueRSVP\n";
$vCalOutput = $vCalOutput."VERSION:2.0\n";
$vCalOutput = $vCalOutput."BEGIN:VEVENT\n";
$vCalOutput = $vCalOutput."DESCRIPTION:".rawurldecode($this->details)."\n";
$vCalOutput = $vCalOutput."SEQUENCE:5\n";
$vCalOutput = $vCalOutput."DTSTART;TZID=US/Pacific:".$this->frm_dt."\n";
$vCalOutput = $vCalOutput."DTSTAMP:".$this->frm_dt."\n";
$vCalOutput = $vCalOutput."SUMMARY:".rawurldecode($this->text)."\n";
$vCalOutput = $vCalOutput."UID:EC9439B1-FF65-11D6-9973-003065F99D04\n";
$vCalOutput = $vCalOutput."DTEND;TZID=US/Pacific:".$this->to_dt."\n";
$vCalOutput = $vCalOutput."BEGIN:VALARM\n";
$vCalOutput = $vCalOutput."TRIGGER;VALUE=DURATION:-P1D\n";
$vCalOutput = $vCalOutput."ACTION:DISPLAY\n";
$vCalOutput = $vCalOutput."DESCRIPTION:Event reminder\n";
$vCalOutput = $vCalOutput."END:VALARM\n";
$vCalOutput = $vCalOutput."END:VEVENT\n";
$vCalOutput = $vCalOutput."END:VCALENDAR\n";

echo $vCalOutput;

}
}
?>