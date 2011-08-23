<!DOCTYPE html>
<html lang="en" xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml">
<head> 
	<meta charset="utf-8" />
	<meta name="Author" content="trueRSVP" />
	<meta property="fb:app_id" content="123284527755183" />
	<meta property="fb:admins" content="1164166702" />
	<meta property="og:title" content="{if !isset($event->title)}trueRSVP{else}{$event->title}{/if} "/> 
	<meta property="og:type" content="event" /> 
	<meta property="og:url" content="{$CURHOST}{$current_page}" /> 
	<meta property="og:image" content="{$IMG_PATH}/logo_wide.jpg" /> 
	<meta property="og:site_name" content="{$WTITLE} | {if !isset($event->title)}{$WSLOGAN}{else}{$event->title}{/if}" /> 
	<meta property="og:description" content="{if !isset($event->description)}A new RSVP system based on reputation{else}{$event->description}{/if}" /> 
	<title>{$WTITLE} | {if !isset($event->title)}{$WSLOGAN}{else}{$event->title}{/if}</title>
	<link rel="stylesheet" type="text/css" href="{$CSS_PATH}/style.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="{$CSS_PATH}/fileuploader.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="{$CSS_PATH}/jquery-ui-1.8.11.custom.css" />
	<link rel="SHORTCUT ICON" href="{$CSS_PATH}/favicon.ico" />
	<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	
	<script type="text/javascript">var _gaq=_gaq||[];_gaq.push(["_setAccount","UA-24315776-1"]);_gaq.push(["_trackPageview"]);(function(){ var a=document.createElement("script");a.type="text/javascript";a.async=!0;a.src=("https:"==document.location.protocol?"https://ssl":"http://www")+".google-analytics.com/ga.js";var b=document.getElementsByTagName("script")[0];b.parentNode.insertBefore(a,b) })();</script>
</head>
