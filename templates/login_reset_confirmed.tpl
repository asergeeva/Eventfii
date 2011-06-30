{include file="header.tpl" title="Jumpstart your social life"}
{include file="cp_css.tpl"}
{include file="home_css.tpl"}
<link rel="stylesheet" type="text/css" href="{$CSS_PATH}/login_style.css" />
</head>

<body>
<div id="fb-root"></div>
<div id="container">
  <div id="header">
	{include file="home_header.tpl"}
  </div>
	<div id="login_forgot">
    <h3>Reset Password</h3>
    <h4>Password has successfully been reset</h4>
  </div>
</div>
{include file="global_js.tpl"}
<script type="text/javascript" language="javascript" src="{$JS_PATH}/md5-min.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/fb.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/login.js"></script>
{include file="footer.tpl"}