<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	
	<!-- Facebook sharing information tags -->
	<meta property="og:title" content="Event Title" />
	
	<title>{$event->title}</title>
	<style type="text/css">
		{literal}
		/* Client-specific Styles */
		#outlook a{padding:0;} /* Force Outlook to provide a "view in browser" button. */
		body{-webkit-text-size-adjust:none;} /* Prevent Webkit platforms from changing default text sizes. */

		/* Reset Styles */
		body{margin:0; padding:0;}
		a{color: #3399cc; cursor: pointer;}
		img{border:0; height:auto; line-height:100%; outline:none; text-decoration:none;vertical-align: middle;}
		table td{border-collapse:collapse;}
		#backgroundTable{height:100% !important; margin:0; padding:0; width:100% !important;}
		
		.btn-rsvp{background: url({$IMG_PATH}/btn_rsvp.gif) 0 0 no-repeat; text-align: center; text-decoration: none; color: #fff; width: 207px; height: 16px; padding: 4px 0 0; margin: 0 0 2px; display: block;}
		.btn-rsvp:hover{color: #F93;}
		.btn-attend{background: url({$IMG_PATH}/btn_attend.gif) 0 0 no-repeat; text-align: center; text-decoration: none; color: #fff; width: 128px; height: 18px; padding: 6px 0 0; margin: 0 0 2px; display: block;}
		.btn-attend:hover{color: #F93;}
		
		#event_info {width: 300px;}
		{/literal}
	</style>
</head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">

<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="backgroundTable" style="font-family: Arial; font-size: 12px; ">
	<tr style="background: #3399cc">
		<td><a href="http://truersvp.com/" title="trueRSVP"><img src="{$IMG_PATH}/logo.gif" alt="trueRSVP" /></a></td>
	</tr>
	<tr>
		<td style="padding: 10px">
			<p>You're invited to:</p>
			<p id="event_info"><a href="{$EVENT_URL}/a/{$event->alias}?gref={$event->global_ref}" target="_blank"><span id="event_name">{$event->title}</span></a><br />
				<span id="event_date">{$event->friendly_date}</span> at <span id="event_time">{$event->friendly_time}</span><br />
				<span id="event_location">{$event->address}</span><br /><br />
				<span id="event_description">{$event->description}</span></p>
			<h3><a href="{$EVENT_URL}/a/{$event->alias}?gref={$event->global_ref}" target="_blank"><span id="event_link">RSVP now</span></a></h3>
			<p>The sooner you RSVP, the more reputation points you will earn!</p>
			<p>Thanks,<br /><span id="host_name">{$event->organizer->fname} {$event->organizer->lname}</span><br /><span id="host_email">{$event->organizer->email}</span></p>
			<p>Share this event on Twitter &amp; Facebook! <a href="http://www.facebook.com/pages/trueRSVP/245102305531976" target="_blank"><img src="{$IMG_PATH}/icon_facebook.gif" alt="Share on Facebook" /></a> <a href="http://twitter.com/#!/trueRSVP" target="_blank"><img src="{$IMG_PATH}/icon_twitter.gif" alt="Share on Twitter" /></a></p>
			<p>trueRSVP is the first RSVP system based on reputation. Find out how many people are actually coming to your event &amp; stop last minute flakes for good! <a href="https://truersvp.com" target="_blank">Learn more.</a></p>
		</td>
	</tr>
</table>

</body>
</html>