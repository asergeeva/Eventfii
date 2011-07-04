{include file="header.tpl" title="Jumpstart your social life"}

<body id="cp_body">
{include file="cp_header.tpl"}
<div id="section-header">
	<h1>Welcome, {$currentUser['fname']}!</h1>
	<h2><a href="{$CURHOST}/user/{$currentUser['id']}" id="user-{$userInfo['id']}">View your public profile</a></h2>
</div>
<div id="private">
	<div id="main">
		<div id="container">
			<div class="section">
				<section class="block">
					<h1 class="block-title">Personal Profile</h1>
					<img class="info-pic" src="{$IMG_PATH}/user.jpg" alt="Anna" />
					<p><a href="#" class="btn-small"><span>Update</span></a></p>
					<p class="info-about">We love to organize events!</p>
					<p class="info-website">www.truersvp.com</p>
					<p><a href="#" class="btn-small"><span>Edit</span></a></p>
				</section>
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
					{include file="event_created.tpl"}
				</section>
			</div>
			</div>
		</div>
	</div>
</div>
{include file="global_js.tpl"}
<script type="text/javascript" language="javascript" src="{$JS_PATH}/create_event_form.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/cp.js"></script>
{include file="footer.tpl"}