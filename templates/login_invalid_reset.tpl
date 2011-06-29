{include file="header.tpl" title="Jumpstart your social life"}
{include file="cp_css.tpl"}
{include file="home_css.tpl"}
</head>

<body>
<div id="fb-root"></div>
<div id="container">
  <div id="header">
	{include file="home_header.tpl"}
  </div>
	<div id="login_forgot">
    <h3>Invalid reset request</h3>
  </div>
</div>
{include file="global_js.tpl"}
<script type="text/javascript" language="javascript" src="{$JS_PATH}/md5-min.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/fb.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/login.js"></script>
{include file="footer.tpl"}