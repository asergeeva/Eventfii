{include file="head.tpl"}
<body>

{include file="new_header.tpl"}
<div id="container">
	{include file="cp_header.tpl"}
	<section id="main">
		<div id="navigation">
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
		<div id="content">{if isset($page.contacts)}{include file="block_contacts.tpl"}{elseif isset($page.addcontacts)}{include file="block_addguests.tpl"}{/if}
		</div>
	</section>
</div>
{include file="footer.tpl"}

{include file="js_global.tpl"}
{include file="js_cp.tpl"}
<script src="{$JS_PATH}/openinviter.js" type="text/javascript" charset="utf-8"></script>
<script src="{$JS_PATH}/uploader.js" type="text/javascript" charset="utf-8"></script>
<script src="{$JS_PATH}/contacts.js" type="text/javascript" charset="utf-8"></script>


</body>
</html>