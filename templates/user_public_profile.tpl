{include file="header.tpl"}
<body>

{include file="cp_header.tpl"}
<div id="container">
	<header id="header">
		<h1>Meet {$userInfo['fname']} {$userInfo['lname']}!</h1>
	</header>
	<section id="main">
		<div class="content">
			{include file="event_created_pub.tpl"}
			{include file="event_attending.tpl"}
		</div>
		<aside class="extra">
			{include file="user_profile_pub.tpl"}
		</aside>
	</section>
</div>
{include file="footer.tpl"}

{include file="global_js.tpl"}
</body>
</html>
