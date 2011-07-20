{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="container">
	<header id="header">
		<h1>Create a New Event</h1>
	</header>
	<section id="main"> 
		<aside class="navigation">
			<nav class="block" id="cp-nav">
				<header class="block-title">
					<h1>Steps</h1>
				</header>
				<ol>
					<li{$step1}><a href="#"><span>Add event information</span></a></li>
					<li{$step2}><a href="#"><span>Add guests</span></a></li>
					<li><a href="#"><span>trueRSVP</span></a></li>
				</ol>
			</nav>
			<footer class="links-extra">
				<p><a href="cp.html">Back to home</a></p>
			</footer>
		</aside>
		<div class="content">
			<section class="block">
				{if $step1}
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
