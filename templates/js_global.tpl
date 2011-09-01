<script type="text/javascript" language="javascript" src="https://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" language="javascript" src="https://platform.twitter.com/anywhere.js?id=ZJMtzKDqSB7aIkZKQxbDg&v=1"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/jquery-1.5.2.min.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/jquery-ui-1.8.11.custom.min.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/jquery.tools.min.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/fileuploader.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/json2.js"></script>
<script src="https://connect.facebook.net/en_US/all.js#appId={$FB_APP_ID}&amp;xfbml=1"></script>
<script type="text/javascript" language="javascript">{literal}
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
		isSucceed: '<span class="succeed_text">Success</span>'
	}
}());{/literal}
</script>
