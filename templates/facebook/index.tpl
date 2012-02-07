<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>TrueRSVP</title>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.4.js"></script>
<script src="https://connect.facebook.net/en_US/all.js" type="text/javascript"></script>
  <script>
    $(document).ready(function(){
		FB.init({
		 appId  : '{$FB_APP_ID}',
		 status : true, // check login status
		 cookie : true, // enable cookies to allow the server to access the session
		 xfbml  : true,  // parse XFBML
		 oauth: true
	   });
	   FB.Canvas.setAutoResize();
	   FB.XFBML.parse();
	});
</script>
</head>
<body>
<div id="fb-root"></div>
<table width="600" border="0" cellspacing="0" cellpadding="0" align="center" style="font-family:Arial, Helvetica, sans-serif; color:#696969; font-size:11px;">
  <tr>
    <td style="font-size:0;"><img src="{$IMG_PATH}/main_bg_top.jpg" height="19" width="600" alt="" /></td>
  </tr>
  <tr>
    <td background="{$IMG_PATH}/main_bg_cnt.jpg" style="background-repeat:repeat-y;" height="600" valign="top">
    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="center">
            	{if $event->image eq NULL || $event->image eq ''}
            		<div style="display: block; width: 430px; margin: 0 auto 5px; padding:10px 0 14px; line-height: 40px; text-align: center; font-size: 30px; color: #666666; font-weight: bold; border-bottom: 1px solid #cccccc;">You're invited to...</div>
                {else}
                    <table width="569" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td style="font-size:0;"><img src="{$IMG_PATH}/frame_top.jpg" height="20" width="569" alt="" /></td>
                      </tr>
                      <tr>
                        <td background="{$IMG_PATH}/frame_cnt.jpg" style="background-repeat:repeat-y;" align="center">
                            <span id="event_custom_invite">
                            {if $event->image eq ""}
                                <img src="{$IMG_PATH}/photo.jpg" width="531" height="291" alt="" />
                            {else}
                                <img src="{$CURHOST}/upload/events/{$event->image}" width="531" height="291" />
                            {/if}
                            </span><br /><strong><span style="font-size:14px; padding-top:5px; display:block;"><span id="event_name">{$event->title}</span></span><span id="event_date">{$event->friendly_date}</span> at <span id="event_time">{$event->friendly_time}</span></strong>
                        </td>
                      </tr>
                      <tr>
                        <td style="font-size:0;"><img src="{$IMG_PATH}/frame_bot.jpg" height="20" width="569" alt="" /></td>
                      </tr>
                      <tr>
                        <td></td>
                      </tr>
                    </table>
                {/if}
            </td>
          </tr>
          <tr>
            <td align="center">
            	<table width="486" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td>
                    	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="196" valign="top" align="center">
                            	<span style="display:block; font-size:14px"><strong>RSVP Now</strong></span>
                                <span style="display:block; padding-bottom:5px;"><span style="color:#ff952c;"><span id="event_rsvp_days">{$event->rsvp_days_left}</span></span> days left to RSVP</span>
                                <a href="{$EVENT_URL}/a/{$event->alias}?gref={$event->global_ref}" target="_blank"><span id="event_link"><img src="{$IMG_PATH}/side_btns.gif" width="117" height="136" alt="" border="0" /></span></a>
                            </td>
                            <td valign="top">
                            	<span style="display:block; padding-top:5px;"><strong><span id="event_name">{$event->title}</span></strong></span>
                                <span style="display:block;"><span id="event_date">{$event->friendly_date}</span> at <span id="event_time">{$event->friendly_time}</span></span>
                                <span style="display:block;"><span id="event_location">{$event->address}</span></span>
                                <span style="display:block; padding-top:5px;"><strong>Details:</strong> <span id="event_description">{$event->description}</span></span>
                                <span style="display:block; padding-top:14px;"><strong>Like: <a href="{$EVENT_URL}/a/{$event->alias}?gref={$event->global_ref}" target="_blank" style="color:#42b3da; text-decoration:none;"><span id="event_link"><span id="event_link_actual">{$EVENT_URL}/a/{$event->alias}?gref={$event->global_ref}</span></span></a></strong></span>
                            </td>
                          </tr>
                        </table>
                    </td>
                  </tr>
                  <tr>
                    <td align="center" style="font-size:0;"><div style="padding:22px 0 10px 0;"><img src="{$IMG_PATH}/sep.gif" width="414" height="2" /></div></td>
                  </tr>
                  <tr>
                    <td align="center">
                    	<span style="display:block; color:#222; font-size:11px;">Spread the love: <a href="http://www.facebook.com/pages/trueRSVP/245102305531976" target="_blank"><img src="{$IMG_PATH}/fb_ico.gif" width="18" height="18" alt="facebook" style="vertical-align:-2px;" border="0" /></a> <a href="http://twitter.com/#!/trueRSVP" target="_blank"><img src="{$IMG_PATH}/twit_ico.gif" width="18" height="18" alt="twitter" style="vertical-align:-2px;" border="0" /></a></span>
                        <span style="display:block; padding:3px 0 3px 0;"><a href="{$CURHOST}" target="_blank"><img src="{$IMG_PATH}/logo.gif" width="137" height="35" alt="True RSVP" border="0" /></a></span>
                        <span style="display:block; text-align:center; font-size:10px;"><strong>trueRSVP is the first system that's flake proof. Find out how many people<br />are actually coming to your event &amp; stop last minute flakes for good! <a href="{$CURHOST}" style="color:#42b3da; text-decoration:none;" target="_blank">Learn more</a>.</strong></span>
                    </td>
                  </tr>
                </table>
            </td>
          </tr>
        </table>
    </td>
  </tr>
  <tr>
    <td style="font-size:0;"><img src="{$IMG_PATH}/main_bg_bot.jpg" height="25" width="600" alt="" /></td>
  </tr>
</table>

</body>
</html>