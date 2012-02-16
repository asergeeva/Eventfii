{include file="head.tpl"}
    <body class="whiteBg">
<span id="event_title" style="display:none;">{$event->title}</span>
    {include file="new_header.tpl"}
<article id="container">
    {if isset($smarty.get.created) && $smarty.session.user->id == $event->organizer->id}
        <div class="block info-message">
            <h1>Woohoo! Here's your awesome event page!</h1>
            <h2>And your event link to share with everyone:</h2>
            <p class="event-link">{$CURHOST}/event/a/{$event->alias}</p>
            <footer class="buttons">
                <p><a href="{$CURHOST}/event/manage?eventId={$event->eid}" class="btn btn-med"><span>Manage</span></a></p>
            </footer>
        </div>
    {/if}
    {if (isset($smarty.session.user) && $smarty.session.user->id == $event->organizer->id) && ( ! isset($smarty.get.public) || $smarty.get.public != true) && ( ! isset($smarty.get.created) || $smarty.get.created != true)}
			<header id="header">
		<nav>
			<ul>
				<li id="manage"><a href="{$CURHOST}/event/manage?eventId={$event->eid}"><span>Manage</span></a></li>
				<li id="edit"><a href="{$CURHOST}/event/manage/edit?eventId={$event->eid}" id="update_event_edit"><span>Edit</span></a></li>
				<li class="current"><a href="{$CURHOST}/event/a/{$event->alias}?preview=true" id="update_event_preview"><span>Preview</span></a></li>
			</ul>
		</nav>
    </header>
    <div style="clear:both"></div>
        {/if}
        <div style="width: 900px; margin: 20px auto;">
        	{if date("Y-m-d", strtotime($event->datetime)) == date("Y-m-d") || date("Y-m-d", strtotime($event->datetime)) < date("Y-m-d")}
                <section class="block clearfix" style=" display:block; padding-bottom:0px;overflow:visible;">
                    <center>
                        <table cellpadding="0" cellspacing="0" style="width:auto">
                        <tr>
                            <td align="center">
                                <div class="viewby_top" style="width:100%;">
                                    <img src="{$CURHOST}/images/share_photo_txt.png" alt="Photo" style="vertical-align: -11px; float:left;" /> &nbsp; <span style="font-size:16px; float:left; padding:0 10px;">Share your photos from <strong>{$event->title}!</strong></span> &nbsp; 
                            
                                {if isset($smarty.session.user)}
                                	{if $showUpload eq 'yes'}
                                    	<div style=" float:left; margin-top:5px; "><form method="post" id="create_guests" enctype="multipart/form-data"><input type="file" name="file" id="file" /></form></div>
                                   	{else}
                                    	<a style="margin-top:-5px;" href="javascript:void(0);" class="btn btn-manage" onClick="$('#rsvp-first-error').fadeIn(500);"><span>&nbsp; Browse &nbsp;</span></a>
                                    {/if}
                                {else}
                                    <a style="margin-top:5px;" href="javascript:void(0);" class="btn btn-manage" id="" onClick="$('#log-in').fadeIn(500);"><span>&nbsp; Browse &nbsp;</span></a>    
                                {/if}
                        </div>
                            </td>
                        </tr>
                        </table>
                    </center>
                    <div class="viewby_img" id="viewby_img_div" style="display:none;">
                        <div id="container_isolate">
                            <div class="title" id="viewBy">Viewing all by <span id="viewByName"></span></div>
                            <div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>
                            <div class="viewport">
                                <div class="overview" id="container_iso"></div>                            
                            </div>
                        </div>
                    </div>
                    <div class="viewby_bot clearfix " id="viewby_bot_div" style="display:none; width:100%">
                        <div class="bl" style="position: relative;">Sort by: <span id="sort_by" style="min-width:78px;"></span> 
                        <div class="dd_box" style="display:none;">
                        </div>
                        <span id="back_to_all" style="display:none;"><a href="javascript:void(0);" onClick="reloadImagesAll();">Back to All Photos</a></span></div>
                        <div class="br">View: <a href="javascript:void(0);" onClick="openFaceBox();" id="slideshow">Slideshow</a></div>
                    </div>
                </section>
            {/if}
            {if $event_image eq NULL || $event_image eq ''}
            	<div class="orent_box">
                        <h2>{$event->title}</h2>
                        <h3>{date("F j, Y, g:i A", strtotime($event->datetime))}</h3>
                        <strong class="links fl">Share this event link: <a href="{$CURHOST}/event/a/{$event->alias}" id="eventURL">{$CURHOST}/event/a/{$event->alias}</a></strong>
                        <span class="fr" style="margin-right:-37px;margin-top:18px;">
                        	{if $event->is_public}
                            	<div class="fb-like" data-href="{$CURHOST}/event/a/{$event->alias}" data-send="true" data-layout="button_count" data-show-faces="false"></div>
                        	{/if}
                        </span>
                    </div>
            {/if}
            <div class="clear22"></div>
            <header id="notification-box-update" style="display:none;" class="block notification update"><p class="message">Your RSVP has been updated!</p></header>
            {if $extraParam eq ''}
            	<header id="notification-box" style="display:none;" class="block notification"><p class="message">Thanks for your RSVP!</p></header>
            {else}
            	<header id="notification-box" class="block notification"><p class="message">Thanks for your RSVP!</p></header>
            {/if}
            <div>
                <div class="photo_frame_outer poto_fram_gray fl">
                {if $event_image neq ''}
                    <div class="fram_bot">
                        <div class="fram_cnt">
                            <div class="fram_top">
                              {if $event_image neq ""}
                                <img src="{$CURHOST}/upload/events/{$event_image}" alt="photo" />
                              {else}
                                <img src="{$CURHOST}/upload/events/photo.png" alt="photo" />
                              {/if} 
                             <div><span>{$event->title}</span>{date("F j, Y, g:i A", strtotime($event->datetime))}</div>  
                           </div>
                        </div>
                    </div>
                    <div style="float:left; padding:10px 0 5px 10px;">Share this event link: <a href="{$CURHOST}/event/a/{$event->alias}" id="eventURL">{$CURHOST}/event/a/{$event->alias}</a></div>
                    <div style="float:right; padding:10px 0 0; margin-right:-37px;">
                    	{if $event->is_public}
                            <div class="fb-like" data-href="{$CURHOST}/event/a/{$event->alias}" data-send="true" data-layout="button_count" data-show-faces="false"></div>
                        {/if}
                    </div>
                 {/if}
                    <div style="clear:both;"></div>
                    {if isset($attending)}
                    <div style="padding:0 15px;">
                    	<fieldset>
                            <legend>Who's Coming</legend>
                            <ul class="thumbs">{foreach from=$attending key=index item=guest}{if $index lt {$MAX_DISPLAY_GUEST}}
                            <li>
                                <figure>
                                    <a href="{$CURHOST}/user/a/{$guest->alias}">
                                        <span></span><img src="{$guest->pic}?type=normal" alt="{$guest->fname} {$guest->lname}" />
                                        <figcaption>{$guest->fname} {$guest->lname}</figcaption>
                                    </a>
                                </figure>
                            </li>{/if}{/foreach}
        
                        </ul>
                        <footer class="links-extra">
                            <p><a href="javascript:void(0);" id="all-guests">See All ({sizeof($attending)})</a></p>
                        </footer>
                        </fieldset>
                    </div>
                    <div style="padding:0 15px;">
                    	<fieldset>
                        	<legend>Details</legend>
                        	<p class="event-desc" style="padding:10px 0 0; min-height:70px;">{$event->description}</p>
                        </fieldset>
                    </div>
                    <div style="padding:0 15px 20px;">
                    	<fieldset>
                        	<legend>Comments</legend>
                            <div class="fb-comments" data-href="{$CURHOST}/event/{$event->eid}" data-num-posts="2" data-width="650px"></div>
                        </fieldset>
                    </div>
                    {/if}
                </div>
                <aside id="rsvp" class="viewby_sidebar">
                	{include file="popup_rsvp_multiple.tpl"}
                    <div>
                    	<fieldset>
                            <legend style="padding-top:0px;">RSVP Now</legend>{if $event->rsvp_days_left > 0}
                            {* {if $you_rsvpd eq 'true' && {sizeof($attending)-1} > 0}
                                <p class="rsvp-message"><em id="rsvp_days_left"{if isset($smarty.session.user)} class="loggedIn"{/if}>You</em> and {sizeof($attending)-1} guests have RSVP'd</p>
                           	{else} *}
                            	<p class="rsvp-message"><em id="rsvp_days_left"{if isset($smarty.session.user)} class="loggedIn"{/if}>{$event->rsvp_days_left}</em> days left to RSVP</p>
                          	{* {/if} *}
                            {else if $event->rsvp_days_left == 0}
                            <p class="rsvp-message"><em id="rsvp_days_left" style="display:none"{if isset($smarty.session.user)} class="loggedIn"{/if}>0</em>Today is the last day to RSVP for this event</p>{else}            
                            <p class="rsvp-message"><em id="rsvp_days_left" style="display:none"{if isset($smarty.session.user)} class="loggedIn"{/if}></em>The deadline to RSVP for this event had passed</p>{/if}
                            <ol class="rsvp-list {if $event->total_rsvps > 0}noMore{/if}" id="event_attending_response">
                                <li>
                                    <label class="rsvp-1{if isset($select90)} selected{/if}" for="event_attending_response_1">
                                        <input type="radio" name="event_attending_response" value="{$CONFOPT1}"{if isset($conf90)}{$conf90}{/if}{if isset($disabled)}{$disabled}{/if} id="event_attending_response_1" class="rsvp-opt" /> 
                                        <span>Absolutely</span>
                                        <em>I'll definitely be there</em>
                                    </label>
                                </li>
                                <li>
                                    <label class="rsvp-2{if isset($select65)} selected{/if}" for="event_attending_response_2">
                                        <input type="radio" name="event_attending_response" value="{$CONFOPT2}"{if isset($conf65)}{$conf65}{/if}{if isset($disabled)}{$disabled}{/if} id="event_attending_response_2" class="rsvp-opt" /> 
                                        <span>Pretty sure</span>
                                        <em>I'll have to check my schedule</em>
                                    </label>
                                </li>
                                <li>
                                    <label class="rsvp-3{if isset($select35)} selected{/if}" for="event_attending_response_3">
                                        <input type="radio" name="event_attending_response" value="{$CONFOPT3}"{if isset($conf35)}{$conf35}{/if}{if isset($disabled)}{$disabled}{/if} id="event_attending_response_3" class="rsvp-opt" /> 
                                        <span>50/50</span>
                                        <em>Interested, but not ready to commit</em>
                                    </label>
                                </li>
                                <li>
                                    <label class="rsvp-4{if isset($select15)} selected{/if}" for="event_attending_response_4">
                                        <input type="radio" name="event_attending_response" value="{$CONFOPT4}"{if isset($conf15)}{$conf15}{/if}{if isset($disabled)}{$disabled}{/if} id="event_attending_response_4" class="rsvp-opt" /> 
                                        <span>Not likely</span>
                                        <em>I probably won't go</em>
                                    </label>
                                </li>
                                <li>
                                    <label class="rsvp-label rsvp-5{if isset($select4)} selected{/if}" for="event_attending_response_5">
                                        <input type="radio" name="event_attending_response" value="{$CONFOPT5}"{if isset($conf4)}{$conf4}{/if}{if isset($disabled)}{$disabled}{/if} id="event_attending_response_5" class="rsvp-opt" /> 
                                        <span>Raincheck</span>
                                        <em>Can't make it this time</em>
                                    </label>
                                </li>{*
                                <li>
                                    <label class="rsvp-6{if isset($select1)} selected{/if}" for="event_attending_response_6">
                                        <input type="radio" name="event_attending_response" value="{$CONFOPT6}"{if isset($conf1)}{$conf1}{/if}{if isset($disabled)}{$disabled}{/if} id="event_attending_response_6" /> 
                                        <span>Spam. Take me off this host's list.</span>
                                    </label>
                                </li>*}
                            </ol>
                        </fieldset>
                    </div>
                    <div class="align_lft">
                    	<fieldset>
                            <legend>Hosted By</legend>
                            <span style="float:left;">
                            <ul class="thumbs">
                                <li>
                                    <figure style="text-align:center;">
                                        <a href="{$CURHOST}/user/a/{$event->organizer->alias}">
                                            <span><img src="{$event->organizer->pic}?type=normal" alt="{$event->organizer->fname} {$event->organizer->lname}" /></span>
                                        </a>
                                    </figure>
                                </li>
            
                            </ul>
                            </span>
                            <span style="float:left; color:#3399CC; padding:10px 0 0; width:110px;"><a href="{$CURHOST}/user/a/{$event->organizer->alias}">{$event->organizer->fname} {$event->organizer->lname}</a><br />&nbsp;<br /><a href="mailto:{$event->organizer->email}">Contact host</a></span>
                        </fieldset>
                    </div>
                    <div class="align_lft">
                    	<fieldset>
                            <legend>Where</legend>
                            <figure>
                                <figcaption><address>{if isset($event->location) && trim($event->location) neq ""}{$event->location}, {/if}{$event->address}</address></figcaption>
                                <iframe width="100%" height="203" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="{$GOOGLE_MAP_URL}?q={$event->address|urlencode}&amp;hnear={$event->address|urlencode}&amp;hl=en&amp;sll={$event->location_lat},{$event->location_long}&amp;ie=UTF8&amp;hq=&amp;z=14&amp;output=embed&amp;iwloc=near"></iframe>
                            </figure>
                            <header>
								<h1 style="padding:8px 0;">Add event to:</h1>
                       		</header>
                        	<p class="icons"><a href="{$CURHOST}/calendar/ics?eventId={$event->eid}" class="icon-ical" target="_blank">iCal</a> <a href="http://www.google.com/calendar/event?action=TEMPLATE&amp;text={$event->title}&amp;dates={if isset($event->end_date) && isset($event->end_time)}{$event->getCalDate($event->date, $event->time)}/{$event->getCalDate($event->end_date, $event->end_time)}{else}{$event->getCalDate($event->date, $event->time)}/{$event->getCalDate($event->date, $event->time)}{/if}&amp;details={$event->description}&amp;location={$event->address}&amp;trp=false&amp;sprop={$EVENT_URL}/{$event->eid}&amp;sprop={$event->description}" class="icon-gcal" target="_blank">Google Calendar</a> <a href="{$CURHOST}/calendar/vcs?eventId={$event->eid}" class="icon-outlook" target="_blank">Outlook</a></p>
                        </fieldset>
                    </div>
                    <div style="clear:both"></div>
                </aside>

            </div>
        </div>
</article>
<div id="dialog"></div>
<input type="hidden" id="count_images" value="0" name="count_images" />
{include file="footer.tpl"}
{include file="popup_login.tpl"}
{include file="popup_seeall.tpl"}

{include file="js_global_event.tpl"}
{include file="js_event.tpl"}
<script type="text/javascript" src="{$JS_PATH}/jquery.min.js"></script>
<script type="text/javascript" src="{$JS_PATH}/jquery.fancybox.js"></script>
<link rel="stylesheet" type="text/css" href="{$CURHOST}/css/jquery.fancybox.css" media="screen" />
<link rel="stylesheet" type="text/css" href="{$CURHOST}/css/jquery.fancybox-buttons.css?v=2.0.3" />
<script type="text/javascript" src="{$JS_PATH}/jquery.fancybox-buttons.js?v=2.0.3"></script>
<link href="{$JS_PATH}/uploadify/uploadify.css" type="text/css" rel="stylesheet" />
<script src="{$JS_PATH}/uploadify/swfobject.js"></script> 
<script src="{$JS_PATH}/uploadify/jquery.uploadify.v2.1.4.js"></script>
<script src="{$JS_PATH}/jquery.tinyscrollbar.min.js"></script>
<script src="{$JS_PATH}/fb_share.js"></script>
<script language="javascript">
var queSize     = 50;
var uploaded	= 1;
var totalQueued = 0;
var t = '';
var tb = '';
$(document).ready(function() {
  $('#file').uploadify({
    'uploader'  : '{$JS_PATH}/uploadify/uploadify.swf',
    'script'    : '{$CURHOST}/upload/event/images',
    'cancelImg' : '{$JS_PATH}/uploadify/cancel.png',
    'folder'    : '{$CURHOST}/eventimages/',
    'multi'     : true,
	'auto'		: true,
	'scriptData': {$userid},
	'queueSizeLimit' : queSize,
	'buttonImg' : '{$CURHOST}/images/btn_chooseFle_2.png',
	'fileExt'   : '*.jpg;*.jpeg;*.gif;*.JPG;*.JPEG;*.GIF',
	'sizeLimit' : '5242880',
	'onComplete'  : function(event, ID, fileObj, response, data) {
		queSize--;
		uploaded++;
    },
	'onAllComplete' : function(event,data) {
		$("#upload-progress").fadeOut(500);
		$("#upload-complete").fadeIn(500);
		$("#upload-total-complete").html(totalQueued);
		$("#upload-completed-complete").html(totalQueued);
		reloadImages({$userid});
		totalQueued = 0;
		uploaded = 1;
    },
	'onSelect': function(event,ID,fileObj) {
		++totalQueued;
    },
	'onProgress'  : function(event,ID,fileObj,data) {
		$("#upload-progress").fadeIn(500);
		$("#upload-total").html(totalQueued);
		$("#upload-completed").html(uploaded);
		$("#loadingPercentage").css("width", Math.round(((uploaded/totalQueued)*100))+"%");
    },
	'onError': function(event,ID,fileObj, d)
	{
		/*$("#upload-progress").fadeOut(500);
		$("#upload-complete").fadeOut(500);*/
		if(d.info == 500)
		{
			$("#upload-error-new").fadeIn(500);	
		}else
		{
			$("#upload-error").fadeIn(500);
		}
		totalQueued = 0;
		uploaded = 1;
		queSize = 50;
		//alert("Error: "+d.type+"      Info: "+d.info);
	}
	
	
  });
});
function startUpload()
{
	$('#file').uploadifyUpload();	
}
function reloadImagesAll()
{
	reloadImages({$userid});	
}
function reloadImages(data)
{
	$("#viewBy").hide();
	//$("#sort_by").html('All');
	$.ajax({
		type: 'POST',
		data: data,
		url : '{$CURHOST}/event/reloadImages',
		cache: false,
		dataType: 'json',
		success: function(response)
		{
			$("#container_iso").html(response.html);
			$("#sort_by").html(response.users_dropdown);
			$("#count_images").val(response.count_images);
			if(response.count_images == 0)
			{
				$("#viewby_img_div").hide();
				$("#viewby_bot_div").hide();	
			}else
			{
				$("#viewby_img_div").show();
				$("#viewby_bot_div").show();	
			}
			tb = setInterval("bindfancyBox()", 2000);
			t = setInterval("bindScrollBar()", 1000);
		}
	});
}
function reloadImagesByUser(data, uid, name)
{
	if(uid == 0)
	{
		$("#viewBy").hide();
		$('#back_to_all').hide();
	}else
	{
		$("#viewBy").show();
		$("#viewByName").html(name);
		$('#back_to_all').show();
	}
	$.ajax({
		type: 'POST',
		data: data,
		url : '{$CURHOST}/event/reloadImagesByUser',
		cache: false,
		dataType: 'json',
		success: function(response)
		{
			$("#container_iso").html(response.html);
			$("#sort_by").html(response.users_dropdown);
			$("#count_images").val(response.count_images);
			if(response.count_images == 0)
			{
				$("#viewby_img_div").hide();
				$("#viewby_bot_div").hide();	
			}else
			{
				$("#viewby_img_div").show();
				$("#viewby_bot_div").show();	
			}
			tb = setInterval("bindfancyBox()", 2000);
			t = setInterval("bindScrollBar()", 1000);
		}
	});
}
function loadImagesByUser(uid)
{
	
}
function bindfancyBox()
{
	clearInterval(tb);
	$('.fancybox-buttons').fancybox({
		openEffect  : 'none',
		closeEffect : 'none',
		prevEffect : 'none',
		nextEffect : 'none',
		closeBtn  : false,
		helpers : {
			title : {
				type : 'inside'
			},
			buttons	: {}
		},
		afterLoad : function() {
			var index_to_show = (this.index + 1);
			var href_download = str_replace("{$CURHOST}/eventimages/", "", this.href);
			var hrefShare = $("#eventURL").text();
			var title_to_show = '<div style="float:left; font-size:10px; max-width:80px; line-height:normal;">by '+$("#by_name_"+index_to_show).html()+'</div>';
			title_to_show += '<div style="float:right; text-align:right; font-size:10px; line-height:normal">Share <img src="{$CURHOST}/images/connect_favicon.png" class="fb_share" onclick="streamPublish(\''+(hrefShare)+'\', \'Check out this photo from '+($("#event_title").html())+'\', \''+(this.href)+'\');" /><br /><a href="{$CURHOST}/event/imageDownload/?file='+href_download+'">Download</a></div>';
			this.title = title_to_show;
			//this.title = 'Image ' + (this.index + 1) + ' of ' + this.group.length + (this.title ? ' - ' + this.title : '');
		}
	});
}
function openFaceBox()
{
	$('.fancybox-buttons').click();
}
function bindScrollBar()
{
	clearInterval(t);
	var oScrollbar = $('#container_isolate');
	oScrollbar.tinyscrollbar();
	oScrollbar.bottom($("#count_images").val());
	//oScrollbar.tinyscrollbar_update('relative');
	//$('#container_isolate').tinyscrollbar();	
}
function changeImagesView(uid, name)
{
	var data = "uid="+uid+"&eid={$event->eid}";
	$.ajax({
		type: 'POST',
		url: '{$CURHOST}/event/getJsonArray',
		data: data,
		cache: false,
		dataType: 'json',
		success: function(response)
		{
			reloadImagesByUser(response.val, uid, name);
		}
	});
}
function deletePhoto(delId)
{
	$("#delete-image").fadeIn(500);
	$("#delImageId").val(delId);
}
function delImage()
{
	var delId = $("#delImageId").val();
	$.ajax({
		type: 'POST',
		url: '{$CURHOST}/event/delImage/',
		data: 'delId='+delId,
		cache: false,
		success: function(response)
		{
			if(response)
			{
				$("#delete-image").fadeOut(500);
				$("#delImageId").val('');
				reloadImages({$userid});
			}	
		}
	});
}
reloadImages({$userid});
</script>
<div class="popup_box_main" id="loader" style="display:none;">
    <div class="popup_overlay"></div>
    <div class="popup_box" align="center">
        <div class="popup_box_inr">
    	<div style="width:100%; text-align:center;">Please wait as we load your photo.</div>
        <div style="text-align:center;"><img src="{$CURHOST}/images/loader.gif" /></div>
        </div>
    </div>
</div>
    </body>
</html>