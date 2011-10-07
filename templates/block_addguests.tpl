{if ! isset($smarty.get.option) && ! isset($page.addcontacts) && ! isset($page.addguests)}

			<header class="block notification">
				<p class="message">Congrats, your event has been created! Add guests now or come back to this step later.</p>
			</header>{elseif isset($error) && strlen($error) > 0}

			<header class="block error">
				<p class="message">{$error}</p>
			</header>{else if isset($notification) && strlen($notification) > 0}

			<header class="block notification" id="message-notification">
				<p class="message" id="message-notification-content">{$notification}</p>
			</header>{else}
			
			<header class="block notification" style="display:none" id="fb-notification-box">
				<p class="message" id="fb-notification-message">Facebook requests is sent successfully</p>
			</header>
			{/if}

			<section class="block">
				<nav class="horizontal-nav">
					<ul>{if !isset($page.addcontacts)}

						<li><a href="?{if isset($event)}eventId={$event->eid}&amp;{/if}option=trueRSVP" class="btn btn-manage{if ! isset($smarty.get.option) || $smarty.get.option == 'trueRSVP'} current{/if}"><span>trueRSVP Contacts</span></a></li>{/if}

						<li><a href="?{if isset($event)}eventId={$event->eid}&amp;{/if}option=manual" class="btn btn-manage{if isset($smarty.get.option) && $smarty.get.option == 'manual' || (isset($page.addcontacts) && $page.addcontacts && ! isset($smarty.get.option))} current{/if}"><span>Manually Add</span></a></li>
						{if !isset($page.addcontacts)}
						<li><a href="#" id="guest_facebook_add" class="btn btn-manage{if isset($smarty.get.option) &&$smarty.get.option == 'fb'} current{/if}"><span>Add from Facebook</span></a></li>
						{/if}
						{*
						<li><a href="?{if isset($event)}eventId={$event->eid}&amp;{/if}option=csv" class="btn btn-manage{if $smarty.get.option == 'csv'} current{/if}"><span>Import CSV</span></a></li>*}

						<li><a href="?{if isset($event)}eventId={$event->eid}&amp;{/if}option=import" class="btn btn-manage{if isset($smarty.get.option)  && $smarty.get.option == 'import'} current{/if}"><span>Gmail/Yahoo Import</span></a></li>
					</ul>
				</nav>{if isset($smarty.get.option) &&  $smarty.get.option == 'fb' && ! isset($page.addcontacts)}{*
				<div id="fb-root"></div>
				<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
				<fb:send href="{$EVENT_URL}/a/{$event->alias}?gref={$smarty.get.gref}"></fb:send>*}
				
				<fb:serverFbml width="582">
				<script type="text/fbml">
				  <fb:fbml>
					  <fb:request-form 
					  	action="{if isset($submitTo)}{$submitTo}{/if}" 
					  	target="_top" 
					  	method="POST" 
					  	invite="true" 
					  	type="event" 
					  	content="{$smarty.session.user->fname} invites you to {$event->title}.<fb:req-choice url='{if isset($event->alias)}{$EVENT_URL}/a/{$event->alias}{if $smarty.get.gref neq ''}?gref={$smarty.get.gref}{/if}{else}{$CURHOST}{/if}' label='Accept' />">
					  <fb:multi-friend-selector showborder="false" actiontext="{if isset($event->title)}Invite to {$event->title}{else}{$smarty.session.user->fname} added you as a contact at trueRSVP{/if}" cols="3" max="35">
					</fb:request-form>
				  </fb:fbml>
				</script>
				</fb:serverFbml>{elseif isset($smarty.get.option) && $smarty.get.option == 'csv'}

				<p><a href="#" class="btn btn-large" id="csv_upload"><span>Upload</span></a></p>{elseif isset($smarty.get.option) &&  $smarty.get.option == 'import'}

				<div class="block" id="oi_container">
					<header class="block-title">
						<h1>Import Contacts</h1>
					</header>
					<input type="hidden" name="oi_provider" id="oi_provider" value="{if isset($provider)}{$provider}{/if}" />
					<fieldset>
						<dl>
							<dt>
								<label for="oi_email">Email:</label>
							</dt>
							<dd><input type="text" name="oi_email" class="inputbox" id="oi_email" /></dd>
							<dt>
								<label for="oi_pass">Password:</label>
							</dt>
							<dd><input type="password" name="oi_pass" class="inputbox" id="oi_pass" /></dd>
						</dl>
						<footer class="buttons buttons-submit">
							<p><a href="#search-container" class="btn btn-small" id="oi_import"><span>Import</span></a></p>
						</footer>
					</fieldset>{else}{if ( ! isset($smarty.get.option) || $smarty.get.option == 'trueRSVP' ) && ! isset($page.addcontacts)}

				<section class="block">{if isset($contacts) || isset($fbContacts)}

					<header class="block-title">
						<h1>Contacts</h1>
					</header>
					<div id="search-container">Search: <div id="contacts-header"></div></div>
					<ul class="contacts-list" id="contacts-list">
						{if isset($contacts)}
						{foreach $contacts as $contact}
						<li>
							<label for="contact-{$contact->id}">{if isset($addButton)}
							
								<input type="checkbox" id="contact-{$contact->id}" value="{$contact->email}" class="selected_contact contact-email" />{/if}

								<img src="{$contact->pic}" width="36px" height="36px" alt="{$contact->email}" />{if isset($contact->fname)}
								<h3>{$contact->fname} {$contact->lname}</h3>{/if}
								<p>{$contact->email}</p>
							</label>
						</li>
						{/foreach}
						{/if}
						
						{*if isset($fbContacts)}
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
						<fieldset>
							<legend>Manually add guests</legend>
							<label>Enter e-mails separated by a comma</label>
							<textarea name="emails" class="inputbox autowidth"></textarea>
							<footer class="buttons buttons-submit"> 
								<p><span class="btn btn-med"><input type="submit" name="submit" value="Invite" /></span></p>
							</footer>
						</fieldset>
					</form>
				</section>{/if}{/if}{if ! isset($page.contacts) && ! isset($page.addcontacts) && ! isset($page.manage)}

				<footer class="buttons buttons-submit">
					<p><a href="{$finishSubmit}&submit=true" class="btn btn-med"><span>Finish</span></a>{if sizeof($signedUp) == 1} <a href="{$CURHOST}/$event/a/{$event->alias}?created=true">Skip this step</a>{/if}</p>
				</footer>{/if}

			</section>
