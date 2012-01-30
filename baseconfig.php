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
	define('DB_HOST', '127.0.0.1:3306');
	define('DB_USER', 'glaksmono');
	define('DB_PASS', '12345');
	define('DB_NAME', 'eventfii');		
	define('PATH', '/Eventfii');
	define('CURHOST', 'http://localhost'.PATH);	
}
define('DEBUG', true);
define('ENABLE_EMAIL', true);

define('FB_BASE_URL', 'https://apps.facebook.com/truersvpdev');
define('FB_GRAPH_URL', 'https://graph.facebook.com');
define('FB_APP_ID', '257841307621350');
define('FB_APP_SECRET', '7920961fdd741f11a4d3c26a82e59a4f');

define('GOOGLE_MAPS_API', 'ABQIAAAAYw9IaNOSbAwS1vK0gtJ__xT2yXp_ZAY8_ufC3CFXhHIE1NvwkxRy3n3lZ15FCAqVglN8_EtnpZDXbg');
define('GOOGLE_MAP_URL', 'https://maps.google.com/maps');

define('TWITTER_API', 'NdOFEfRSe5BNdveGsWBIpg');

define('ALL_REPORTING', E_ALL);
