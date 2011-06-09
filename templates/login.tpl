{include file="header.tpl" title="Jumpstart your social life"}
{include file="home_css.tpl"}
{include file="cp_css.tpl"}
<link rel="stylesheet" type="text/css" href="{$CSS_PATH}/login_style.css" />
</head>

<body>
<div id="fb-root"></div>
<div id="container">
	{include file="login_form.tpl"}
</div>
{include file="global_js.tpl"}
<script type="text/javascript" language="javascript" src="{$JS_PATH}/md5-min.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/cp.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/create_event_form.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/login.js"></script>
<script type="text/javascript" language="javascript" src="http://connect.facebook.net/en_US/all.js"></script>
<script>
	FB.init({
		appId  : '114335368653053',
		status : true, // check login status
		cookie : true, // enable cookies to allow the server to access the session
		xfbml  : true  // parse XFBML
	});
</script>
</body>
{include file="footer.tpl"}