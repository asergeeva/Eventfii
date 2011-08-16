{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="container">
	{include file="cp_header.tpl"}
	<section id="main">
		<header class="block">
			<p class="message">Make your event well known! Add your contacts.</p>
		</header>
		<div class="navigation">
			<nav class="block" id="manage-before">
				<header class="block-title">
					<h1>Address Book</h1>
				</header>
				<ul>
					<li{if isset($page["contacts"])} class="current"{/if}><a href="{$CURHOST}/event/manage/contacts/add?eventId={$smarty.session.manage_event->eid}"><span>View all contacts</span></a></li>
					<li{if isset($page["addcontacts"])} class="current"{/if}><a href="{$CURHOST}/event/manage/contacts/add?eventId={$smarty.session.manage_event->eid}"><span>Add contacts</span></a></li>
				</ul>
			</nav>
		</div>
		<div class="content">
			{include file="contacts.tpl"}
		</div>
	</section>
</div>
{include file="footer.tpl"}

{include file="js_global.tpl"}
{include file="js_cp.tpl"}
<script type="text/javascript" language="javascript" src="{$JS_PATH}/openinviter.js"></script>
<script type="text/javascript" language="javascript" src="{$JS_PATH}/uploader.js"></script>

</body>
</html>