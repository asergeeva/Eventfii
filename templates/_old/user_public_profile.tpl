{include file="header.tpl"}
<body>

{include file="cp_header.tpl"}
<div id="container">
	<header id="header">
		<h1>Meet {$userInfo['fname']} {$userInfo['lname']}!</h1>
	</header>
	<section id="main">
		<aside class="extra">
			{include file="user_profile_pub.tpl"}
			<section class="block" id="user-social">
				<ul class="network">
					<li><a href="http://facebook.com/sergeeva1" class="icon-facebook">sergeeva1</a></li>
					<li><a href="http://twitter.com/asergeeva" class="icon-twitter">@asergeeva</a></li>
				</ul>
			</section>
			<footer class="follow">
				<p><a href="#">Follow</a></p>
			</footer>
		</aside>
		<div class="content">
			{include file="event_created_pub.tpl"}
			{include file="event_attending.tpl"}
		</div>
	</section>
</div>
{include file="footer.tpl"}

{include file="global_js.tpl"}
</body>
</html>
