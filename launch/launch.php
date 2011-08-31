<?php
$DB_HOST = "internal-db.s99541.gridserver.com:3306";
$DB_USER = "db99541_true";
$DB_PASS = "happyparty";
$DB_NAME = "db99541_true";
	
if (filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL)) {
	$dbLink = mysql_connect($DB_HOST, $DB_USER, $DB_PASS);
	mysql_select_db($DB_NAME);
	if (!$dbLink) {
		die('Could not connect: '.mysql_error());	
	}
	$STORE_SIGNUPS = "INSERT IGNORE INTO ef_launch_signups (email, dislike) 
											VALUES ('".mysql_real_escape_string($_REQUEST['email'])."',
															'".mysql_real_escape_string($_REQUEST['dislike'])."')";
	$dbResult = mysql_query($STORE_SIGNUPS);
	if (!$dbResult) {
		print($STORE_SIGNUPS . "<br />");
		die('Invalid query: ' . mysql_error());
	}
	mysql_close($dbLink);
	print("Thank you! We're launching in August 2011 and you'll have first dibs.");
} else {
	print('Invalid e-mail. Please enter your e-mail in the correct format. Thank you!');
}