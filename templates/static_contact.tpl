{include file="head.tpl"}
<body>

{include file="header.tpl"}
<div id="container">
	<section class="block form-contact">
		<header class="block">
			<p class="message">Contact Us</p>
		</header>{if isset($notification)}

		<header class="block notification">
			<p>{$notification}</p>
		</header>{else}{if isset($error)}

		<header class="block error">
			<p>Please fix the errors below.</p>
		</header>{/if}

		<p class="message-small">Have any questions or concerns?<br />We'd love to hear from you!</p>
		<form method="post" action="{$CURHOST}/contact">
			<fieldset id="contact_form">
				<dl>
					<dt>
						<label for="name">Your name</label>
					</dt>
					<dd>
						<input type="text" name="name"{if isset($smarty.session.user)} value="{$smarty.session.user->fname} {$smarty.session.user->lname}" readonly="readonly"{elseif isset($smarty.post.name)} value="{$smarty.post.name}"{/if} class="inputbox autowidth" id="name" />
					</dd>
					<dt>
						<label for="email">Email<span>*</span></label>
					</dt>
					<dd{if isset($error.email)} class="error"{/if}>
						<input type="text" name="email"{if isset($smarty.session.user)} value="{$smarty.session.user->email}" readonly="readonly"{elseif isset($smarty.post.email)} value="{$smarty.post.email}"{/if} class="inputbox autowidth" id="email" />{if isset($error.email)}

						<em>{$error.email}</em>{/if}

					</dd>
					<dt>
						<label for="subject">Subject</label>
					</dt>
					<dd>
						<input type="text" name="subject"{if isset($smarty.post.subject)} value="{$smarty.post.subject}"{/if} class="inputbox autowidth" id="subject" />
					</dd>
					<dt>
						<label for="message">Message<span>*</span></label>
					</dt>
					<dd{if isset($error.message)} class="error"{/if}>
						<textarea name="message" class="inputbox autowidth" id="message">{if isset($smarty.post.message)}{$smarty.post.message}{/if}</textarea>{if isset($error.message)}

						<em>{$error.message}</em>{/if}

					</dd>
				</dl>
				<footer class="buttons buttons-submit">
					<p><span class="btn btn-med"><input type="submit" name="submit" value="Submit"></span></p>
				</footer>
			</fieldset>
		</form>{/if}

	</section>
</div>
{include file="footer.tpl"}

{include file="js_global.tpl"}
{include file="js_event.tpl"}

</body>
</html>
