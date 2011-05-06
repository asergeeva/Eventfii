{include file="header.tpl" title="Jumpstart your social life"}
{include file="cp_css.tpl"}
<link rel="stylesheet" type="text/css" href="{$CSS_PATH}/guest_style.css" />
</head>

<body id="cp_body">
<div id="container">
  <div id="cp_container">
    <div id="header">
    {include file="cp_header_guest.tpl"}
    </div>
    <div id="middle">
      <div id="event_created_container">
        <h2>Event created</h2>
        {include file="event_created_pub.tpl"}
      </div>
      <div id="event_attended_container">
        <h2>Events attending</h2>
        {include file="event_attending.tpl"}
      </div>
      <div id="user_profile_container">
        <h2>Profile</h2>
        {include file="user_profile.tpl"}
      </div>
    </div>
    <div id="footer"></div>
  </div>
</div>
{include file="global_js.tpl"}
</body>
{include file="footer.tpl"}