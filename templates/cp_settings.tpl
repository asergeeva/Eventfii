{include file="head.tpl"}
<body>

{include file="new_header.tpl"}
<div id="container">
	{include file="cp_header.tpl"}
	<section id="main">{if isset($notification)}

		<header class="block notification">
			<p class="message">{$notification}</p>
		</header>{elseif isset($error)}

		<header class="block error">
			<p class="message">User settings not updated. Please fix the errors before continuing.</p>
		</header>{elseif isset($responseMsg['user_success'])}

		<header class="block notification" id="notification-box">
			<p class="message">{$responseMsg['user_success']}</p>
		</header>{/if}{if isset($responseMsg['password_success'])}

		<header class="block notification">
			<p class="message">{$responseMsg['password_success']}</p>
		</header>{elseif isset($responseMsg['password_error'])}

		<header class="block error">
			<p class="message">Password not changed. Please correct the errors before re-submitting the form.</p>
		</header>{/if}

		<aside id="extra">
			{include file="block_cp_user.tpl"}
		</aside>
		<div id="content">
			<section class="block" id="settings">
				<form method="post" action="{$CURHOST}/settings" autocomplete="off">
				<fieldset>
					<legend>Account Info</legend>
					<dl>
						<dt class="inline">
							<label for="fname">First Name</label>
						</dt>
						<dd{if isset($error.fname)} class="error"{/if}>
							<input type="text" name="fname" value="{$smarty.session.user->fname}" class="inputbox" id="fname" />{if isset($error.fname)}

							<em>{$error.fname}</em>{/if}

						</dd>
						<dt class="inline">
							<label for="lname">Last Name</label>
						</dt>
						<dd{if isset($error.lname)} class="error"{/if}>
							<input type="text" name="lname" value="{$smarty.session.user->lname}" class="inputbox"  id="lname" />{if isset($error.lname)}

							<em>{$error.lname}</em>{/if}

						</dd>
						<dt class="inline">
							<label for="email">Email</label>
						</dt>
						<dd{if isset($error.email)} class="error"{/if}>
							<input type="text" name="email" value="{$smarty.session.user->email}" class="inputbox" id="email" disabled="disabled" />{if isset($error.email)}

							<em>{$error.email}</em>{/if}

						</dd>
						<dt class="inline">
							<label for="other-email">Other E-mails <em>(linked with your account)</em></label>
						</dt>
						<dd>
							<p id="block-email-2">
								<input type="text" name="email" value="{$smarty.session.user->email2}" class="inputbox" id="other-email-2" disabled="disabled" />
								<a href="#" class="btn btn-manage" id="edit-email-2"><span>Edit</span></a>
								<a href="#" class="btn btn-manage" id="save-email-2" style="display:none"><span>Save</span></a>
								{if !isset($smarty.session.user->email3)}
									<a href="#" class="btn btn-manage" id="add-email-2"><span>Add</span></a>
								{/if}
							</p>
							<p id="block-email-3"{if !isset($smarty.session.user->email3)} style="display: none"{/if}>
								<input type="text" name="email" value="{$smarty.session.user->email3}" class="inputbox" id="other-email-3" disabled="disabled" />
								<a href="#" class="btn btn-manage" id="edit-email-3"><span>Edit</span></a>
								<a href="#" class="btn btn-manage" id="save-email-3" style="display:none"><span>Save</span></a>
								{if !isset($smarty.session.user->email4)}
									<a href="#" class="btn btn-manage" id="add-email-3"><span>Add</span></a>
								{/if}
							</p>
							<p id="block-email-4"{if !isset($smarty.session.user->email4)} style="display: none"{/if}>
								<input type="text" name="email" value="{$smarty.session.user->email4}" class="inputbox" id="other-email-4" disabled="disabled" />
								<a href="#" class="btn btn-manage" id="edit-email-4"><span>Edit</span></a>
								<a href="#" class="btn btn-manage" id="save-email-4" style="display:none"><span>Save</span></a>
								{if !isset($smarty.session.user->email5)}
									<a href="#" class="btn btn-manage" id="add-email-4"><span>Add</span></a>
								{/if}
							</p>
							<p id="block-email-5"{if !isset($smarty.session.user->email4)} style="display: none"{/if}>
								<input type="text" name="email" value="{$smarty.session.user->email5}" class="inputbox" id="other-email-5" disabled="disabled" />
								<a href="#" class="btn btn-manage" id="edit-email-5"><span>Edit</span></a>
								<a href="#" class="btn btn-manage" id="save-email-5" style="display:none"><span>Save</span></a>
							</p>
						</dd>
						<dt class="inline">
							<label for="user-phone">Cell #</label>
						</dt>
						<dd{if isset($error.phone)} class="error"{/if}>
							<input type="text" name="phone" value="{$smarty.session.user->phone}" class="inputbox" id="user-phone" />{if isset($error.phone)}

							<em>{$error.phone}</em>{/if}

						</dd>
						<dt class="inline">
							<label for="user-zip">Zip</label>
						</dt>
						<dd{if isset($error.zip)} class="error"{/if}>
							<input type="text" name="zip" value="{$smarty.session.user->zip}" class="inputbox" id="user-zip" maxlength="5" />{if isset($error.zip)}

						<em>{$error.zip}</em>{/if}
						</dd>						
					</dl>
				</fieldset>
				<fieldset>
					<legend>Connect with Social Networks</legend>
					<dl>
						<dt class="inline">
							<label>Twitter:</label>
						</dt>
						<dd><a href="#"><span id="connect_twitter"></span></a></dd>
						<dt class="inline">
							<label>Facebook:</label>
						</dt>
						<dd>
							<div id="fb-root"></div>
							<fb:login-button scope="email,publish_stream" id="fb-login-button" onlogin="EF_SETTINGS.fbconnect()">Login with Facebook</fb:login-button>
						</dd>
				</fieldset>{* NOT IMPLEMENTED
				<fieldset>
					<legend>Notification Options</legend>
					<dl>
						<dt class="inline">
							<label>Please select:</label>
						</dt>
						<dd>
							<label for="features">
								<p><input type="checkbox" name="notif_opt1" value="1"{if $smarty.session.user->notif_opt1 == 1} checked="checked"{/if} id="features" /> Tell me about new features every month</p>
							</label>
							<label for="updates">
								<p><input type="checkbox" name="notif_opt2" value="1"{if $smarty.session.user->notif_opt2 == 1} checked="checked"{/if} id="updates" /> Send me daily updates about my event when I’m the host</p>
							</label>
							<label for="attend">
								<p><input type="checkbox" name="notif_opt3" value="1"{if $smarty.session.user->notif_opt3 == 1} checked="checked"{/if} id="attend" /> Notify me when my friends are likely to attend the same event as I</p>
							</label>
						</dd>
					</dl>
				</fieldset>*}
				<fieldset>
					<legend>Password Change</legend>
					<dl>
						<dt class="inline">
							<label for="password-current">Enter current password</label>
						</dt>
						<dd{if isset($responseMsg['password_error'])} class="error"{/if}>
							<input type="password" name="user-curpass" class="inputbox" id="password-current" />{if isset($responseMsg['password_error'])}

							<em>{$responseMsg['password_error']}</em>{/if}

						</dd>
						<dt class="inline">
							<label for="password-new">Enter new password</label>
						</dt>
						<dd>
							<input type="password" name="user-newpass" class="inputbox" id="password-new" />
						</dd>
						<dt class="inline">
							<label for="password-confirm">Confirm new password</label>
						</dt>
						<dd>
							<input type="password" name="user-confpass" class="inputbox" id="password-confirm" />
						</dd>
					</dl>
				</fieldset>
				<footer class="buttons buttons-submit">
					<p><span class="btn btn-small"><input type="submit" name="submit" value="Save All" /></span></p>
				</footer>
				</form>
			</section>
		</div>
	</section>
</div>
{include file="footer.tpl"}

{include file="js_global.tpl"}
<script src="{$JS_PATH}/md5-min.js" type="text/javascript" charset="utf-8"></script>
<script src="{$JS_PATH}/uploader.js" type="text/javascript" charset="utf-8"></script>
<script src="{$JS_PATH}/fb.js" type="text/javascript" charset="utf-8"></script>
<script src="{$JS_PATH}/twitter.js" type="text/javascript" charset="utf-8"></script>
<script src="{$JS_PATH}/jquery.jeditable.mini.js" type="text/javascript" charset="utf-8"></script>
<script src="{$JS_PATH}/settings.js" type="text/javascript" charset="utf-8"></script>
</body>
</html>