{include file="head.tpl"}
<body>

{include file="new_header.tpl"}
<div id="container">
	<div class="page-error">
		<h1>Uh oh! The page you requested does not exist.</h1>
    <p>{$current_page}</p>
    <p>{$error_message}</p>
	</div>
</div>
{include file="footer.tpl"}

{include file="js_global.tpl"}
{include file="js_event.tpl"}

</body>
</html>