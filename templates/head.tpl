<!DOCTYPE html>
<html lang="en" xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml">
<head> 
	<meta charset="utf-8" />
	<meta name="Author" content="trueRSVP" />
	<meta property="fb:app_id" content="123284527755183" />
	<meta property="fb:admins" content="1164166702" />
	<meta property="og:title" content="{if !isset($event->title)}trueRSVP{else}{$event->title}{/if}"/> 
	<meta property="og:type" content="event" /> 
	<meta property="og:url" content="{if !isset($event->title)}{$CURHOST}{else}{$EVENT_URL}/a/{$event->alias}{/if}" /> 
	<meta property="og:image" content="{$IMG_PATH}/trueRSVP_logo.png" /> 
	<meta property="og:site_name" content="{$WTITLE} | {if !isset($event->title)}{$WSLOGAN}{else}{$event->title}{/if}" /> 
	<meta property="og:description" content="{if !isset($event->description)}Create an event & find out how many people will actually show up!{else}{$event->description}{/if}" /> 
	<title>{$WTITLE} | {if !isset($event->title)}{$WSLOGAN}{else}{$event->title}{/if}</title>
	<link rel="stylesheet" type="text/css" href="{$CSS_PATH}/style.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="{$CSS_PATH}/fileuploader.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="{$CSS_PATH}/autocomplete.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="{$CSS_PATH}/jquery-ui-1.8.11.custom.css" />
	<link rel="SHORTCUT ICON" href="{$CSS_PATH}/favicon.ico" />
	<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	
	<script type="text/javascript">var _gaq=_gaq||[];_gaq.push(["_setAccount","UA-24315776-1"]);_gaq.push(["_trackPageview"]);(function(){ var a=document.createElement("script");a.type="text/javascript";a.async=!0;a.src=("https:"==document.location.protocol?"https://ssl":"http://www")+".google-analytics.com/ga.js";var b=document.getElementsByTagName("script")[0];b.parentNode.insertBefore(a,b) })();</script>
	
	{literal}
	<!-- start Mixpanel --><script type="text/javascript">var mpq=[];mpq.push(["init","90a81dc86956434fc4a155802fac19fc"]);(function(){var b,a,e,d,c;b=document.createElement("script");b.type="text/javascript";b.async=true;b.src=(document.location.protocol==="https:"?"https:":"http:")+"//api.mixpanel.com/site_media/js/api/mixpanel.js";a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(b,a);e=function(f){return function(){mpq.push([f].concat(Array.prototype.slice.call(arguments,0)))}};d=["init","track","track_links","track_forms","register","register_once","identify","name_tag","set_config"];for(c=0;c<d.length;c++){mpq[d[c]]=e(d[c])}})();
</script><!-- end Mixpanel -->
	{/literal}
</head>
