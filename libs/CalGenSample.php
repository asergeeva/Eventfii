<?php
include_once("CalGen.class.php");
$cal=new CalGen("2011","12","15","12","34","2012","12","15","14","34","manu's birthday party","mamamman mmamama mamamma mamama mamamm mamam mammama","bhopal c-19","http://manumehrotra.com","manu's website");

if(isset($_GET['fn']) && $_GET['fn']=="ICS")
	{
	$cal->getICS();
	exit();
	}	
	
if(isset($_GET['fn']) && $_GET['fn']=="VCS")
	{
	$cal->getVCS();
	exit();
	}
	
echo($cal->getGCAL());	
?>
<br />
<a href="?fn=ICS">Get ICS</a>
<br />
<a href="?fn=VCS">Get VCS</a>
