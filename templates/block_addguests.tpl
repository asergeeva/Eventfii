{if ! isset($smarty.get.option) && ! isset($page.addcontacts) && ! isset($page.addguests)}

			<header class="block notification">
				<p class="message">Congrats, your event has been created! Add guests now or come back to this step later.</p>
			</header>{elseif isset($error) && strlen($error) > 0}

			<header class="block error">
				<p class="message">{$error}</p>
			</header>{else if isset($notification) && strlen($notification) > 0}

			<header class="block notification" id="message-notification">
				<p class="message" id="message-notification-content">{$notification}</p>
			</header>{/if}
			
			<header class="block notification" style="display:none" id="fb-notification-box">
				<p class="message" id="fb-notification-message">Facebook requests is sent successfully</p>
			</header>
			<header class="block notification" style="display:none" id="csv-notification-box">
				<p class="message" id="csv-notification-message">CSV is successfully uploaded</p>
			</header>
			<div style="display:none" id="fb-message">Hi, I'm inviting you to an event that I'm organizing at trueRSVP: {$event->title}</div>
			
			<section class="block">
				<nav class="horizontal-nav">
					<ul>{if !isset($page.addcontacts)}

						<li><a href="?{if isset($event)}eventId={$event->eid}&amp;{/if}option=trueRSVP" class="btn btn-manage{if ! isset($smarty.get.option) || $smarty.get.option == 'trueRSVP'} current{/if}"><span>trueRSVP Contacts</span></a></li>{/if}

						<li><a href="?{if isset($event)}eventId={$event->eid}&amp;{/if}option=manual" class="btn btn-manage{if isset($smarty.get.option) && $smarty.get.option == 'manual' || (isset($page.addcontacts) && $page.addcontacts && ! isset($smarty.get.option))} current{/if}"><span>Manually Add</span></a></li>{*
						if !isset($page.addcontacts)}
						<li><a href="#" id="guest_facebook_add" class="btn btn-manage{if isset($smarty.get.option) &&$smarty.get.option == 'fb'} current{/if}"><span>Add from Facebook</span></a></li>
						{/if*}
						<li><a href="?{if isset($event)}eventId={$event->eid}&amp;{/if}option=csv" class="btn btn-manage"><span>Import CSV</span></a></li>
						<li><a href="?{if isset($event)}eventId={$event->eid}&amp;{/if}option=import" class="btn btn-manage{if isset($smarty.get.option)  && $smarty.get.option == 'import'} current{/if}"><span>Gmail/Yahoo Import</span></a></li>
					</ul>
				</nav>{if isset($smarty.get.option) && $smarty.get.option == 'csv'}
				
				<form method="post" action="{$submitTo}" id="create_guests">
					<div id="csv_container" style="margin-bottom:10px"></div>
					<textarea name="emails" id="emails-hidden" style="display:none"></textarea>
					<p id="add_import_contact_list" style="display:none"><span class="btn btn-med"><input type="submit" name="submit" value="Invite" /></span></p>
          <p style="text-align:center;margin-bottom:10px;">Upload a CSV with your guests' emails here.</p>
					<p style="text-align:center">
						<a href="#" class="btn btn-small" id="csv_upload"><span>Upload</span></a>
					</p>
          {if ! isset($page.contacts) && ! isset($page.addcontacts) && ! isset($page.manage)}
         <p style="float:right"><a href="{$finishSubmit}&submit=true" class=""><span>I'm done, show me my event!</span></a>{if sizeof($guests) == 1} <a href="{$CURHOST}/$event/a/{$event->alias}?created=true">Skip this step</a>{/if}</p> {/if}
				</form>{elseif isset($smarty.get.option) &&  $smarty.get.option == 'import'}

				<div class="block">
					<div id="oi_logo"></div>
					<div id="oi_container">
						<header class="import">
							<h1>Secure Contact Importer</h1>
							<h2>trueRSVP will not store your e-mail and password</h2>
						</header>
						<div class="import-options">
							<img src="{$CURHOST}/images/icon_gmail.gif" alt="Gmail" /> <span>or</span> <img src="{$CURHOST}/images/icon_yahoo.gif" alt="Yahoo Mail" />
						</div>
						<fieldset>
							<dl>
								<dt class="inline"><label for="oi_email">Email:</label></dt>
								<dd><input type="text" name="oi_email" class="inputbox" id="oi_email" /></dd>
								<dt class="inline"><label for="oi_pass">Password:</label></dt>
								<dd><input type="password" name="oi_pass" class="inputbox" id="oi_pass" /></dd>
							</dl>
							<footer class="buttons buttons-submit">
								<p><a href="#search-container" class="btn btn-small" id="oi_import"><span>Grab Contacts</span></a></p>
                 {if ! isset($page.contacts) && ! isset($page.addcontacts) && ! isset($page.manage)}
         <p style="float:right"><a href="{$finishSubmit}&submit=true" class=""><span>I'm done, show me my event!</span></a>{if sizeof($guests) == 1} <a href="{$CURHOST}/$event/a/{$event->alias}?created=true">Skip this step</a>{/if}</p> {/if}
								<input type="hidden" name="oi_provider" id="oi_provider" value="{if isset($provider)}{$provider}{/if}" />
							</footer>
						</fieldset>
					</div>
					<div id="import_form_container" style="display:none">
						<form method="post" action="{$submitTo}" id="create_guests">
							<textarea name="emails" id="emails-hidden" style="display:none"></textarea>
							<footer class="buttons buttons-submit">
								<p><span class="btn btn-med"><input type="submit" name="submit" value="Invite" id="add_import_contact_list" /></span></p>
                {if ! isset($page.contacts) && ! isset($page.addcontacts) && ! isset($page.manage)}
         <p style="float:right"><a href="{$finishSubmit}&submit=true" class=""><span>I'm done, show me my event!</span></a>{if sizeof($guests) == 1} <a href="{$CURHOST}/$event/a/{$event->alias}?created=true">Skip this step</a>{/if}</p> {/if}
							</footer>
						</form>
					</div>
 				</div>{else}{if ( ! isset($smarty.get.option) || $smarty.get.option == 'trueRSVP' ) && ! isset($page.addcontacts)}
				{if $smarty.session.user->facebook eq '' || $smarty.session.user->facebook eq NULL}
				<aside class="facebook-connect">
					<p>Import your Facebook friends</p>
				  <fb:login-button scope="email,publish_stream" id="fb-login-button" onlogin="FBCON.onlogin()">Import your facebook friends</fb:login-button>
				</aside>
                {/if}
       <section class="block">{if isset($contacts) || isset($fbContacts)}

					<p style="float: right"><a href="#" id="select_contact_all">Select all</a> | <a href="#" id="select_contact_none">Select none</a></p>
					<header class="block-title">
						<h1>Contacts</h1>
					</header>
					<!--<div id="search-container" >Search: <div id="contacts-header"></div></div>-->
          <form method="post" action="#" class="quicksearch" id="contacts-header">
            <fieldset>
              <span class="fl"><input type="text" value="Search by name" /></span>
                          
            </fieldset>
          </form>
         	<ul class="user-list" id="contacts-list">{if isset($contacts)}{foreach $contacts as $contact}
						<li>
							<label{if ! isset($addButton)}>{else} for="{$contact->cid}">							
								<input type="checkbox" id="{$contact->cid}" value="{$contact->cid}" class="selected_contact {if $contact->is_email}contact-email{/if}" />{/if}

								<img src="{$contact->pic}?type=square" width="36" height="36" alt="{$contact->email}" />{if isset($contact->name) && strlen($contact->name) > 0}

								<h3>{$contact->name}</h3>{/if}

								<span>{$contact->friendly_cid}</span>
							</label>
						</li>{/foreach}{/if}
						
{*
						if isset($fbContacts)}
						{foreach $fbContacts as $contact}
						<li>
							<label for="fb-contact-{$contact['fb_id']}">{if isset($addButton)}
							
								<input type="checkbox" id="fb-contact-{$contact['fb_id']}" value="{$contact['fb_id']}" class="selected_contact contact-fb" />{/if}

								<img src="http://graph.facebook.com/{$contact['fb_id']}/picture" width="36px" height="36px" alt="{$contact['fb_id']}" />
								<h3>{$contact['fb_name']}</h3>
								<p>Facebook contact</p>
							</label>
						</li>
						{/foreach}
						{/if*}
					</ul>
					<form method="post" action="{if isset($submitTo)}{$submitTo}{/if}" id="create_guests">
						<textarea name="emails" id="emails-hidden" style="display:none"></textarea>
						<input type="submit" name="submit" id="submit_create_guests" style="display:none" />
						<footer class="buttons buttons-submit">
							<p><span class="btn btn-med"><input type="button" name="invite" value="Invite" id="add_import_contact_list" /></span></p>
              {if ! isset($page.contacts) && ! isset($page.addcontacts) && ! isset($page.manage)}
         <p style="float:right"><a href="{$finishSubmit}&submit=true" class=""><span>I'm done, show me my event!</span></a>{if sizeof($guests) == 1} <a href="{$CURHOST}/$event/a/{$event->alias}?created=true">Skip this step</a>{/if}</p> {/if}
						</footer>
					</form>{else}

					<header class="block error">
						<p class="message">No contacts</p>
					</header>
					<footer class="message">
						<p>Use the options above to add guests to your event. Guests added to your event will automatically be added to your trueRSVP contact list. You can also <a href="{$CURHOST}/contacts/add">add contacts</a> to your contact list through your control panel.</p>
					</footer>{/if}

				</section>{else}

				<section class="block">
					<form method="post" action="{if isset($submitTo)}{$submitTo}{/if}" id="create_guests">
						<textarea name="emails" id="emails-hidden" style="display:none" enctype="multipart/form-data"></textarea>
						<fieldset>
							<legend>Manually add guests</legend>
							<label>Enter e-mails separated by a comma</label>
							<textarea name="emails" class="inputbox autowidth"></textarea>
							<footer class="buttons buttons-submit"> 
								<p><span class="btn btn-med"><input type="submit" name="submit" value="Invite" id="add_import_contact_list" /></span></p>
                {if ! isset($page.contacts) && ! isset($page.addcontacts) && ! isset($page.manage)}
         <p style="float:right"><a href="{$finishSubmit}&submit=true" class=""><span>I'm done, show me my event!</span></a>{if sizeof($guests) == 1} <a href="{$CURHOST}/$event/a/{$event->alias}?created=true">Skip this step</a>{/if}</p> {/if}
							</footer>
						</fieldset>
					</form>
				</section>{/if}{/if}{if ! isset($page.contacts) && ! isset($page.addcontacts) && ! isset($page.manage)}

				<footer class="buttons buttons-submit">
       </footer>{/if}
<input type="hidden" name="req_uri" id="req_uri" value="fbimport" />
			</section>
<script src="{$JS_PATH}/md5-min.js" type="text/javascript" charset="utf-8"></script>
<script src="{$JS_PATH}/fb.js" type="text/javascript" charset="utf-8"></script>
<script src="{$JS_PATH}/login.js" type="text/javascript" charset="utf-8"></script>