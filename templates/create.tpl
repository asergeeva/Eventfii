{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="container">
	<header id="header">
		{if $step1}
		<h1>Create a New Event</h1>
		{else if $step2}
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
					<li{$step1}><span>Add event information</span></li>
					<li{$step2}><span>Add guests</span></li>
					<li><span>trueRSVP</span></li>
				</ol>
			</nav>
			<footer class="links-extra">
				<p><a href="{$CURHOST}">Back to home</a></p>
			</footer>
		</aside>
		<div class="content">
			<section class="block">
				{if $step1}
				{if $error}
				<header class="block">
					<p class="message">Please fix the errors below before continuing.</p>
				</header>
				{/if}
				{include file="create_form.tpl"}
				{else if $step2}
				{include file="create_guest.tpl"}
				{else if $step3}
				Done!	
				{/if}
			</section>
		</div>
	</section>
</div>
{include file="footer.tpl"}

{include file="js_global.tpl"}
{*include file="js_cp.tpl"*}
{*include file="js_create.tpl"*}

</body>
</html>
