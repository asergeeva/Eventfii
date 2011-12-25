{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="container">
	{include file="manage_header.tpl"}
	<section id="main">
		{include file="manage_nav.tpl"}
		<div id1="content">
			<header class="block">
				<p class="message">Pick the check-in option that's right for your event.</p>
			</header>
			<section class="block" id="manage-checkin">
				<section class="block">
					<header>
						<img src="{$CURHOST}/images/checkin_qr.gif" alt="QR Code" />
					</header>
					<p>
						<span>Scan attendee tickets with our trueRSVP mobile app availalble in the iTunes stores & Andriod Marketplace.</span>
						<em>Recommended for events that requite a registration process.</em>
					</p>
				</section>
				<section class="block">
					<header>
						<img src="{$CURHOST}/images/checkin_manual.gif" alt="Manual Checkin" />
					</header>
					<p>
						<span>Manually check off guests who showed up on the manage page of the site 
& mobile app.</span>
						<em>Ideal for small gatherings.</em>
					</p>
				</section>
				<section class="block">
					<header>
						<img src="{$CURHOST}/images/checkin_geolocation.gif" alt="Geolocation Checkin" />
					</header>
					<p>
						<span>Let your attendees check themselves in with our trueRSVP mobile app.</span>
						<em>Perfect for large outdoor events.</em>
					</p>
				</section>
			</section>
		</div>
	</section>
</div>
{include file="footer.tpl"}

{include file="js_global.tpl"}
{include file="js_manage.tpl"}
{include file="js_cp.tpl"}
{include file="js_create.tpl"}

</body>
</html>
