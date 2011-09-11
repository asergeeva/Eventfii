{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="container">
	{include file="cp_header.tpl"}
	<section id="main">
		<div class="navigation">
			<nav class="block" id="manage-before">
				<header class="block-title">
					<h1>Address Book</h1>
				</header>
				<ul>
					<li{if isset($page["contacts"])} class="current"{/if}><a href="{$CURHOST}/contacts"><span>View all contacts</span></a></li>
					<li{if isset($page["addcontacts"])} class="current"{/if}><a href="{$CURHOST}/contacts/add"><span>Add contacts</span></a></li>
				</ul>
			</nav>
		</div>
		<div class="content">
			<header class="block">
				<p class="message">Make your event well known! Add your contacts.</p>
			</header>{if isset($page.addcontacts)}

			{include file="guest_add.tpl"}{else}
			
			{include file="contacts.tpl"}{/if}

		</div>
	</section>
</div>
{include file="footer.tpl"}

{include file="js_global.tpl"}
{include file="js_cp.tpl"}
<script type="text/javascript" language="javascript" src="{$JS_PATH}/openinviter.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/uploader.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/contacts.js"></script>


</body>
</html>