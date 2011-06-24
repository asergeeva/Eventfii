{include file="header.tpl" title="Jumpstart your social life"}
{include file="cp_css.tpl"}
{include file="home_css.tpl"}
</head>

<body>
<div id="container">
  <div id="header">
	{include file="home_header.tpl"}
  </div>
  <div class="event_guest_invite_overlay" id="event_guest_invite_overlay">
  	{include file="event_invite_guest_create.tpl"}
  </div>
  <div id="middle">
  {include file="create_event_form.tpl"}
  </div>
  <div id="footer"></div>
</div>
{include file="global_js.tpl"}
<script type="text/javascript" language="javascript" src="{$JS_PATH}/md5-min.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/login.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/create_event_form.js"></script>
{include file="footer.tpl"}