{include file="header.tpl"}

<body>
{include file="home_header.tpl"}
<div class="event_guest_invite_overlay" id="event_guest_invite_overlay">
  	{include file="event_invite_guest_create.tpl"}
</div>
<div id="container">
  {include file="create_event_form.tpl"}
</div>
{include file="global_js.tpl"}
<script type="text/javascript" language="javascript" src="{$JS_PATH}/md5-min.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/login.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/create_event_form.js"></script>
{include file="footer.tpl"}