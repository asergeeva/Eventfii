<?php
/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */
if($_SERVER['HTTP_HOST'] == 'eventfii.iserver.purelogics.info')
{
	define('DB_HOST', '127.0.0.1:3306');
	define('DB_USER', 'eventfii');
	define('DB_PASS', 'pl@123');
	define('DB_NAME', 'eventfii');		
	define('PATH', '');
	define('CURHOST', 'http://'.$_SERVER['HTTP_HOST'].PATH);
}else
{
	define('DB_HOST', 'internal-db.s99541.gridserver.com:3306');
	define('DB_USER', 'db99541_true_dev');
	define('DB_PASS', 'happyparty');
	define('DB_NAME', 'db99541_true_dev');		
	define('PATH', '');
	define('CURHOST', 'http://'.$_SERVER['HTTP_HOST'].PATH);
}
define('DEBUG', true);
define('ENABLE_EMAIL', true);

define('FB_BASE_URL', 'https://apps.facebook.com/truersvp_dev');
define('FB_GRAPH_URL', 'https://graph.facebook.com');
define('FB_APP_ID', '215532001821574');
define('FB_APP_SECRET', '356ff9caba21a0da73cb8fbe99310f86');

define('GOOGLE_MAPS_API', 'ABQIAAAAYw9IaNOSbAwS1vK0gtJ__xT2yXp_ZAY8_ufC3CFXhHIE1NvwkxRy3n3lZ15FCAqVglN8_EtnpZDXbg');
define('GOOGLE_MAP_URL', 'https://maps.google.com/maps');

define('TWITTER_API', 'NdOFEfRSe5BNdveGsWBIpg');

define('ALL_REPORTING', E_ALL);
