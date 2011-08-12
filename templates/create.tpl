{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="container">
	<header id="header">
		{if isset($step1)}
		<h1>Create a New Event</h1>
		{else if isset($step2)}
		<h1>Add guests to your event</h1>
		{/if}
	</header>
	<section id="main"> 
		<aside class="navigation">
			<nav class="block" id="cp-nav">
				<header class="block-title">
					<h1>Steps</h1>
				</header>
				<ol>
					<li{if isset($step1)} class="current"{/if}><span>Add event information</span></li>
					<li{if isset($step2)} class="current"{/if}><span>Add guests</span></li>
				</ol>
			</nav>
			<footer class="links-extra">
				<p><a href="{$CURHOST}">Back to home</a></p>
			</footer>
		</aside>
		<div class="content">
			<section class="block">
				{if isset($step1)}
				{if isset($error)}
				<header class="block">
					<p class="message">Please fix the errors below before continuing.</p>
				</header>
				{/if}
				{include file="create_form.tpl"}
				{else if isset($step2)}
				{include file="create_guest.tpl"}
				{else if isset($step3)}
				<header class="block">
					<p class="message">Event created successfully.</p>
				</header>
				<a href="{$CURHOST}">Click here to return to your control panel</a>
				{/if}
			</section>
		</div>
	</section>
</div>
{include file="footer.tpl"}

{include file="js_global.tpl"}
{include file="js_cp.tpl"}
{include file="js_create.tpl"}

</body>
</html>