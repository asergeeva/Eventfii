<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="https://platform.twitter.com/anywhere.js?id={$TWITTER_API}&amp;v=1"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
<script type="text/javascript" src="{$JS_PATH}/jquery-ui-1.8.11.custom.min.js"></script>
<script type="text/javascript" src="{$JS_PATH}/jquery.tools.min.js"></script>
<script type="text/javascript" src="{$JS_PATH}/jquery.validate.pack.js"></script>
<script type="text/javascript" src="{$JS_PATH}/contactable.js"></script>
<script type="text/javascript" src="{$JS_PATH}/fileuploader.js"></script>
<script type="text/javascript" src="{$JS_PATH}/json2.js"></script>
<script src="https://connect.facebook.net/en_US/all.js#appId={$FB_APP_ID}&amp;xfbml=1"></script>
<script type="text/javascript">{literal}
/*
 * Author : Grady Laksmono
 * Email : grady@truersvp.com
 * All code (c) 2011 trueRSVP Inc. 
 * All rights reserved
 */
var EFGLOBAL = (function() {
	return {
		ajaxLoader: '<img src="images/ajax-loader.gif" alt="loading" class="ajax-loader" />',
		baseUrl: '{/literal}{$CURHOST}{literal}',
		fbAppId: '{/literal}{$FB_APP_ID}{literal}',
		attendSucceed: '<h2>Success!</h2>',
		isSucceed: '<span class="succeed_text">Success</span>',
		fbGraph: '{/literal}{$FB_GRAPH_URL}{literal}'
	}
}());

$(document).ready(function() {
	$('#contactable').contactable({
		subject: 'feedback URL:'+location.href
	});
	$(".dropdown dt a").click(function() {
	
	// Change the behaviour of onclick states for links within the menu.
	var toggleId = "#" + this.id.replace(/^link/,"ul");
	
	// Hides all other menus depending on JQuery id assigned to them
	$(".dropdown dd ul").not(toggleId).hide();
	
	//Only toggles the menu we want since the menu could be showing and we want to hide it.
	$(toggleId).toggle();
	
	//Change the css class on the menu header to show the selected class.
	if($(toggleId).css("display") == "none"){
	$(this).removeClass("selected");
	}else{
	$(this).addClass("selected");
	}
	
	});
	
	$(".dropdown dd ul li a").click(function() {
	
	// This is the default behaviour for all links within the menus
	var text = $(this).html();
	$(".dropdown dt a span").html(text);
	$(".dropdown dd ul").hide();
	});
	
	$(document).bind('click', function(e) {
	
	// Lets hide the menu when the page is clicked anywhere but the menu.
	var $clicked = $(e.target);
	if (! $clicked.parents().hasClass("dropdown")){
	$(".dropdown dd ul").hide();
	$(".dropdown dt a").removeClass("selected");
	}});
});
{/literal}
</script>
