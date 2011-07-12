{include file="header.tpl"}

<body>
{include file="cp_header.tpl"}
<header id="section-header">
	<h1>{$userInfo['fname']} {$userInfo['lname']}</h1>
</header>
<div id="container">
  <div class="section">
    {include file="user_profile_pub.tpl"}
  </div>
  <div class="section">
    <section class="block">
      <h1 class="block-title">Events Attended</h1>
      {include file="event_attending.tpl"}
    </section>
  </div>
  <div class="section">
    <section class="block">
      <h1 class="block-title">Events Hosted</h1>
      {include file="event_created_pub.tpl"}
    </section>
  </div>
</div>
{include file="global_js.tpl"}
{include file="footer.tpl"}